<?php
include('runcard_common.php');
$runcard_id = $_REQUEST['id'];

$runcard = Runcard::get_by_id($runcard_id);
$runcard->remove();

$message = urlencode("Runcard: " . $runcard->name . " deleted.");
header('Location: browse_runcards.php?message=' . $message);
?>
