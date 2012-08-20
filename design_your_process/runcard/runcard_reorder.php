<?php
include('runcard_common.php');
$process_ids = $_REQUEST['process_id'];
$runcard_id = $_REQUEST['runcard_id'];
$runcard = Runcard::get_by_id($runcard_id);
$runcard->reorder_process_forms($process_ids);

$runcard->save();
echo "Success";    
?>