<?php
defined('sCore') or die('access denied');
// Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
// Payment of the set sum with a choice of currency on site ROBOKASSA

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = "cam-net_ru";
$mrh_pass1 = "BMOJqidQb3";

// номер заказа
// number of order
$count_payments = @mysql_query("SELECT COUNT( * ) FROM payments");
$count_payments = @mysql_result ($count_payments,0);
if ($count_payments!=0){
$inv_id = @mysql_query("SELECT payment_num FROM payments ORDER BY payment_num DESC LIMIT 1;");
$inv_id = @mysql_result ($inv_id,0);
} else {
$inv_id = $count_payments;
}
$inv_id = ++$inv_id;

// описание заказа
// order description
$inv_desc = "Пополнение баланса на cam-net.ru";

// сумма заказа
// sum of order
//$out_summ = "15
$def_sum = "25";

// тип товара
// code of goods
$shp_uID = @mysql_query("SELECT id FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."'");
$shp_uID = @mysql_fetch_array($shp_uID);
$shp_uID = $shp_uID[id];
$shp_item = "top-up";

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = "ru";

// кодировка
// encoding
$encoding = "utf-8";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:Shp_uID=$shp_uID");

// форма оплаты товара
// payment form
$payment_form = "<script language=JavaScript ".
      "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
      "MerchantLogin=$mrh_login&DefaultSum=$def_sum&InvoiceID=$inv_id".
      "&Description=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item&Shp_uID=$shp_uID".
      "&Culture=$culture&Encoding=$encoding'></script>";
?>