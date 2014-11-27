<?php
class class_switcher {	
	function __construct() {
		switch ($_GET['page']) {
			case 'calendar':
				require_once ("includes/archive_controller.inc.php");
				require_once ("includes/calendar.inc.php");
				break;			
			case 'archive':
				require_once ("includes/archive_controller.inc.php");
				break;
		}
	}
	
	// function get_date($format) {
		
	// }
}
?>