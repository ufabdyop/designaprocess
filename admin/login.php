<?php
require_once ('../library/authentication.php');
require_once ('../library/helper_functions.php');

$message = 'Please login';

if(isset($_POST['user']) and isset($_POST['pwd'])){
    $auth = new Authentication();
    $user = $auth->login($_POST['user'], $_POST['pwd']);
    if ($user) {
        if (isset($_GET['redirect_to'])) {
            header('Location: ' . base64_decode($_GET['redirect_to']));
            flush();
            die;
        } else {
            $message = "Login Successful.  Welcome $user->firstname";
        }
    } else {
        $message = "Login Failure";
    }
}

ob_clean();
ob_start();

?>

<form method="post" action="">
    <label id="username-label" for="user">Username: 
        <input type="text" name="user" id="user">
    </label>
    <label id="password-label" for="pwd"> 
	Password: <input type="password" name="pwd" id="pwd">
    </label>
	<input type="submit" value="Login">
</form>

<?
$html = ob_get_clean();
load_template('template.html', array('TITLE' => 'Login',
                                            'CONTENT' => $html,  
                                            'INSTRUCTIONS' => $message, 
                                            'LOGIN' => $login_html));

?>