<?php
class archive_controller {

	var $host;
	var $in_dir = array();
	var $dirs = array();
	
	function __construct() {
		$this->host = "http://archive";
		$this->dirs = $this->curl_request();
		$this->in_dir = array_keys($this->parse_path());
	}
	
	function curl_request() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->host);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// указываем, что у нас POST запрос
		curl_setopt($ch, CURLOPT_POST, 1);
		// добавляем переменные
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);	
		curl_close($ch);
		# DIRs/subDIRs array
		return json_decode(trim($output), TRUE);
	}
	
	function parse_path() {
		$dirs_array = $this->dirs;
		# Преобразовать путь в массив
		if ($_GET['url']) {
			$path = preg_split('/[\\/]+/', $_GET['url'], -1, PREG_SPLIT_NO_EMPTY);
			
			
			foreach($path as $piece) {
				if(array_key_exists($piece, $dirs_array)){
					$dirs_array = $dirs_array[$piece];
				} else {
					unset($dirs_array);
					$dirs_array = 'NOT FOUND';
					break;
				}
			}
		}
			return $dirs_array;
			
	}
}
?>