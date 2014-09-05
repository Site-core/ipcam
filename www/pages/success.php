<?php
// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = "BMOJqidQb3";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$shp_uID = $_REQUEST["Shp_uID"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:Shp_uID=$shp_uID"));

// проверка корректности подписи
// check signature
if ($my_crc != $crc)
{
  echo '
	<div class="succes_block">
	<div class="denied_img"></div>
	<h2>Платеж не удался</h2>
	<div><p>Не корректная подпись платежа. Заказ# '.$inv_id.'</p></div>
	</div>
	';
  exit();
}

// проверка наличия номера счета в истории операций
// check of number of the order info in history of operations
$f=@fopen("robox/order.txt","r+") or die("error");

while(!feof($f))
{
  $str=fgets($f);

  $str_exp = explode(";", $str);
  if ($str_exp[0]=="order_num :$inv_id")
  {
	echo '
	<div class="succes_block">
	<div class="succes_img"></div>
	<h2>Ваш платеж успешно выполнен</h2>
	<p>Баланс пополнен на сумму <b>'.$out_summ.' рублей</b>. Заказ# '.$inv_id.'</p>
	<p>Благодарим вас, за использование нашего сервиса</p>
	</div>
	';
  }
}
fclose($f);
?>


