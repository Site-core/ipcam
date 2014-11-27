<?php
defined('sCore') or die('access denied');
function checkmail($mail) {
   // режем левые символы и крайние пробелы
   $mail=trim($mail); // функцию pregtrim() возьмите выше в примере
   // если пусто - выход
   if (strlen($mail)==0) return -1;
   if (!preg_match("/^[a-z0-9_-]{1,20}+(\.){0,2}+([a-z0-9_-]){0,5}@(([a-z0-9-]+\.)+(com|net|org|mil|".
   "edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
   "9]{1,3}\.[0-9]{1,3})$/is",$mail))
   return -1;
   return $mail;
}

function sendmail($mail,$subject,$message,$headers) {

if(mail($mail,$subject,$message,$headers)) { return TRUE;}
else {return FALSE;}

}

//=== ARCHIVE ===//

//Листинг папок
function listing ($url,$mode) {
//Проверяем, является ли директорией
if (is_dir($url)) {
//Проверяем, была ли открыта директория
if ($dir = opendir($url)) {
//Сканируем директорию
while (false !== ($file = readdir($dir))) {
//Убираем лишние элементы
if ($file != "." && $file != "..") {

//Если папка, то записываем в массив $folders
if(is_dir($url."/".$file)) {
$folders[] = $file;
}
//Если файл, то пишем в массив $files
else {$files[] = $file;}
}
}
}
//Закрываем директорию
closedir($dir);
} else {echo 'не найдено';}
//Если режим =1 то возвращаем массив с папками
if($mode == 1) {return $folders;}
//Если режим =0 то возвращаем массив с файлами
if($mode == 0) {return $files;}
}

//Функция создания папки
function makedir ($url){
//Вырезаем пробелы и хтмл-тэги
$url = trim(htmlspecialchars($url));
//Если папка создается возвращаем TRUE
if(@mkdir($url)){return TRUE;}
else{return FALSE;} }

//Функция переименования
function frename ($url,$oldname,$nname){
$nname = trim(htmlspecialchars($nname));
$oldname = trim(htmlspecialchars($oldname));
$url = trim(htmlspecialchars($url));
if(@rename($url."/".$oldname,$url."/".$nname))

{return TRUE; }
else {return FALSE; } }

function removedir ($directory) {
$dir = opendir($directory);
while(($file = readdir($dir)))
{
if ( is_file ($directory."/".$file))
{
unlink ($directory."/".$file);
}
else if ( is_dir ($directory."/".$file) &&
($file != ".") && ($file != ".."))
{
removedir ($directory."/".$file);
}
}
closedir ($dir);
rmdir ($directory);
return TRUE;  }

function removefile ($path) {
if(unlink($path)) { return TRUE; }
else {    return FALSE; } }

function updir($path){
	$last = strrchr( $path, "/" );
	$n1 = strlen( $last );
	$n2 = strlen( $path );
	if ($n2 != 0){
		$updir = substr( $path, 0, $n2-$n1 );
	} else {
		$updir = false;
	}
	return $updir;
}

//Получаем размер файла
function fsize($path) {
return substr(filesize($path)/1024, 0, 4);
}

?>