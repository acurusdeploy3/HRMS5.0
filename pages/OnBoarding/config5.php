<?php
	$hostname='172.18.1.120:3308';
	//$hostname='172.18.2.211:3308';
	$user = 'root';
	$password = 'acurus';
	$mysql_database = 'report';
	$db1 = mysqli_connect($hostname, $user, $password,$mysql_database);
?>