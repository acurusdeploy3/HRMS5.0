<?php
	$hostname='172.18.0.10:3306';
	//$hostname='172.18.2.211:3308';
	$user = 'root';
	$password = 'Capella@20!4';
	$mysql_database = 'ahrms';
	$db = mysql_connect($hostname, $user, $password,$mysql_database);
	mysql_select_db("ahrms", $db);	
	
?>