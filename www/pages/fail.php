<?
defined('sCore') or die('access denied');
$inv_id = $_REQUEST["InvId"];
echo '
<div class="succes_block">
	<div class="denied_img"></div>
	<h2>Платеж не удался</h2>
	<div><p>Вы отказались от оплаты. Заказ# '.$inv_id.'</p></div>
</div>
';
?>


