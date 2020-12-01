<?php
	$odaurl ='jdbc:mysql://172.18.0.11:3308/acurus_attendance_portal';
	$odauser='hrms';
	$odapwd ='Acurus123';
	
session_start();  
$empId=$_SESSION['login_user'];
$userid=$_SESSION['login_user'];
date_default_timezone_set('Asia/Kolkata');	
?>
