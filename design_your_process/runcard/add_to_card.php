<?php
include('runcard_common.php');

$process_id = $_REQUEST['process_id'];
$inputs = $_REQUEST['input_parameter'];
$runcard_id = null;
if (isset($_REQUEST['runcard_id'])) {
    $runcard_id = $_REQUEST['runcard_id'];
} else {
    $runcard_name = $_REQUEST['name'];
    $public = (($_REQUEST['access'] == 'public') ? 1 : 0);
}

//are we creating a new runcard or adding to an existing one?
if ($runcard_id) {
    $runcard = Runcard::get_by_id($runcard_id);
} else {
    $runcard = Runcard::create($user->username, $runcard_name, $public);
}

$process_form = Process_form::get_by_id($process_id);
foreach($inputs as $id => $value) {
    $process_form->set_parameter_by_id($id, $value);
}

$runcard->add_process_form($process_form);
$runcard->save();

header('Location: browse_runcards.php?message=' . urlencode('Process added to Runcard'));
?>
