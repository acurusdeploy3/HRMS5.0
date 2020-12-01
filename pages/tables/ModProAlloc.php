<?php 
if(isset($_REQUEST))
{
	session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$resId = $_SESSION['rId'];
 include("config.php");
 $Emplid = $_SESSION['EmpId'];
error_reporting(E_ALL && ~E_NOTICE);
$ModPer=mysql_real_escape_string($_POST['ModifiedAllocatedPer']);
$rID=mysql_real_escape_string($_POST['rowId']);
$datefrom=$_POST['DateFromEdit'];
$dateto=$_POST['DateToEdit'];
$sql="update employee_projects set allocated_percentage='$ModPer',allocated_from='$datefrom',allocated_to='$dateto',modified_date_and_time=now(),modified_by='$name' where id='$rID'";
$result=mysql_query($sql);

}
?>