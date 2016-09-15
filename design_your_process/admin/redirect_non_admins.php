<?php

require_once('../library/authentication.php');
$auth = new Authentication();
if ($auth->is_logged_in()) {
} else {
    $auth->login();
}
