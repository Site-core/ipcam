<?php
class calendar extends archive_controller {
	
	var $day;
	var $month;
	var $year;
	
	var $days_in_month;
	var $first_day;
	var $last_day;
	
	function __construct($date) {
		parent::__construct();
		# Timezone and Locale
		$timezone = 'Asia/Yekaterinburg';
		date_default_timezone_set($timezone);
		setlocale(LC_ALL, 'ru_RU.UTF-8', 'Russian_Russia.1251');
		# Set date
		$this->day = date('j');
		$this->month = 12; //date('n')
		$this->year = date('Y');
		# Date info
		$this->days_in_month = $this->get_date('days_in_month');
		$this->first_day = $this->get_date('first_day_in_month');
		$this->last_day = $this->get_date('last_day_in_month');
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
	
	function generate_table() {
		$weekdays = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
		$calendar = '<table><tr class="days">';
		foreach($weekdays as $weekday) {
			$calendar .= '<th>'.$weekday.'</th>';
		}
		$calendar .= '</tr><tr class="dates">';
		if ($this->first_day != 1)
			$calendar .= '<td class="blank_top" colspan="'.($this->first_day-1).'"></td>';
			
		for ($i = 1; $i <= $this->days_in_month; $i++) {
			$weekday = date("N", mktime(0, 0, 0, $this->month, $i, $this->year));
			$calendar .= '<td><a href="">'.$i.'</a></td>';
			if ($weekday % 7 === 0 && $i != $this->days_in_month)
				$calendar .= '</tr><tr class="dates">';
		}
		
		if ($last_day != 7)
			$calendar .= '<td class="blank_bottom" colspan="'.(7-$last_day).'"></td>';
		$calendar .= '	
				</tr>
			</table>';
		return $calendar;
	}
}
?>