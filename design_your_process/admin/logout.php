
<?php
require_once('../library/authentication.php');
$auth = new Authentication();
$auth->logout();
header('Location: ../index.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
