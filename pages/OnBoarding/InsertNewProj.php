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

$AddedProj=mysql_real_escape_string($_POST['ProjSelect']);
$allPer=mysql_real_escape_string($_POST['AllocatedPer']);
$allfrom=mysql_real_escape_string($_POST['dateFrom']);
$allto=mysql_real_escape_string($_POST['dateUpto']);
$sql="insert into employee_projects (res_management_id,project_id,employee_id,created_Date_and_time,created_by,is_Active,allocated_percentage,allocated_from,allocated_to)

values

('$resId','$AddedProj','$Emplid',now(),'$name','Y','$allPer','$allfrom','$allto')";
$result=mysql_query($sql);
header("Location: ViewResource.php?id=$Emplid");
}
?>