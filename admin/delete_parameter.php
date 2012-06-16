<?php
require_once('../library/helper_functions.php');
$parameter_id=$_REQUEST['parameter_id'];
$process_id=$_REQUEST['process_id'];

delete_parameter_from_process ($process_id , $parameter_id);

header("Location: select_parameter.php?process_id=$process_id");

?>
