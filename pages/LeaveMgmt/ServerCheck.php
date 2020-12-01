<?php
	$hostname='172.18.0.11:3308';
	//$hostname='172.18.2.211:3308';
	$user = 'hrms';
	$password = 'Acurus123';
	$mysql_database = 'acurus_attendance_portal';
	$attcheck = mysqli_connect($hostname, $user, $password,$mysql_database);
?>