<?php
class get_DBrecords {
	
	var $config;
	
	function get_DBrecords() {
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
	
	$connect = mysql_connect($host, $usr, $pwd) or die("Не могу подключиться" );
	mysql_select_db($db, $connect) or die ('Не могу выбрать БД');
	}
	
	function user_data($field) {
		$ud_query = @mysql_query("SELECT * FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."' AND status=1");
		if(@mysql_num_rows($ud_query)==1){
			$this->authorized=true;
			$this->user_data = @mysql_fetch_array($ud_query);
			$this->user_id = $this->user_data['id'];
			$this->user_nick=$this->user_data['nick'];
			$this->user_fund=$this->user_data['fund'];
			return $this->$field;
		}
	}
	
	function payments_records() {
		echo $user_id;
	}
	
	function cams_data() {
		echo $user_id;
	}

	
}

//$link = mysql_connect($DBSERVER, $DBUSER, $DBPASS)
//or die("Не могу подключиться" );
//mysql_select_db($DB, $link) or die ('Не могу выбрать БД');
?>