<?php
require_once('../library/helper_functions.php');
require_once('redirect_non_admins.php');
$message="";

$process_id=$_REQUEST['process_id'];
$process_name=process_name($process_id);

$message = "Add parameters for process: $process_name";

if(isset($_POST['submit']) || isset($_POST['finish'])){
    $parameter=$_POST['parameter'];
    $unit=$_POST['unit'];
    $value=$_POST['value'];
    $confidence=$_POST['confidence'];
    $data_origin=$_POST['data_origin'];
    if($parameter){
    validate_inputs($unit, $value);
    add_parameter($process_id, $parameter, $unit, $value, $confidence, $data_origin);
    }
    if(isset ($_POST['finish'])){
        header("Location: select_parameter.php?process_id=$process_id");
    }else{
        $message = "Continue adding parameters for process: $process_name or select 'finish'";
    }

}

$title = "Enter Parameters";
ob_clean();
ob_start();
	
?>
<script language="javascript" src="../assets/toggle.js"> </script>

		<form id="form_387875" class="appnitro"  method="post" action="">
                                            <ul>
					<li id="li_1" >
				<label class="description" for="element_1">Parameter </label>
		<div>
			<input id="element_1" name="parameter" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_2" >
		<label class="description" for="element_2">Unit </label>
		<div>
			<input id="element_2" name="unit" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_3" >
		<label class="description" for="element_3">Value </label>
		<div>
			<input id="element_3" name="value" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_4" >
		<label class="description" for="element_4">Parameter Type </label>
		<span>
<input id="input_radio" name="parameter_type" class="element radio" type="radio" value="input" />
<label class="choice" for="input_radio">Input</label>
<input id="process_parameter_radio" name="parameter_type" class="element radio" type="radio" value="process_parameter" />
<label class="choice" for="process_parameter_radio">Process Parameter </label>
<input id="measured_result_radio" name="parameter_type" class="element radio" type="radio" value="measured_result" />
<label class="choice" for="measured_result_radio">Predicted Result </label>
</th>
<th>
		<li id="li_5" >
		<label class="description" for="confidence">Confidence Level </label>
		<div>
			<input  id="confidence" name="confidence" class="element text medium" type="text" maxlength="1024" value=""/> 
		</div> 
		</li>
</th>
<th>
		<li id="li_6" >
		<label class="description" for="data_origin">Data Origin </label>
		<div>
			<textarea  id="data_origin" name="data_origin" class="origin_desc" type="text" maxlength="1024" value=""/> 
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
                                <input id="finish" class="button_text" type="submit" name="finish" value="Finish" />   
                                
		</li>
			</ul>
		</form>	
<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => '','MESSAGES' => $message ));

?>
