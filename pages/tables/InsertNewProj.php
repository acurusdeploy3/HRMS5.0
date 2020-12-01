<?php 
if(isset($_REQUEST))
{
	session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$resId = $_SESSION['rId'];
 include("config.php");
 include("FYITransaction.php");
 $Emplid = $_SESSION['EmpId'];
error_reporting(E_ALL && ~E_NOTICE);

$AddedProj=mysql_real_escape_string($_POST['ProjSelect']);
$allPer=mysql_real_escape_string($_POST['AllocatedPer']);
$allfrom=mysql_real_escape_string($_POST['dateFrom']);


if($_POST['dateUpto']=='')
{
		$getPMSDate = mysql_query("select review_to_date from employee_performance_review_Dates where employee_id='$Emplid' and is_active='Y'");
				$getPMSDateRow = mysql_fetch_array($getPMSDate);
				$Pmsdate  = $getPMSDateRow['review_to_date'];
				$allto = $Pmsdate;
}
else
{
$allto=mysql_real_escape_string($_POST['dateUpto']);
}
$todaydate = date('Y-m-d');
if($allfrom<$todaydate && $allto< $todaydate)
{
$isactive='N';
}
else
{
$isactive='Y';
}
$sql="insert into employee_projects (res_management_id,project_id,employee_id,created_Date_and_time,created_by,is_Active,allocated_percentage,allocated_from,allocated_to)

values

('$resId','$AddedProj','$Emplid',now(),'$name','$isactive','$allPer','$allfrom','$allto')";
mysql_query($sql);
$insertid = mysql_insert_id();
if($isactive=='N')
{
 mysql_query("insert into history_employee_projects select 0,now(),e.* from employee_projects e where id='$insertid'");
}

$transaction = "Project Allocation";
$module = "Resource Management";
$date = $allfrom;
$getProjectName = mysql_query("select project_name from all_projects where project_id='$AddedProj'");
$getProjectNameRow = mysql_fetch_array($getProjectName);
$ProjectName = $getProjectNameRow['project_name'];

FYITransaction($Emplid,$transaction,$module,$ProjectName,$date);
header("Location: ViewResource.php?id=$Emplid");
}
?>