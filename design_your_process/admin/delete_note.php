<?php
require_once('../library/helper_functions.php');
require_once('../library/form.php');
require_once('../library/domain_model/note.php');
require_once('redirect_non_admins.php');
$message="";

$id=$_REQUEST['id'];
$process_id=$_REQUEST['process_id'];

$note = new Note();
$note->get_by_id($id);
$note->delete();

header('Location: select_parameter.php?process_id=' . $process_id);


?>
