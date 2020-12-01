<?php
session_start();
require_once("config2.php");
require_once("config5.php");
$name = $_SESSION['login_user'];
$idvalue = mysqli_real_escape_string($db,$_POST['formval']);
$categoryval = mysqli_real_escape_string($db,$_POST['category']);
$expdevval = mysqli_real_escape_string($db,$_POST['expdev']);
$projectdet="select * from cos_master where cos_master_id='$idvalue'";
$chkprojectdet = mysqli_query($db,$projectdet);
$Result = mysqli_fetch_array($chkprojectdet);
$strengthcheck = "select * from cos_pip_summary where category ='$categoryval' and Expected_Deliverables = '$expdevval' and cos_master_id='$idvalue' and is_active='Y'";
echo $strengthcheck;
$result1 =mysqli_query($db,$strengthcheck);
if(mysqli_num_rows($result1)<1 && $expdevval !='' && $categoryval!='')
{
	
	$strengthareas = "Insert into `cos_pip_summary` (employee_id,category,Expected_Deliverables,cos_master_id,created_by,created_date_and_time) value ('".$Result['Employee_ID']."','$categoryval','$expdevval','$idvalue','$name',now())";
	$str=mysqli_query($db,$strengthareas);
	echo $strengthareas;
}
else
{
}
?>