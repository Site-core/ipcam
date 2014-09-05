<?php
session_start();

@include("config.inc.php");
@include("functions.inc.php");
 //Необходимо подключиться к БД
$link = mysql_connect($DBSERVER, $DBUSER, $DBPASS)
or die("Не могу подключиться" );
// сделать $DB текущей базой данных
mysql_select_db($DB, $link) or die ('Не могу выбрать БД');


if(!$_POST['do'] OR $_POST['do'] =='') {
//Генерируем шестизначный ключ для капчи
if($_SESSION['uid'] =='') { $_SESSION['uid'] = mt_rand(100000,999999); }

//Выводим форму
echo '<html><head><title>Регистрация</title></head><body>';
echo'<form action="" method="POST">';
echo 'Желаемый ник: <input name="nick" type="text" value=""><br/>';
echo 'Пароль: <input name="pass" type="password" value=""><br/>';
echo 'Ещё раз пароль: <input name="rpass" type="password" value=""><br/>';
echo 'Эл.адрес <input name="mail" type="text" value=""><br/>';
echo '<img src="img/capcha.php?sid='.$_SESSION['uid'].'"/> <br/><input name="sid" type="text" value=""><br/><br/>';
echo '<input name="do" type="submit" value="зарегистрировать">';
echo '</form></body></html>';

}
//Если данные отправлены
if($_POST['do'] !='') {
//Начинаем проверять входящие данные
if($_POST['sid'] == $_SESSION['uid']) {

//Создаем запрос к базе для проверки существования Пользователя

$nick = $_POST['nick'];
mysql_query("SELECT * FROM users WHERE nick='".strtolower($nick)."'");

//Проверка результата запроса

if(mysql_affected_rows()==0) {
//Проверка ввведенных паролей

if($_POST['pass'] !='' AND $_POST['rpass'] !='' AND $_POST['pass'] === $_POST['rpass']){
//Проверяем на валидность электронный адрес
if(checkmail($_POST['mail']) !== -1) {

//Осуществляем регистарацию
//Генерируем uniq_id
$uniq_id = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].mktime());
$pass = $_POST['pass'];
$email = $_POST['mail'];
//Создаем запрос для записи данных в БД
$r = @mysql_query("INSERT INTO users VALUES(NULL,'".strtolower($nick)."','".md5($pass)."','".$email."','".$uniq_id."',0,'".date("dmY")."','".date("dmY")."')");

//После запроса отправляем письмо юзеру, для активации аккаунта
if($r) {

// Для отправки e-mail в виде HTML устанавливаем необходимый mime-тип и кодировку
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";

// Откуда пришло
$headers .= 'From:Сайт %sitename%' . "\r\n";

//Здесь укажите электронный адрес, куда будут уходить сообщения
$mailto = $email;
$subject = "Подтверждение регистарции на сайте";
$message = 'Для активации аккаунта пройдите по следующей ссылке <a href="http://АДРЕС_САЙТА/registration.php?

activation='.$uniq_id.'" target="_blank">http://АДРЕС_САЙТА/registration.php?activation='.$uniq_id.'</a>';
$message .= 'или скопируйте ссылку в окно ввода адреса браузера и нажмите enter.';
//Отправляем сообщение
if(sendmail($mailto,$subject,$message,$headers) !== FALSE) {
echo 'Регистрация завершена, на введеный Вами e-mail было отправлено сообщение для активации аккаунта';
}
else {echo 'Регистрация невозможна: Повторите запрос позднее';}
                       }
                       else {echo 'Регистрация невозможна: Повторите запрос позднее';}
                 }
                 else {echo 'Регистрация невозможна: Электронный адрес должен соответствовать шаблону <b>name@domen.com</b><br/><a

href="registration.php"/>назад</a>';}

             }
             else {echo 'Регистрация невозможна: Введенные пароли не совпадают<br/><a href="registration.php"/>назад</a>';}


           }
           else { echo 'Регистрация невозможна: Пользователь с таким именем уже существует<br/><a href="registration.php"/>назад</a>';}


         session_destroy();
        }
        else { echo 'Регистрация невозможна: код подтверждения введен не верно<br/><a href="registration.php"/>назад</a>';}



     }
     //Модуль отвечающий за активацию аккаунта

     if($_GET['activation'] AND $_GET['activation']!='') {

     $uniq_id = $_GET['activation'];
     //Создаем запрос
     $r=@mysql_query("UPDATE users SET status=1 WHERE uniq_id='".$uniq_id."' AND status=0");
     if($r) {echo '<h2>Ваша учетная запись активирована.</h2><br/> Теперь вы можете <a href="index.php">войти на сайт</a> используя данные

указанные при регистрации';}

     else {echo 'Активация невозможна: профиль уже активирован';}

     }
?>