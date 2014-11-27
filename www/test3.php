<?
require("/includes/archives_controller.php");

/* $file_name = 'http://archive/';
// Для PHP 5 и выше
$handle = fopen($file_name, "r");
$contents = stream_get_contents($handle);
fclose($handle);
echo $contents; */


$dir = new dir_controller('archives');
echo '<pre>';
print_r($dir->dir_ls('tree'));
echo '</pre>';
/* foreach($dir->dir_ls('array') as $f) {
	$url = $dir->current.'/'.$f;
		
	if(is_dir($url)) $type = 'dir';
	if(is_file($url)) $type = 'file';
	
	echo '<a href="?url='.$url.'" title="Записи от '.$f.'"><img width="50" height="32" src="/img/folder_icon.svg" alt="'.$f.'" />
	'.$f.'</a> - '.$type.'<br />';
} */

?>

