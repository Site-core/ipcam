<?php
//defined('sCore') or die('access denied');
class dir_controller {
	private $dir;
	
	function __construct($directory) {
		if(isset($_GET['url']))
			$directory = $_GET['url'];
		$this->dir = $directory;
		$this->current = $this->dir;
	}
	
	function dir_ls($format) {
		if(is_dir($this->dir))
			$files = array_diff(scandir($this->dir), array('..', '.'));
		switch($format){
			case 'array':
				return $files;
				break;
			case 'html':
				foreach($files as $file) {
					$path = $this->dir.'/'.$file;
					$type = filetype($path);
					echo '<a href="?url='.$path.'" title="Записи от '.$file.'"><img width="50" height="32" src="/img/folder_icon.svg" alt="'.$f.'" />'.$file.'</a> - '.$type.'<br />';
				}
				break;
			case 'tree':
				$dirs_tree = $this->dir_tree($this->dir);
				return $dirs_tree;
				break;
		}
//		return $output;
	}
	function dir_tree($dir){
		$dirs = array();
		if(is_dir($dir)) {
			$files = array_diff(scandir($dir), array('..', '.'));
			foreach($files as $file) {
				$path = $dir.'/'.$file;		
				if(is_dir($path))
					$dirs[$file] = $this->dir_tree($path);
				elseif (is_file($path))
					$dirs[] = $file;
			}
		} elseif (is_file($dir))
			$dirs[] = basename($dir);
		return $dirs;
	}
}
?>