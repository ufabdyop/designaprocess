<?php
require_once('redirect_non_admins.php');
require_once('../library/helper_functions.php');

if(isset($_POST['submit'])){
	$category=$_POST['Category'];
	$process=$_POST['Process'];
	$tool=$_POST['Tool'];
	$material=$_POST['Material'];
		//check if process exists if it exists get id of existing parameter
	$sql="SELECT * FROM processes WHERE category = '$category' and process = '$process' and tool = '$tool' and material = '$material' ";
	$results=db_query($sql);
	if($results){
		$id=$results[0]['id'];
		echo "That process already exists. Would you like to <a href=\"select_parameter.php?process_id=$id\">edit this process</a>?";
	}else{

		//if doesn't exist enter values to database
		$sql="INSERT INTO processes (category,process,tool,material) Values ('$category','$process','$tool','$material')";
		db_query($sql);
		$id=last_insert_id();
                header ("Location: select_parameter.php?process_id=$id");
	}
        
}


ob_clean();
ob_start();


?>

		<form id="form_387875" class="appnitro"  method="post" action="">
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Category </label>
		<div>
			<input id="category" name="Category" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Process </label>
		<div>
			<input id="process" name="Process" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Tool </label>
		<div>
			<input id="tool" name="Tool" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Material </label>
		<div>
			<input id="material" name="Material" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="387875" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	

<?php

$content = ob_get_clean();
load_template('template.html', array('TITLE' => 'Add New Process', 'CONTENT' => $content, 'INSTRUCTIONS' => 'Add new Category, Process, Tool, or Material' ));


?>