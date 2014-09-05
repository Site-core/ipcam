<?php
//defined('sCore') or die('access denied');

include("config.inc.php");
//Подключаем файл с функциями
include("functions.inc.php");
$url=ARCHVS_DIR;
$page = '?page='.$_GET['page'];

//Указываем рабочую папку по умолчанию
if(isset($_GET['url'])) $url = $_GET['url'];
//Переименование папок и файлов
if($_GET['rename'])
{
if(!$_POST['rename']) {
echo '

<form action="'.$page.'&rename=1" method="post">';
echo 'Новое имя:
';
echo '
<input name="nname" type="text" value="'.$_GET['fname'].'" />';
echo '
<input name="url" type="hidden" value="'.$_GET['url'].'" />
';
echo '
<input name="oldname" type="hidden" value="'.$_GET['fname'].'" />';
echo '
<input name="rename" type="submit" value="ok" /></form>'; }
else {
if(frename($_POST['url'],$_POST['oldname'],$_POST['nname'])== FALSE) {
echo 'Ошибка<br />';}
}
}

//Шапка
echo $_SERVER['REQUEST_URI'].'&url='.updir($url)."<br />";
echo $page.'<br />';
echo 'Текущая директория:  '.$url.'
<br />
';

echo '<a href="'.$page.'">[корень]</a> ';
echo '<a href="'.$page.'&url='.updir($url).'">[вверх]</a> ';
echo '<a href="'.$page.'&mkdir=1&url='.$url.'">[создать папку]</a>
<br />
';

//Листинг папок
if(listing($url,1)) {
foreach(listing($url,1) as $f) {
echo '<a href="'.$page.'&rename=1&url='.$url.'&fname='.$f.'">[переименовать]</a>';
echo '<a href="'.$page.'&rmdir=1&url='.$url.'&fname='.$f.'">[удалить]</a> ';
echo '<img src="/img/dir.png" alt="" /><a href="'.$page.'&url='.$url.'/'.$f.'">'.$f.'</a><br />';
}}
//Листинг файлов
if(listing($url,0)) {
foreach(listing($url,0) as $f) {
echo '<a href="'.$page.'?rename=1&url='.$url.'&fname='.$f.'">[переименовать]</a>';
echo '<a href="'.$page.'?rmfile=1&url='.$url.'&fname='.$f.'">[удалить]</a> ';
echo '<img src="/img/'.strtolower(substr(strrchr($f, '.'),1)).'.png" alt="" /><a href="'.$url.'/'.$f.'">'.$f.'</a> - '.fsize($url.'/'.$f).'<br />';
}}


?>