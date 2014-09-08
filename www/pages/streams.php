<?php defined('sCore') or die('access denied'); ?>
<ul class="section_selector">
	<li><a class="active" href="/streams">Камеры</a></li>
	<li><a href="/archive">Архив</a></li>
</ul>
<div id="video-streams">
	<ul>
	</ul>
</div>

<?php
$cams_data = db_controller::cams_data();
if($cams_data){
	echo '<script>var hosts = [';
	foreach($cams_data as $data){
		echo "'".$data[cam_ip]."',";
	}
	echo '];</script>';
} else {
	echo 'У вас нет доступных камер';
}
?>

<script type="text/javascript" src="js/vPlayer.js"></script>