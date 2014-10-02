<?php
defined('sCore') or die('access denied');
class session_controller {
	var $authorized = false;
	var $session_lifetime = 1800;
	var $sid_rst_time = 1800;
	
	function __construct() {
		GLOBAL $db_controller;
		$this->db_controller = $db_controller;
		session_start();
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $this->session_lifetime)) {
			// last request was more than 30 minutes ago
			session_unset();
			session_destroy();	// destroy session data in storage
		}
		$_SESSION['LAST_ACTIVITY'] = time();
		
		if (!isset($_SESSION['CREATED'])) {
			$_SESSION['CREATED'] = time();
			
		} else if (time() - $_SESSION['CREATED'] > $this->sid_rst_time) {
			// session started more than 30 minutes ago
			session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
			$_SESSION['CREATED'] = time();  // update creation time
		}
		if(md5(crypt($_SESSION['user'],$_SESSION['password'])) != $_SESSION['SID']) {
			if($_POST['do']) {
				$this->authorization();
			}
		} else {
			if($_GET['exit']) {
				$this->logout();
			}
			if ($this->db_controller->user_data())
				$this->authorized=true;
		}
	}
	
	function authorization() {
		GLOBAL $db_controller;
		$login = preg_replace("/[^(\w-)]/",'',strip_tags(substr($_POST['login'],0,30)));
		$upass = md5(preg_replace("/[^(\w-)]/",'',strip_tags(substr($_POST['password'],0,50))));
		if($login !='' AND $upass !='') {
			$user_data=$this->db_controller->auth_query($login,$upass);
			if($user_data){
				$this->set_session_data($user_data);
				header("Location: /");
			} else {
				$this->auth_error('lgn_psw');
			};
		} else {
			$this->auth_error('empty_auth_flds');
		}
	}
	
	function set_session_data($user_data) {
				$_SESSION['user'] = $user_data['nick'];
				$_SESSION['password'] = $user_data['password'];
				$_SESSION['SID'] = md5(crypt($user_data['nick'],$user_data['password']));
	}
	
	function auth_error($code){
		header("Location: ?error=$code");
	}
	
	function logout() {
		session_unset();
		session_destroy();
		unset($_GET['exit']);
		$this->db_controller->db_close();
		header("Location: /");
	}
}
?>