<?php
session_start();

include ("../includes/config.inc.php");
$link = mysql_connect($DBSERVER, $DBUSER, $DBPASS)
or die("Не могу подключиться" );
mysql_select_db($DB, $link) or die ('Не могу выбрать БД');

// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = "BMOJgidQb3";

//установка текущего времени
//current date
$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$shp_uID = $_REQUEST["Shp_uID"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item:Shp_uID=$shp_uID"));

// проверка корректности подписи
// check signature
if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}

// признак успешно проведенной операции
// success
echo "OK$inv_id\n";
// запись в файл информации о проведенной операции
// save order info to file
$top_up=@mysql_query("UPDATE users SET fund=fund+$out_summ WHERE id=$shp_uID");
$register_payment=@mysql_query("INSERT INTO payments VALUES($shp_uID,$inv_id,$out_summ,'$date')");
if($top_up && $register_payment) {echo 'Платеж Удался!';} else {echo 'Платеж НЕ удался';}

$f=@fopen("order.txt","a+") or
          die("error");
fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
fclose($f);

?>


