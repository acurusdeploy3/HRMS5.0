<?php
	$hostname='172.18.0.11:3308';
	//$hostname='172.18.2.211:3308';
	$user = 'appuser';
	$password =  'Cap@curus3';
	$mysql_database = 'resume_tracker';
	$db1 = mysqli_connect($hostname, $user, $password,$mysql_database);
	mysqli_select_db("resume_tracker", $db1);
?>