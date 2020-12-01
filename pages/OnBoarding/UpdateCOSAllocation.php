<?php
include('config2.php');
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$process=$_POST['process'];
$empid=$_POST['EmpIdvalue'];
$cosid=$_POST['CosIdValue'];
$AllIdvalue=$_POST['AllIdvalue'];
$StrValue = mysqli_real_escape_string($db,$_POST['StrValue']);
$ImpValue = mysqli_real_escape_string($db,$_POST['ImpValue']);
$FedValue = mysqli_real_escape_string($db,$_POST['FedValue']);
$DeptValue = mysqli_real_escape_string($db,$_POST['DeptValue']);
$AppValue = mysqli_real_escape_string($db,$_POST['AppValue']);
$RemValue = mysqli_real_escape_string($db,$_POST['RemValue']);
$ProdValue = isset($_POST['ProdValue'])?mysqli_real_escape_string($db,$_POST['ProdValue']):'0.00';
$QualValue = isset($_POST['QualValue'])?mysqli_real_escape_string($db,$_POST['QualValue']):'0.00';
$ExtQual = mysqli_real_escape_string($db,$_POST['ExtQual']);
$Role = $_POST['Role'];
$hrdetails = mysqli_query($db,"SELECT value FROM `application_configuration` where config_type='COS_HANDLING' and parameter ='HR_ID'");
$hrid = mysqli_fetch_array($hrdetails);
$hrval = $hrid['value'];
$showcurrentstatus ="select * from cos_process_queue_table where COS_Process_Queue_Name='$process'";
$resultstatus=mysqli_query($db,$showcurrentstatus);
$resultid = mysqli_fetch_array($resultstatus);
$resultval = $resultid['COS_Process_Queue_ID'];
$sql="update `cos_master` set COS_Pending_Queue_ID=concat(substring_index('$AllIdvalue',' -',1),'-','$Role'),COS_Process_Queue_ID='$resultval' where employee_id='$empid' and COS_Master_ID='$cosid'";
$result=mysqli_query($db,$sql);
echo $sql;
if($process=='Manager_Approved')
{
	$selectquery = mysqli_query($db,"select * from cos_review_summary where cos_master_id='$cosid'");
	if(mysqli_num_rows($selectquery)==0)
	{
		$insertval = mysqli_query($db,"insert into cos_review_summary (COS_Master_ID,Employee_ID,Productivity,Quality,employee_strengths,employee_development_areas,manager_feedback,is_active,created_date_and_time,created_by) values ('$cosid','$empid','$ProdValue','$QualValue','$StrValue','$ImpValue','$FedValue','Y',now(),'$name')");
		$insertquery = mysqli_query($db,"insert into cos_transaction SELECT 0, COS_Master_ID, Employee_ID, COS_Process_Queue_ID, COS_Pending_Queue_ID,
		Substring_index(COS_Pending_Queue_ID,'-',-1),date(now()),'0001-01-01', 0, is_active, now(), '$name' FROM `cos_master` m where employee_id='$empid'");
	}
		
}
else if($process=='HOD_Approved')
{
	$selectquery = mysqli_query($db,"select * from cos_review_summary where cos_master_id='$cosid'");
	if(mysqli_num_rows($selectquery)==0)
	{
		$insertval = mysqli_query($db,"insert into cos_review_summary (COS_Master_ID,Employee_ID,Productivity,Quality,employee_strengths,employee_development_areas,manager_feedback,is_active,created_date_and_time,created_by) values ('$cosid','$empid','$ProdValue','$QualValue','$StrValue','$ImpValue','$FedValue','Y',now(),'$name')");
		$insertquery = mysqli_query($db,"insert into cos_transaction SELECT 0, COS_Master_ID, Employee_ID, COS_Process_Queue_ID, COS_Pending_Queue_ID,
		Substring_index(COS_Pending_Queue_ID,'-',-1),date(now()),'0001-01-01', 0, is_active, now(), '$name' FROM `cos_master` m where employee_id='$empid'");
	}
		
}
else
{
	$selectquery = mysqli_query($db,"select * from cos_review_summary where cos_master_id='$cosid'");
	if(mysqli_num_rows($selectquery)==0)
	{
		$insertquery = mysqli_query($db,"insert into cos_transaction SELECT 0, COS_Master_ID, Employee_ID, COS_Process_Queue_ID, COS_Pending_Queue_ID,
		Substring_index(COS_Pending_Queue_ID,'-',-1),date(now()),'0001-01-01', 0, is_active, now(), '$name' FROM `cos_master` m where employee_id='$empid'");
		if($process != 'PIP_Requested' || $process !='HOD_PIP_Approved' || $process!='HR_PIP_Approved' )
		{
			$insertval = mysqli_query($db,"insert into cos_review_summary (COS_Master_ID,Employee_ID,Productivity,Quality,employee_strengths,employee_development_areas,manager_feedback,hod_recommendation,hr_approval,remarks,is_active,created_date_and_time,created_by) values ('$cosid','$empid','$ProdValue','$QualValue','$StrValue','$ImpValue','$FedValue','$DeptValue','$AppValue','$RemValue','Y',now(),'$name')");
		}
	}
	else
	{
		$insertquery = mysqli_query($db,"insert into cos_transaction SELECT 0, COS_Master_ID, Employee_ID, COS_Process_Queue_ID, COS_Pending_Queue_ID,
		Substring_index(COS_Pending_Queue_ID,'-',-1),date(now()),'0001-01-01', 0, is_active, now(), '$name' FROM `cos_master` m where employee_id='$empid'");
		if($process=='PIP_Requested' || $process =='HOD_PIP_Approved' || $process =='HR_PIP_Approved')
		{}
		else {
		$updatequery = mysqli_query($db,"update cos_review_summary set hod_recommendation='$DeptValue',hr_approval='$AppValue' ,remarks='$RemValue',modified_date_and_time=now(),modified_by='$name' where employee_id='$empid' and COS_Master_ID='$cosid'");
		}
	}
	if($process == 'HR_PIP_Approved')
	{
		$sql1="update `cos_master` set Extension_Comments='$ExtQual' where employee_id='$empid' and COS_Master_ID='$cosid'";
		$result1=mysqli_query($db,$sql1);
	}
}
//header("Location: ConfirmServices.php")
?>
