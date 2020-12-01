<?php
	$hostname='172.18.0.143:3306';
	//$hostname='172.18.2.211:3308';
	$user = 'dbuser';
	$password = 'acurus';
	$mysql_database = 'ahrms';
	$att = mysqli_connect($hostname, $user, $password,$mysql_database);
?>