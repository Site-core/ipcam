<?php
class calendar extends archive_controller {
	
	var $day;
	var $month;
	var $dates = array();
	var $year;
	var $calendars = array();
	
	var $days_in_month;
	
	function __construct() {
		parent::__construct();
		# Timezone and Locale
		$timezone = 'Asia/Yekaterinburg';
		date_default_timezone_set($timezone);
		setlocale(LC_ALL, 'ru_RU.UTF-8', 'Russian_Russia.1251');
		# Set date
		$this->day = date('j');
//		$this->month = 11; //date('n')
//		$this->year = date('Y');
		# Date info
		
		$this->generate_table();
	}
	
	function get_date($format) {
		$day = 1;
		switch($format) {
			case 'current_day':
				return date('j');
				break;
			case 'days_in_month':
				return date("t",  mktime(0, 0, 0, $this->month, $day, $this->year));
				break;
			case 'first_day_in_month':
				return date("N", mktime(0, 0, 0, $this->month, $day, $this->year));
				break;
			case 'last_day_in_month':
				return date("N", mktime(0, 0, 0, $this->month, $this->days_in_month, $this->year));
				break;
			case 'month_year':
				$date = strftime("%B %Y", mktime(0, 0, 0, $this->month, $day, $this->year));				
				$WIN_OS = array('WINNT', 'WIN32', 'Windows');				
				$UNIX_OS = array('Linux', 'Unix', 'OpenBSD', 'FreeBSD', 'NetBSD');				
				if (PHP_OS==$UNIX_OS)
					return $date;
				elseif (in_array(PHP_OS, $WIN_OS))
					return iconv('Windows-1251','utf-8',$date);
				break;				
		}
	}
	
	function set_dates() {
		$pattern = '/^([0-9]{4}-[0-9]{2})-[0-9]{2}$/';
		$replacement = '$1';
		$this->dates = array_values(
			array_unique(
				preg_replace($pattern, $replacement, $this->in_root_dir)
			)
		);
	//	return $this->dates;
	}
	
	function generate_table() {
		$this->set_dates();
		$calendar = array();
		foreach($this->dates as $num => $date){
			$split_dates = preg_split('/-/', $this->dates[$num], -1); // разделить ГГ-ММ в массив
			$this->year = $split_dates[0];
			$this->month = $split_dates[1];
			$this->days_in_month = $this->get_date('days_in_month');
			//echo $this->dates[$num];
			$pattern = '/^'.$this->dates[$num].'-0?(\d{1,2})$/';
			$replacement = '$1';
			$marked_dates = preg_filter($pattern, $replacement, $this->in_root_dir);
		//	echo '<pre>';
		//	print_r($this->in_root_dir);
		//	print_r($marked_dates);
		//	echo '</pre>';
			$weekdays = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
			$first_day = $this->get_date('first_day_in_month');
			$last_day = $this->get_date('last_day_in_month');
			$calendars[$num]='<div id="calendar'.$num.'" class="calendar">
				<div class="month">
					
					<div class="month_name"><h2>'. $this->get_date('month_year') .'</h2></div>
					
				</div>
				<div class="table_wrapper">';
			$calendars[$num] .= '<table><tr class="days">';
			foreach ($weekdays as $weekday) {
				$calendars[$num] .= '<th>'.$weekday.'</th>';
			}
			$calendars[$num] .= '</tr><tr class="dates">';
			if ($first_day != 1)
				$calendars[$num] .= '<td class="blank_top" colspan="'.($first_day-1).'"></td>';
				
			for ($i = 1; $i <= $this->days_in_month; $i++) {
				$weekday = date("N", mktime(0, 0, 0, $this->month, $i, $this->year));
				if ( ($key = array_search($i, $marked_dates)) !== false) {
					//echo $this->in_root_dir[$key];
					$calendars[$num] .= "<td class='recorded'><div class='date_in'></div><div><a>".$i."</a></div></td>";
				} else {
					$calendars[$num] .= "<td><div><a href='?url=".$this->in_root_dir[$key]."'>$i</a></div></td>";
				}
			//	$class = (in_array($i, $marked_dates) ? "style='color:red'" : ""); // Есть ли соответствующая папка для даты?
				
			//	$calendars[$num] .= "<td><a $class href=''>".$i."</a></td>";
				if ($weekday % 7 === 0 && $i != $this->days_in_month)
					$calendars[$num] .= '</tr><tr class="dates">';
			}
			
			if ($last_day != 7)
				$calendars[$num] .= '<td class="blank_bottom" colspan="'.(7-$last_day).'"></td>';
			$calendars[$num] .= '	
					</tr>
				</table>
					</div></div>';
		}
		$this->calendars = $calendars;
	}
	
	function show($num=''){
		$this->calendars;
		foreach($this->calendars as $calendar) {
			echo $calendar;
		}
	}
}
?>