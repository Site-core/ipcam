<?php
defined('sCore') or die('access denied');
class template {
# SC_engine v1.2
	private $files = array();
	private $vars = array();
	private $pvt_pgs = array();
	VAR $template;
	VAR $homepage = 'home';
	
	function set_tpl($tpl_name){
		$this->template = $this->get_file($tpl_name);
	}

	function set_block($key,$var,$wrapper=false,$pages=''){
	do {
		if ($pages) {
			$page = isset($_GET['page']) ? $_GET['page'] : $this->homepage;
			if(!in_array($page, array_map('trim',explode(",", $pages)))){
				if(!array_key_exists($key, $this->vars))
					$this->vars[$key] = '';
				break;
			}
		}
		if($wrapper){
			$BEGIN = '<div class="'.$wrapper.'">';
			$END = '</div>';
			$var = $BEGIN.$var.$END;
		}
		$this->vars[$key] = $var;
	} while (0);
		
	}

	function get_file($file_name,$parse=false) {
		$fh = @fopen($file_name, "r") or die("Couldn't open the file!");
		$file_contents = fread($fh, filesize($file_name));
		fclose($fh);
/* 		if($parse)
			$file_contents = $this->parse_file($file_contents); */
		return $file_contents;
	}
	
	function pvt_pgs($pages) {
		GLOBAL $session_controller;
		if (!$session_controller->authorized) {
			$this->pvt_pgs = array_map('trim',explode(",",$pages));
		}
	}

// Обработчик страниц
	function set_content () {
		$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_REGEXP,
			array("options"=>array("regexp"=>"/^[a-z0-9_]+$/")));
		
		if ($page) {
			if (is_array($this->pvt_pgs) && in_array($_GET['page'], $this->pvt_pgs)){
				$content=PGS_DIR."access_denied.html";
			} else {
				$content = PGS_DIR.'/'.$page.'.html';
				if (!file_exists($content)){
					$content = PGS_DIR.$_GET['page'].".php";
					if (!file_exists($content)) {
						http_response_code(404);
						$content = '404.html';
					}
				}
			}
		} elseif (is_null($page)) {
			$content = PGS_DIR.'/'.$this->homepage.'.html';
		} else {
			http_response_code(404);
			$content = '404.html';
		}

		$content = $this->get_file($content);
		$content = $this->parse_file($content);
		$this->set_block('CONTENT', $content);
	}
	function parse_file ($file){
		foreach($this->vars as $find => $replace){
			$file = str_replace("{".$find."}", $replace, $file);
		}
		return $file;
	}
	function tpl_parse(){
		$this->template = $this->parse_file($this->template);
	}
}
?>