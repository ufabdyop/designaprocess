<?php
$global_db_handle = false;
$global_debug = false;
$global_debug_file = '/var/log/dyop.sql.log';

function db_connect() {
  global $global_db_handle;
  $dbh;

  $dbInfo['database_target'] = "localhost";
  $dbInfo['database_name'] = "design_process";
  $dbInfo['username'] = "design_process";
  $dbInfo['password'] = "123456";

  $dbConnString = "sqlite:" . pathinfo(__FILE__, PATHINFO_DIRNAME) . "/../assets/dsp.sqlite";
  try {
    $dbh = new PDO($dbConnString);
  } catch (Exception $e) {
      print_r($e);
      ob_flush();
      die;
  }
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $global_db_handle = $dbh;
  return $dbh;
}

function _initialize_handle($handle = null)  {
    global $global_db_handle;

    if (!$handle) {
            if (!$global_db_handle) {
                    $handle = db_connect();
                    $global_db_handle = $handle;
            } else {
                    $handle = $global_db_handle ;
            }
    }
    return $handle;
}

function db_query($sql, $handle = null) {
    global $global_debug;
    global $global_debug_file;
    if ($global_debug) {
        $fh = fopen($global_debug_file, 'a');
        fwrite($fh, "SQL: " . $sql . "\n");
    }
    $handle = _initialize_handle($handle);  
    $pdo_statement = $handle->query("$sql");

    if ($global_debug) {
        if ($pdo_statement === false) {
            fwrite($fh, "SQL Error: " + print_r($handle->errorInfo(), true) . "\n");
        }
        fclose($fh);
    }
    $return_rows = NULL;
    try {
      $return_rows = $pdo_statement->fetchAll();
    } catch (Exception $e) {
    }
    return $return_rows;
}

function db_query_with_column_names($sql, $handle = null) {
    $results = db_query($sql, $handle);
    $pruned = array();
    foreach($results as $row) {
        $newrow = array();
        foreach($row as $key => $value) {
            if (is_numeric($key)) {
                continue;
            }
            $newrow[$key] = $value;
        }
        $pruned[] = $newrow;
    }
    return $pruned;
}

function last_insert_id (){
    global $global_db_handle;
    return $global_db_handle->lastInsertId();
}

/**
* named_query: function for executing named queries so we can centralize all queries here and guard against sql injection.
* @param: name: The name of the query to be run
* @param: args: The array of arguments for the query, must be the same format as PDO::execute expects--eg. array(':name' => 'bob', ':email' => 'bob@foo.com') 
* @return: result set 
*
* Interesting comment on php pdo::prepare page: Don't just automatically use prepare() for all of your queries.
		If you are only submitting one query, using PDO::query() with PDO::quote() is much faster (about 3x faster in my test results with MySQL).  A prepared query is only faster if you are submitting thousands of identical queries at once (with different data).

		If you Google for performance comparisons you will find that this is generally consistently the case, or you can write some code and do your own comparison for your particular configuration and query scenario. But generally PDO::query() will always be faster except when submitting a large number of identical queries.  Prepared queries do have the advantage of escaping the data for you, so you have to be sure to use quote() when using query().
*/
function named_query($name, $args, $handle = null) {
	$handle = _initialize_handle($handle);
	$named_queries = array(
			'create_runcard' => array( 'q' => 'INSERT INTO runcard (username, name, public) VALUES (:username, :name, :public)',
							'type' => 'insert' ),
                        'runcard_insert_inputs' => array('q' => 
            						"INSERT INTO runcard_inputs (runcard_id, ordering, process_id, input_id, input_value)
							VALUES (:id, :ordering, :process_id, :input_id, :input_value)",
                                                         'type' => 'insert'),
                        'runcard_insert_no_inputs' => array('q' => 
            						"INSERT INTO runcard_inputs (runcard_id, ordering, process_id )
							VALUES (:id, :ordering, :process_id)",
                                                         'type' => 'insert'),
		);
	if (isset($named_queries[$name])) {
		$sql = $named_queries[$name]['q'];
		$sth = $handle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute($args);
	}
}

?>
