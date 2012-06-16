<?php
require_once('../library/helper_functions.php');
$process_id=$_REQUEST['process_id'];

delete_process($process_id);

header("Location: ../admin/");
?>
