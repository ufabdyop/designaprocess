<?php
require_once('../library/helper_functions.php');
require_once('redirect_non_admins.php');
$message="";
$parameter_id=$_REQUEST['parameter_id'];
$process_id=$_REQUEST['process_id'];
$process_name=process_name($process_id);

$message = "Process: $process_name";

$sql="SELECT name, unit, is_input_field, is_process_parameter, is_measured_result FROM parameter WHERE id = '$parameter_id'";
$results=db_query($sql);
$curname=$results[0]['name'];
$curunit=$results[0]['unit'];
$curinput=$results[0]['is_input_field'];
$curprocess=$results[0]['is_process_parameter'];
$curresult=$results[0]['is_measured_result'];
    
$sql="SELECT value, confidence, data_origin FROM process_form WHERE process_id = '$process_id' and parameter_id = '$parameter_id'";
$results=db_query($sql);
$curvalue=$results[0]['value'];
$curconf=$results[0]['confidence'];
$curorigin=$results[0]['data_origin'];

if(isset($_POST['submit'])){
	$parameter=$_POST['parameter'];
	$unit=$_POST['unit'];
	$value=$_POST['value'];//different table
        $confidence=$_POST['confidence'];
        $data_origin=$_POST['data_origin'];
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
	validate_inputs($unit, $value);
        //check to see if new parameter is identical to an existing parameter if not enter new parameter to database
        $sql="SELECT id FROM parameter WHERE name = '$parameter' 
                and unit = '$unit' and is_input_field = '$input' 
                and is_process_parameter = '$process_parameter'
                and is_measured_result = '$measured_result'";
	$results=db_query($sql);
        if($results){
           $new_parameter_id=$results[0]['id'];   
        }else{
            $sql="INSERT into parameter (name,unit,is_input_field,is_process_parameter,is_measured_result)
                  VALUES ('$parameter','$unit','$input','$process_parameter','$measured_result')";        
            db_query($sql);  
            $new_parameter_id=last_insert_id();        
        }
        $sql = "UPDATE process_form SET process_id = '$process_id', parameter_id = '$new_parameter_id',
                value = '$value', confidence = '$confidence', data_origin = '$data_origin'
                WHERE process_id = '$process_id' and parameter_id = '$parameter_id'";
        db_query($sql);
        
        $sql = "SELECT * FROM process_form where parameter_id = '$parameter_id'";
        $results = db_query($sql);
        if ($results) {
            //another process is using the old version of the parameter
        } else {
            $sql = "DELETE FROM parameter WHERE id = '$parameter_id'";
            db_query($sql);
        }
        // insert edit record to history table in database
        $date = date('Y-m-d, h:i:s');
        $sql = "INSERT INTO history (process_id, date, type, old_name, new_name, old_unit, old_value,
                new_unit, new_value, old_confidence, new_confidence, old_data_origin, new_data_origin, 
                is_input_field, is_process_parameter, is_measured_result,
                is_equation) VALUES ('$process_id', '$date', 'edit', '$curname', '$parameter', '$curunit',
                '$curvalue', '$unit', '$value', '$curconf', '$confidence', '$curorigin', '$data_origin', '$input', '$process_parameter', '$measured_result', '0')";
        db_query($sql);
        
	header("Location: select_parameter.php?process_id=$process_id");
}

$title = "Edit Parameter";
ob_clean();
ob_start();
	
?>
<script language="javascript" src="../assets/toggle.js"> </script>
		<form id="form_387875" class="appnitro"  method="post" action="">
                                            <ul>
					<li id="li_1" >
				<label class="description" for="element_1">Parameter </label>
		<div>
			<input id="element_1" name="parameter" class="element text medium" type="text" maxlength="255" value="<?=$curname?>"/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_2" >
		<label class="description" for="element_2">Unit </label>
		<div>
			<input id="element_2" name="unit" class="element text medium" type="text" maxlength="255" value="<?=$curunit?>"/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_3" >
		<label class="description" for="element_3">Value </label>
		<div>
			<input id="element_3" name="value" class="element text medium" type="text" maxlength="255" value="<?=$curvalue?>"/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_4" >
		<label class="description" for="element_4">Input? </label>
		<span>
<?
    
    $html_for_input_radio_button = "";
    $html_for_process_parameter_radio_button = "";
    $html_for_measured_result_radio_button = "";
    if ($curinput){
        $html_for_input_radio_button = " checked=\"true\"";
    }
    if ($curprocess){
        $html_for_process_parameter_radio_button = " checked=\"true\"";
    }
    if ($curresult){
        $html_for_measured_result_radio_button = " checked=\"true\"";
    }

?>
                    		<label class="description" for="element_4">Parameter Type </label>
		<span>
<input id="input_radio"  onclick="disable_confidence_and_origin();" name="parameter_type" class="element radio" type="radio" value="input"<?=$html_for_input_radio_button?>  />
<label class="choice" for="element_4_1">Input</label>
<input id="process_parameter_radio" onclick="disable_confidence_and_origin();"  name="parameter_type" class="element radio" type="radio" value="process_parameter"<?=$html_for_process_parameter_radio_button?> />
<label class="choice" for="element_4_2">Process Parameter </label>
<input id="measured_result_radio" onclick="enable_confidence_and_origin();" name="parameter_type" class="element radio" type="radio" value="measured_result"<?=$html_for_measured_result_radio_button?> />
<label class="choice" for="element_4_3">Predicted Result </label>
<th>
		<li id="li_5" >
		<label class="description" for="confidence">Confidence Level </label>
		<div>
			<input id="confidence" name="confidence" class="element text medium" type="text" maxlength="1024" value="<?=$curconf?>" /> 
		</div> 
		</li>
</th>
<th>
		<li id="li_6" >
		<label class="description" for="data_origin">Data Origin </label>
		<div>
			<textarea id="data_origin" name="data_origin" class="origin_desc" type="text" maxlength="1024"> <?=$curorigin?> 
                        </textarea>
		</div> 
		</li>
</th>


</tr>
</table>
		</span> 
		</li>	
					<li class="buttons">
			    <input type="hidden" name="form_id" value="387875" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
                                        
		</li>
			</ul>
		</form>	
<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => '','MESSAGES' => $message ));
?>
