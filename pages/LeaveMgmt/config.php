<?php
	$hostname='172.18.0.143:3306';
	
	$user = 'dbuser';
	$password = 'acurus';
	//$password = 'acurus';
	$mysql_database = 'ahrms';
	$db = mysqli_connect($hostname, $user, $password,$mysql_database);

	//File upload path
	$ABS_PATH = "D:/";
	$RELATIVE_PATH = "uploads/";
	$TARGET_DIR = $ABS_PATH.$RELATIVE_PATH;
	date_default_timezone_set('Asia/Kolkata');	
	//File Display Path
	$HOSTED_URL = "http://115.160.252.30:85/AHRMS/";
	$DOCUMENT_PATH = $HOSTED_URL.$RELATIVE_PATH;
	session_start();
	
	$empId = $_SESSION['login_user'];
?>
