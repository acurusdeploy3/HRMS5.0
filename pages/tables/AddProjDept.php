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

$DeptName=mysql_real_escape_string($_POST['DeptNameText']);
$DeptAbb=mysql_real_escape_string($_POST['DeptAbb']);


mysql_query("insert into all_project_departments (department_name,dept_abb) values ('$DeptName','$DeptAbb')");
}
?>