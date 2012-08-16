<?php
define('DSPPATH', dirname(__FILE__) . '/../' );
$path = $_SERVER['REQUEST_URI'] ;
$path = preg_replace('/design_your_process.*/','', $path) . 'design_your_process/';
define('DSPWEBROOT', $path );
require_once('pdo.php');

/**
*
* Returns an array of all the categories in the database
*/
function get_all_types($type) {
	$sql = "select distinct $type from processes";
	$database_rows = db_query($sql);
	$return_array = array();
	foreach($database_rows as $element) {
		$return_array[] = $element[0];
	}
	return $return_array;
}
function get_all_categories() {
	return get_all_types('category');
}
function get_all_processes() {
	return get_all_types('process');
}

function get_all_tools() {
	return get_all_types('tool');
}

function get_all_materials() {
	return get_all_types('material');
}

function array_to_options_html($array) {
	$html = "";
	foreach($array as $option) {
		$html .= "<option value=\"$option\">$option</option>\n";
	}
	return $html;
}
function load_template($filename, $replacements = array(), $echo = true) {
	$file_contents = file_get_contents(DSPPATH . '/assets/' . $filename);
        $replacements['DSPWEBROOT'] = DSPWEBROOT;
	foreach($replacements as $key => $value) {
		$file_contents = str_replace('@@' . $key . '@@', $value, $file_contents);
	}
        $file_contents = preg_replace('/@@[A-Z]*@@/', '', $file_contents);
        if ($echo) {
            echo $file_contents;
        } else {
            return $file_contents;
        }
}
/**
 *this will return the description of the process specified by process_id (category , process , tool , material)
 * @param int $process_id
 * @return string 
 */

function process_name($process_id){
    $sql="SELECT category,process,tool,material FROM processes WHERE id = '$process_id'";
    $result=db_query($sql);
    $row = $result[0];
    $process_name = $row['category'] . ", " . $row['process'] . ", " . $row['tool'] . ", " . $row['material'];
    return $process_name;
}

/**
 * This will return an array of ids for all parameters that only get used by the process specified by process_id
 * @param int $process_id
 * @return array    an array of ids for parameters 
 */
function get_unique_parameters($process_id){
    $sql="SELECT DISTINCT parameter_id FROM process_form WHERE process_id = '$process_id'";
    $results = db_query($sql);
    foreach($results as $row) {
        $process_parameters[] = $row[0];
    }


    $sql="SELECT DISTINCT parameter_id FROM process_form WHERE process_id <> '$process_id'";
    $results=db_query($sql);
    foreach($results as $row) {
        $other_parameters[] = $row[0];
    }

    return array_diff( $process_parameters ,  $other_parameters);
}
/**
 *this will delete a specific process along with any relationships that it contains in the process_form table
 * and will delete any parameters that would be left without relationships after process is deleted
 * @param int $process_id 
 */

function delete_process($process_id){
    $sql="SELECT DISTINCT parameter_id FROM process_form WHERE process_id = '$process_id'";
    $results = db_query($sql);
    foreach($results as $row) {
        $process_parameters[] = $row[0];
    }


    $sql="SELECT DISTINCT parameter_id FROM process_form WHERE process_id <> '$process_id'";
    $results=db_query($sql);
    foreach($results as $row) {
        $other_parameters[] = $row[0];
    }

    $orphan_parameters=array_diff( $process_parameters ,  $other_parameters);

    $ids = implode(', ', $orphan_parameters);
    $sql="DELETE FROM parameter WHERE id IN ( $ids )";
    db_query($sql);
 
    //delete rows in process_form table that were associated with that process
    $sql="DELETE FROM process_form WHERE process_id = '$process_id'";
    db_query($sql);

    //delete process from processes table
    $sql="DELETE FROM processes WHERE id = '$process_id'";
    db_query($sql);
 
}


function delete_parameter_from_process ($process_id , $parameter_id){ 
    //insert delete record to history table
    $sql="SELECT name, unit, is_input_field, is_process_parameter, is_measured_result FROM parameter WHERE id = '$parameter_id'";
    $results=db_query($sql);
    $name=$results[0]['name'];
    $unit=$results[0]['unit'];
    $input=$results[0]['is_input_field'];
    $parameter=$results[0]['is_process_parameter'];
    $mesresult=$results[0]['is_measured_result'];
    
    $sql="SELECT value, confidence, data_origin FROM process_form WHERE process_id = '$process_id' AND parameter_id = '$parameter_id'";
    $results=db_query($sql);
    $value=$results[0]['value'];
    $confidence=$results[0]['confidence'];
    $data_origin=$results[0]['data_origin'];
    $date = date('Y-m-d, h:i:s');
    
    $sql = "INSERT INTO history (process_id, date, type, old_name, old_unit, old_value, old_confidence, old_data_origin,
            is_input_field, is_process_parameter, is_measured_result, is_equation)
            VALUES ('$process_id', '$date', 'delete', '$name', '$unit', '$value', '$confidence', '$data_origin', '$input',
            '$parameter', '$mesresult', '0')";
    db_query($sql);
    
    //delete unused parameters
    $unique_parameters = get_unique_parameters($process_id);
    if ( in_array($parameter_id, $unique_parameters)){
        $sql="DELETE FROM parameter WHERE id = $parameter_id";
        db_query($sql);
    }
    
    //delete parameter from database
    $sql = "DELETE FROM process_form WHERE parameter_id = '$parameter_id' and process_id = '$process_id'";
    db_query($sql);
   
    
}

