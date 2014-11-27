<?php
define ('sCore', true);
//Поключаем конфиг
require ("includes/config.inc.php");

// Подключение классов
require ("includes/db_controller.inc.php");
require ("includes/session_controller.inc.php");
require ("includes/class_switcher.inc.php");
require ("templates/init.tpl.php");

// Создать экземпляры классов
//$db_controller = new db_controller;
$session_controller = new session_controller();
$db_controller = $session_controller;
new class_switcher();
$template = new template;

$template->set_tpl('templates/index.tpl.html'); //Файл который мы будем парсить
$template->pvt_pgs("streams,archive,cabinet,finance,cams_settings,events"); //Закрытые от публичного просмотра страницы
$template->set_content();
// Присвоить значения переменным
$user_info = $db_controller->user_data();
$user_id = $user_info['id'];
$user_nick = $user_info['nick'];
$user_fund = $user_info['fund'];
$page_title = "cam-net.ru";
$copyright = "cam-net.ru &copy; 2014";
$menu_top = $template->get_file("parts/menu_top.html");
$slider = $template->get_file("parts/slider.html");
$menu_right = $template->get_file('parts/menu_right.html');
$slider_block = '<div class="right-menu">'.$menu_right.'</div>'.$slider;

if (!$session_controller->authorized) {
	$login_form = $template->get_file("parts/login_form.html");
	$login_form = str_replace("\n", '', addslashes($login_form));
	$login_button = "<ul><li><a
	onclick=\"msgBox({content:'$login_form',main_class:'msgBox',height:300},event);\"
	href='javascript:void(0)'>Авторизация</a></li></ul>";
} else {
	$login_button = '<ul><li><a href="/cabinet">Личный кабинет</a></li><li class="exit"><a href="?exit=1"></a></li></ul>';
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
		case 'events':
		case 'success':
		case 'fail':
		include("includes/payment.php");
		$template->set_block('PAYMENT_FORM',$payment_form);
		$template->set_block('PAYMENTS_REPORT',$payments_report);
			break;
	}
	$menu_cabinet = $template->get_file('parts/menu_cabinet.html');
}

// Установка блоков
$template->set_block('MENU_TOP',$menu_top);
$template->set_block('login_button',$login_button);
$template->set_block('login_form',$login_form);

$template->set_block('SLIDER_BLOCK',$slider_block,'slider-wrapper clear-fix','home');
$template->set_block('MENU_CABINET',$menu_cabinet,'','finance,cabinet,cams_settings,events,success,fail');
$template->set_block('MENU_MOBILE',$menu_right,'right-menu-mobile','home,about_us,how_it_works,our_systems,payment_methods,contract');

$template->set_block('user_id',$user_id);
$template->set_block('user_nick',$user_nick);
$template->set_block('user_fund',$user_fund);
$template->set_block('page_title',$page_title);
$template->set_block('copyright',$copyright);

//Парсим
$template->tpl_parse();
eval (' ?' . '>' . $template->template . '<' . '?php ');
?>