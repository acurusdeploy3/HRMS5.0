<?php 
if(isset($_REQUEST))
{
	session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
 include("config.php");
 include("FYITransaction.php");
 $Emplid = $_SESSION['EmpId'];
error_reporting(E_ALL && ~E_NOTICE);

$ClientName=mysql_real_escape_string($_POST['ClientNameText']);
$ClientAbb=mysql_real_escape_string($_POST['ClientAbb']);


mysql_query("insert into all_clients (client_name,client_abb) values ('$ClientName','$ClientAbb')");
}
?>