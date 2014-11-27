<?php
defined('sCore') or die('access denied');

//Подключаем файл с функциями
include("includes/functions.inc.php");
$root_dir = ARCHVS_DIR.'/uid_{user_id}';
//$root_dir = ARCHVS_DIR;
$path = $root_dir;

if(isset($_GET['page']))$page = $_GET['page'];

//Указываем рабочую папку по умолчанию
if(isset($_GET['url'])) {
	$url = $_GET['url'];
	$path = $root_dir.$url;
}

//Селектор
echo '
<ul class="section_selector">
	<li><a href="/streams">Камеры</a></li>
	<li><a class="active" href="/archive">Архив</a></li>
</ul>

<a href="/archive"><img src="/img/home.gif"></a> ';

if ( $url || updir($url) ) {
	echo '<a href="/'.$page.updir($url).'"><img src="/img/upone.gif"></a>';
} else {
	echo '<img src="/img/upone_grey.gif">';
};
echo '<span style="color:grey;font-size:1.2em;margin-left:15px;">Текущая директория: '.($url ? $url : '/').'</span>
<hr color="#ccc" size="1" noshade/>
';
echo '<br />';
echo '<div style="font-size:1.5em">';
//Листинг папок
if(listing($path,1)) {
	foreach(listing($path,1) as $f) {
	//echo '<a href="'.$page.'&rename=1&url='.$url.'&fname='.$f.'">[переименовать]</a>';
	//echo '<a href="'.$page.'&rmdir=1&url='.$url.'&fname='.$f.'">[удалить]</a> ';
	echo '<a href="/'.$page.$url.'/'.$f.'" title="Записи от '.$f.'"><img width="50" height="32" src="/img/folder_icon.svg" alt="'.$f.'" />
	'.$f.'</a><br />';
	}
}
//Листинг файлов
if(listing($path,0)) {
//Player begin
echo "
<div id='hPlayer'></div>
<script type='text/javascript'>
        hPlayer.init({
            wrapper: 'hPlayer',
            autoplay: true,
            loop: false,
            windowless: true,
            mode: 'http',
			stream: [
";
	foreach(listing($path,0) as $f) {
		$f_ext = strtolower(substr(strrchr($f, '.'),1));
		if($f_ext=='ts'){
			echo "'".$path."/".$f."',";
		}
	}
echo "		]
});
</script>";
//Player end
}
echo '</div>';
?>