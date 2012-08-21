<?php
$global_db_handle = false;
$global_debug = false;
$global_debug_file = '/var/log/dyop.sql.log';

function db_connect() {
  global $global_db_handle;
  $dbh;

  $dbInfo['database_target'] = "charon.nanofab.utah.edu";
  $dbInfo['database_name'] = "design_process";
  $dbInfo['username'] = "dspuser";
  $dbInfo['password'] = "mecru";

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

function db_query($sql, $handle = null) {
    
    global $global_db_handle;
    global $global_debug;
    global $global_debug_file;
    if ($global_debug) {
        $fh = fopen($global_debug_file, 'a');
        fwrite($fh, "SQL: " . $sql . "\n");
    }
    if (!$handle) {
            if (!$global_db_handle) {
                    $handle = db_connect();
                    $global_db_handle = $handle;
            } else {
                    $handle = $global_db_handle ;
            }
    }
    $pdo_statement = $handle->query("$sql");

    if ($global_debug) {
        if ($pdo_statement === false) {
            fwrite($fh, "SQL Error: " + print_r($handle->errorInfo(), true) . "\n");
        }
        fclose($fh);
    }
    return $pdo_statement->fetchAll();
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

?>
