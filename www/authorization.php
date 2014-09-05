<?php
defined('sCore') or die;
session_start();
//Необходимо подключиться к БД
$link = @mysql_connect($DBSERVER, $DBUSER, $DBPASS)
or die("Не могу подключиться к БД" );
// сделать $DB текущей базой данных
@mysql_select_db($DB, $link) or die ('Не могу выбрать БД');

//Если нет сессий
if(md5(crypt($_SESSION['user'],$_SESSION['password'])) != $_SESSION['SID']) {
 //Если кнопка не нажата, отображаем форму
if(!$_POST['do']){

$authorized = false;

}
//Если кнопка нажата
if($_POST['do']) {
//Проверяем данные
$login = $_POST['login'];
$upass = $_POST['password'];
if($login !='' AND $upass !='') {
//Создаем запрос
$q1=@mysql_query("SELECT * FROM users WHERE nick='".$login."' AND password='".md5($upass)."' AND status=1");

//Проверяем существует ли хоть одна запись
if(mysql_num_rows($q1)===1) {
//Если есть, то создаем сессии и перенаправляем на эту страницу
$r=mysql_fetch_array($q1);
$_SESSION['user'] = $r['nick'];
$_SESSION['password'] = $r['password'];
$_SESSION['SID'] = md5(crypt($r['nick'],$r['password']));

header("Location: /");
}
else {header("Location: ?error=lgn_psw");}
}
else {header("Location: ?error=empty_auth_flds");}
}

}
else {
	if($_GET['exit']) {
		session_destroy();
		unset($_GET['exit']);
		mysql_close($link);
		header("Location: /");
	}
	$q2 = @mysql_query("SELECT * FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."' AND status=1");
	if(@mysql_num_rows($q2)==1){ //<-тут творится что-то неладное -_-
		$r2 = @mysql_fetch_array($q2);  
		$authorized=true;
	}
}
  
?>