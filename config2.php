<?php
	$hostname='172.18.0.11:3308';
	//$hostname='172.18.2.211:3308';
	$user = 'appuser';
	$password =  'Cap@curus3';
	$mysql_database = 'resume_tracker';
	$db = mysql_connect($hostname, $user, $password,$mysql_database);
	mysql_select_db("resume_tracker", $db);
?>