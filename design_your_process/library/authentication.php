<?php
class User {
	var $firstname = 'Dummy';
	var $lastname = 'User';
	var $email = 'noemail@noemail.com';
	var $is_admin = true;
	var $username = 'dummy';
}

class Authentication {
	function __construct() {
		session_start();
	}
	function get_logged_in_user() {
		if (isset($_SESSION['dsp_logged_in_user'])) {
			return ($_SESSION['dsp_logged_in_user']);
		}
		return false;
	}
	function is_admin() {
		$user = $this->get_logged_in_user();
		if ($user) {
			return $user->is_admin;
		}
		return false;
	}
	function is_logged_in() {
		$user = $this->get_logged_in_user();
		return $user ? true : false;
	}
	function login($username, $password) {
		if ($username == 'admin' && $password == 'admin') {
			$user = new User();
			$user->is_admin = true;
			$_SESSION['dsp_logged_in_user'] = $user;
			return $user;
		} else if ($username == 'user' && $password == 'user') {
			$user = new User();
			$user->is_admin = false;
			$_SESSION['dsp_logged_in_user'] = $user;
			return $user;
		} else {
			return false;
		}
	}
	function logout() {
		unset($_SESSION['dsp_logged_in_user']);
	}
        function enforce_must_login() {
            if (!$this->is_logged_in())  {
                $current_page = ($_SERVER['REQUEST_URI']);
                
                //put posts into get
                if (isset($_POST)) {
                    if (strpos($current_page, '?') === false) {
                        $current_page .= '?';
                    } else {
                        $current_page .= '&';
                    }
                    foreach($_POST as $key => $val) {
                        $current_page .= urlencode($key) . '=' . urlencode($val) . "&";
                    }
                    $current_page = trim($current_page, '&');
                }
                $current_page = base64_encode($current_page);
                header("Location: login.php?redirect_to=$current_page");
            }
        }
}
?>
