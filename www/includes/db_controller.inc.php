<?php
class db_controller {
	
	var $config;
	
	function __construct() {
		$this->config['DBHOST']='localhost';
		$this->config['DBUSER']='root';
		$this->config['DBPASS']='';
		$this->config['DB']='ipcam_ru';
		$this->db_connect();
	}
	
	function db_connect() {
		$host = $this->config['DBHOST'];
		$usr = $this->config['DBUSER'];
		$pwd = $this->config['DBPASS']='';
		$db = $this->config['DB']='ipcam_ru';
	
		$this->connection = mysql_connect($host, $usr, $pwd) or die("Не могу подключиться" );
		mysql_select_db($db, $this->connection) or die ('Не могу выбрать БД');
	}
	
	function db_close() {
		mysql_close();
	}
	
	function is_static() {
		return !(isset($this) && get_class($this) == __CLASS__) ? true : false;
	}
	
	function get_db_data($data) {
		switch ($data) {
			case 'user_data':
				$query = @mysql_query("SELECT * FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."' AND status=1");
				break;
			case 'payments_data':
				$user_id = db_controller::user_data('id');
				$query = @mysql_query("SELECT * FROM payments WHERE uid=$user_id");
				break;
			case 'cams_data':
				$query = @mysql_query("SELECT cam_ip, port, login, password FROM cams WHERE uid IN (SELECT id FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."' AND status=1)");
				break;
		}
		if(@mysql_num_rows($query)!=0){
			return $query;
		} else {
			return false;
		}
	}
	
	function user_data($field=false) {
		$user_data = @mysql_fetch_array(!self::is_static() ? $this->get_db_data('user_data') : self::get_db_data('user_data'));
		return $field ? $user_data[$field] : $user_data;
	}
	
	function payments_data($field=false) {
		$payments_query = !self::is_static() ? $this->get_db_data('payments_data') : self::get_db_data('payments_data');
		$payments_data = array();
		while($data = @mysql_fetch_assoc($payments_query)){
			$payments_data[]=$data;
		};	
		return $field ? $payments_data[$field] : $payments_data;
	}
	
	function cams_data($field=false) {
		$cams_query = !self::is_static() ? $this->get_db_data('cams_data') : self::get_db_data('cams_data');
		$cams_data = array();
		while($data = @mysql_fetch_array($cams_query)){
			$cams_data[]=$data;
		};	
		return $field ? $cams_data[$field] : $cams_data;
	}

	
}
?>