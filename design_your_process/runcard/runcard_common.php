<?php
require_once('../library/authentication.php');
require_once('../library/runcard.php');
require_once('../library/process_form.php');
require_once('../library/process.php');
require_once('../library/helper_functions.php');
require_once('../library/form.php');
require_once('../library/table.php');
ob_clean();
ob_start();
$auth = new Authentication();
if (isset($_REQUEST['process_id'])) {
    $process_id = addslashes($_REQUEST['process_id']);
}
if (isset($_REQUEST['message'])) {
    $message = addslashes($_REQUEST['message']);
}

$auth->enforce_must_login();
$user = $auth->get_logged_in_user();

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
