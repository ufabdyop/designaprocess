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
}
?>
