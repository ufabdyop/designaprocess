<?php

require_once('redirect_no_auth.php');
require_once('../library/helper_functions.php');

ob_clean();
ob_start();

if (isset($_POST)) {
	$username = $auth->get_logged_in_user()->username;
	$name = addslashes($_POST['runcard_name']);
	$public = isset($_POST['public']) ? '1' : '0';
	$sql = "INSERT INTO runcard (username, name, public) VALUES ('$username', '$name', '$public')";
	echo $sql;
}


?>

		<form id="form_387875" class="appnitro"  method="post" action="">
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Runcard Name </label>
		<div>
			<input id="runcard_name" name="runcard_name" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>
		<li id="li_1" ><label class="description" for="element_1"><input id="public" name="public" class="element" type="checkbox" /> 
		Make Runcard Public</label>
		<div>
			
		</div> 
		</li>
			
					<li class="buttons">
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	

<?php

$content = ob_get_clean();
load_template('template.html', array('TITLE' => 'Create New Runcard', 'CONTENT' => $content, 'INSTRUCTIONS' => 'Create a new runcard.' ));


?>