function delete_equation ($process_id, $equation_id){
    //add delete record to database
    $sql = "SELECT name, equation, unit FROM equation WHERE id = '$equation_id'";
    $results = db_query($sql);
    $name = $results[0]['name'];
    $equation = $results[0]['equation'];
    $unit = $results[0]['unit'];
    
    $date = date('Y-m-d, h:i:s');
    $sql = "INSERT INTO history (process_id, date, type, old_name, old_unit, old_value, is_input_field,
            is_process_parameter, is_measured_result, is_equation) VALUES ('$process_id',
            '$date', 'delete', '$name', '$unit', '$equation', '0', '0', '0', '1')";
    db_query($sql);
    //delete equation from database
    $sql ="DELETE FROM equation WHERE id = '$equation_id'";
    db_query($sql);
}
function add_parameter ($process_id, $parameter, $unit, $value, $confidence, $data_origin){

	$input = 0;
	if ($_POST['parameter_type']=='input') {
		$input = 1;
	}
        $process_parameter = 0;
        if ($_POST['parameter_type']=='process_parameter'){
                $process_parameter = 1;
        }
        $measured_result = 0;
        if ($_POST['parameter_type']=='measured_result'){
                $measured_result = 1;
        }
	//check if parameter exists if it exists get id of existing parameter
	$sql="SELECT id FROM parameter WHERE name = '$parameter' and unit = '$unit'";
	$results=db_query($sql);
	if($results){
		$id=$results[0]['id'];
	}else{

		//if doesn't exist enter values to database
		$sql="INSERT INTO parameter (name,unit,is_input_field,is_process_parameter,is_measured_result)
                      VALUES ('$parameter','$unit','$input','$process_parameter','$measured_result')";
                
		db_query($sql);
		$id=last_insert_id();
	}
	$sql="SELECT * FROM process_form WHERE process_id = '$process_id' and parameter_id = '$id'";
	$results=db_query($sql);
	if($results){
		$sql="UPDATE process_form SET value = '$value', confidence = '$confidence', data_origin = '$data_origin' WHERE process_id = '$process_id' and parameter_id = '$id'";
	}else{
		$sql="INSERT INTO process_form (process_id , parameter_id , value, confidence, data_origin) Values ('$process_id','$id','$value','$confidence','$data_origin')";
	}
        db_query($sql);
        //insert record into history table
        $date = date('Y-m-d, h:i:s');
        $sql = "INSERT INTO history (process_id, date, type, new_name, new_unit, new_value, new_confidence, new_data_origin,
                is_input_field, is_process_parameter, is_measured_result, is_equation) VALUES 
                ('$process_id', '$date', 'add', '$parameter', '$unit', '$value', '$confidence', '$data_origin', '$input',
                '$process_parameter', '$measured_result', '0')";
        db_query($sql);
}
function get_parameter($id) { 
        $sql = "select * from parameter where id = '$id'"; 
        $database_rows = db_query($sql); 
        return $database_rows; 
} 
function get_parameter_for_process ($process_id , $parameter_id){   
        $sql="SELECT p.name, is_process_parameter, is_input_field, is_measured_result, pf.value FROM process_form pf  
            left join  parameter p on p.id = pf.parameter_id 
            WHERE parameter_id = '$parameter_id' and process_id = '$process_id'"; 
        $results = db_query($sql); 
        if ($results) {
            return $results[0]; 
        } else {
            return array();
        }
} 
function add_equation ($process_id , $name, $unit, $equation){
        $sql = "INSERT INTO equation (process_id , name, equation, unit) VALUES ('$process_id' , '$name' , '$equation', '$unit')";
        db_query($sql);
        
        //insert record into history table
        $date = date('Y-m-d, h:i:s');
        $sql = "INSERT INTO history (process_id, date, type, new_name, new_unit, new_value,
                is_input_field, is_process_parameter, is_measured_result, is_equation) VALUES 
                ('$process_id', '$date', 'add', '$name', '$unit', '$equation', '0',
                '0', '0', '1')";
        db_query($sql);
    
}
function validate_inputs ($unit, $value){
    $input = 0;
    if ($_POST['parameter_type']=='input') {
            $input = 1;
    }
    if($unit && !$input && !is_numeric ($value)) {
        echo "Error Invalid Input: If you enter a unit for a parameter the value field must be a numeric value.
              Either change the value to a number or remove the unit.";
        die;
    }
}
?>
