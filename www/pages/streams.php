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
$q3 = @mysql_query("SELECT cam_ip, port, login, password FROM cams WHERE uid IN (SELECT id FROM users WHERE nick='".$_SESSION['user']."' AND password='".$_SESSION['password']."' AND status=1)");

if(mysql_num_rows($q3)!=0){
echo '<script>var hosts = [';
	while($data = mysql_fetch_array($q3)){
		echo "'".$data[cam_ip]."',";
	}
echo '];</script>';
} else {echo 'У вас нет доступных камер';}

?>

<script type="text/javascript" src="js/vPlayer.js"></script>