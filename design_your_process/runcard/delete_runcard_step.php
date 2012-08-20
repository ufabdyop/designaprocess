<?php
include('runcard_common.php');
$runcard_id = $_REQUEST['runcard_id'];
$process_id = $_REQUEST['process_id'];
$order = $_REQUEST['order'];

$runcard = Runcard::get_by_id($runcard_id);
$runcard->remove_step($process_id, $order);
$runcard->save();

header('Location: edit_runcard.php?id=' . $runcard_id);

?>

