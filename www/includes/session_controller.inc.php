<?php

class session_controller {
	var $authorized = false;
	var $session_lifetime;
	
	function __construct() {
		session_start();
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
			// last request was more than 30 minutes ago
			session_unset();
			session_destroy();	// destroy session data in storage
		}
		$_SESSION['LAST_ACTIVITY'] = time();
		
		if(md5(crypt($_SESSION['user'],$_SESSION['password'])) != $_SESSION['SID']) {
			if($_POST['do']) {
				$this->authorization();
			}
		} else {
			if($_GET['exit']) {
				$this->logout();
			}
			if (db_controller::user_data())
				$this->authorized=true;
		}
	}
	
	function authorization() {
		$login = preg_replace("/[^(\w-)]/",'',strip_tags(substr($_POST['login'],0,30)));
		$upass = preg_replace("/[^(\w-)]/",'',strip_tags(substr($_POST['password'],0,50)));
		if($login !='' AND $upass !='') {
			$auth_query=@mysql_query("SELECT * FROM users WHERE nick='".$login."' AND password='".md5($upass)."' AND status=1");
			if(mysql_num_rows($auth_query)===1){
				$user_data=mysql_fetch_array($auth_query);
				$this->set_session_data($user_data);
				header("Location: /");
			} else {
				header("Location: ?error=lgn_psw");
			};
		} else {
			header("Location: ?error=empty_auth_flds");
		}
	}
	
	function set_session_data($user_data) {
				$_SESSION['user'] = $user_data['nick'];
				$_SESSION['password'] = $user_data['password'];
				$_SESSION['SID'] = md5(crypt($user_data['nick'],$user_data['password']));
	}
	
	function auth_error($err){
		return $error;
	}
	
	function logout() {
		session_unset();
		session_destroy();
		unset($_GET['exit']);
		db_controller::db_close();
		header("Location: /");
	}
}
?>