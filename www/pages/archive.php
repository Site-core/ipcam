<?php
defined('sCore') or die('access denied');
$file_name = $archive_host.'/archive.php?page=archive';
if(isset($_GET['url']))
	$file_name = $file_name.'&url='.$_GET['url'];
// Для PHP 5 и выше
$handle = @fopen($file_name, "rb") or print('Сервер недоступен');
if ($handle){
$contents = stream_get_contents($handle);
fclose($handle);
echo $contents;}
?>