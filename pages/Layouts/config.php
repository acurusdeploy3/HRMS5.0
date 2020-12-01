<?php
$hostname='172.18.0.11:3308';
	$user = 'appuser';
	$password =  '@cuRus';
	$mysql_database = 'ahrms';
	$db = mysql_connect($hostname, $user, $password,$mysql_database);
	mysql_select_db("ahrms", $db);	
	
?>