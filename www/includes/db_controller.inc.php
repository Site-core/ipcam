<?php
class db_controller {
	
	var $config = array();
	
	function __construct() {
		$host = $this->config['DBHOST']='localhost';
		$usr = $this->config['DBUSER']='root';
		$pwd = $this->config['DBPASS']='';
		$db = $this->config['DB']='ipcam_ru';

		$this->mysqli = @new mysqli($host, $usr, $pwd, $db);
			if ($this->mysqli->connect_errno) {
				echo "Не удалось подключиться к MySQL: (" . $this->mysqli->connect_errno . ")";
			}
		if (!$this->mysqli->set_charset("utf8")) {
			printf("Ошибка при загрузке набора символов utf8: %s\n", $this->mysqli->error);
		}
	}
	
	function db_close() {
		@$this->mysqli->close();
	}
	
	function is_static() {
		return !(isset($this) && get_class($this) == __CLASS__) ? true : false;
	}
	
	function get_db_data($data,$login='',$password='') {
		if (!$login) $login=$_SESSION['user'];
		if (!$password) $password=$_SESSION['password'];
		switch ($data) {
			case 'auth_data':
				$query = "SELECT * FROM users WHERE nick='".$login."' AND password='".$password."' AND status=1";
				break;			
			case 'user_data':
				$query = "SELECT * FROM users WHERE nick='".$login."' AND password='".$password."' AND status=1";
				break;
			case 'payments_data':
				$user_id = $this->user_data('id');
				$query = "SELECT * FROM payments WHERE uid=$user_id";
				break;
			case 'cams_data':
				$query = "SELECT cam_ip, port, login, password FROM cams WHERE uid IN (SELECT id FROM users WHERE nick='".$login."' AND password='".$password."' AND status=1)";
				break;
		}
		$result = @$this->mysqli->query($query);
		if($result->num_rows!=0){
			return $result;
		} else {
			return false;
		}
	}
	
	function auth_query($login,$password) {
		$login = @$this->mysqli->real_escape_string($login);
		$password = @$this->mysqli->real_escape_string($password);
		$result = !self::is_static() ? $this->get_db_data('auth_data',$login,$password) : self::get_db_data('auth_data',$login,$password);
		if ($result->num_rows===1) {
			$auth_data = @$result->fetch_array(MYSQLI_ASSOC);
		} else {
			return false;
		}
		return $auth_data;
	}
	
	function user_data($field=false) {
		$result = !self::is_static() ? $this->get_db_data('user_data') : self::get_db_data('user_data');
		if($result) {
			$user_data = @$result->fetch_array(MYSQLI_ASSOC);
		} else {
			return false;
		}
		return $field ? $user_data[$field] : $user_data;
	}
	
	function payments_data($field=false) {
		$result = !self::is_static() ? $this->get_db_data('payments_data') : self::get_db_data('payments_data');
		if (!$result) {return false;}
		$payments_data = array();
		while($row = @mysqli_fetch_assoc($result)){
			$payments_data[]=$row;
		};
		return $field ? $payments_data[$field] : $payments_data;
	}
	
	function cams_data($field=false) {
		$result = !self::is_static() ? $this->get_db_data('cams_data') : self::get_db_data('cams_data');
		if (!$result) {return false;}
		$cams_data = array();
		while($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$cams_data[]=$row;
		};	
		return $field ? $cams_data[$field] : $cams_data;
	}
}
?>