<?php
define('sCore', true);
//Поключаем конфиг
require("includes/config.inc.php");

require("includes/db_controller.inc.php");
require("includes/session_controller.inc.php");
$db_controller = new db_controller;
$session_controller = new session_controller();
// Включить класс шаблона
require ("templates/init.tpl.php");

// Присвоить значения переменным
$user_info = $db_controller->user_data();
$user_id = $user_info['id'];
$user_nick = $user_info['nick'];
$user_fund = $user_info['fund'];
$page_title = "cam-net.ru";
$copyright = "cam-net.ru &copy; 2014";

// Создать новый экземпляр класса
$template = new template;

$tpl = "cam_net_ru"; // название экземпляра

$template->set_tpl($tpl);

// Регистрация файлов
$template->sec_pgs("streams,archive,cabinet,finance,cams_settings,events");
$template->set_content();

$template->register_file($tpl, "templates/index.tpl.html");

if (!$session_controller->authorized) {
	$template->register_file('login_form', "parts/login_form.html");
	$login_form = $template->get_file('login_form');
	$login_form = str_replace("\r\n", '', $login_form);
	$login_button = "<ul><li><a
	onclick=\"msgBox({content:'$login_form',class:'msgBox',height:300},event);\"
	href='javascript:void(0)'>Авторизация</a></li></ul>";
} else {
	$login_button = '<ul><li><a href="/cabinet">Личный кабинет</a></li><li class="exit"><a href="?exit=1"></a></li></ul>';
}

$template->set_menu('menu_top', 'parts/menu_top.html');
$template->register_variables('menu_top', "login_button");
$template->file_parser('menu_top');
$menu_top = $template->get_file('menu_top');

if (!isset($_GET['page'])){
	$template->set_menu('menu_right', 'parts/menu_right.html');
	$slider_wrapper_BEGIN = "<div class='slider-wrapper clear-fix'>";
	$slider_wrapper_END = "</div>";
	$rMenu_wrapper_BEGIN = "<div class='right-menu'>";
	$rMenu_wrapper_END = "</div>";
	$template->file_parser('menu_right');
	$menu_right = $template->get_file('menu_right');
}
elseif ($session_controller->authorized) {
	switch ($_GET['page']) {
	case 'finance':
		$prTable_BEGIN = '<table class="payments_records"><tbody><tr><th>№ заказа</th><th>Сумма</th><th>Дата платежа</th></tr>';
		$prTable_END = '</tbody></table>';
		$payments_records = $db_controller->payments_data();
		if($payments_records){
			foreach($payments_records as $data){
				$payments_report =$payments_report.'<tr>
				<td>'.$data[payment_num].'</td>
				<td>'.$data[sum].'</td>
				<td>'.$data[payment_date].'</td>
				</tr>';
			}
			$payments_report = $prTable_BEGIN.$payments_report.$prTable_END;
		} else {
			$payments_report =  'У вас нет записей';
		}
	case 'cabinet':
	case 'cams_settings':
	case 'events':
	case 'success':
	case 'fail':
	include("includes/payment.php");
	$template->set_menu('menu_right_cabinet', 'parts/menu_right_cabinet.html');
	$template->register_variables('menu_right_cabinet', "payment_form");
	$template->file_parser('menu_right_cabinet');
	$menu_right_cabinet = $template->get_file('menu_right_cabinet');
	
    break;
	}
}

// Регистрация блоков
$template->register_variables($tpl, "slider,login_form,menu_top,rMenu_wrapper_BEGIN,rMenu_wrapper_END,menu_right,slider_wrapper_BEGIN,slider_wrapper_END,menu_right_cabinet");
// Регистрация переменных
$template->register_variables($tpl, "page_title,user_nick,user_fund,copyright,user_id,payments_report,payment_form");

$template->file_parser($tpl);
// Вывод готовой страницы
$template->eval_file($tpl);
?>