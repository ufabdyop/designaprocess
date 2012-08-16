<?php

require_once('../library/authentication.php');
$auth = new Authentication();
if ($auth->is_logged_in()) {
} else {
    $current_page = base64_encode($_SERVER['REQUEST_URI']);
    
    header("Location: ../admin/login.php?redirect_to=$current_page");
}

?>

