<?php
require_once('../library/authentication.php');
require_once('../library/runcards.php');
$auth = new Authentication();

if (!$auth->is_logged_in()) {
	echo "Must be logged in for this feature.";
	die;
}

$process_id = addslashes($_POST['process_id']);

$run_cards = get_runcards_for_user($auth->get_user());

foreach($_POST['input_parameter'] as $id => $value) {
	$id = addslashes($id);
	$value = addslashes($value);
	$q = "INSERT INTO runcard (process_id, input_id, input_value) VALUES ('$process_id', '$id', '$value')\n";
	//db_query($q);  
}
?>
