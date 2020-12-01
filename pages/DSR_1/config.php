<?php

	$hostname='172.18.0.143:3306';
	//$hostname='172.18.2.211:3308';
	//$hostname='localhost:3306';
	$user = 'dbuser';
	$password = 'acurus';
	//$password = 'acurus';
	$mysql_database = 'ahrms';
	$db = mysqli_connect($hostname, $user, $password,$mysql_database);
	//mysqli_select_db("ahrms", $db);
	
	//File upload path
	$ABS_PATH = "D://";
	$RELATIVE_PATH = "uploads/";
	$TARGET_DIR = $ABS_PATH.$RELATIVE_PATH;

	//File Display Path
	$HOSTED_URL = "http://localhost/phypmyadmin/HRMS20181205/HRMS2.0/pages/forms/";
	$DOCUMENT_PATH = $HOSTED_URL.$RELATIVE_PATH;
	
session_start();  
$empId=$_SESSION['login_user'];
$userid=$_SESSION['login_user'];
date_default_timezone_set('Asia/Kolkata');	
?>

