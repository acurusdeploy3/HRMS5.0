<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_SESSION['Employee_id'];
	
			include("config2.php");
			
			$getRadioValues  = mysqli_query($db,"select is_biometric_authorized,is_id_issued,is_login_created,is_system_allocated,is_data_sheet_completed,mail_type from employee_boarding where employee_id=$emplid");
			$getRadioRow = mysqli_fetch_array($getRadioValues);
			$BioMetric = $getRadioRow['is_biometric_authorized'];
			$IDCard = $getRadioRow['is_id_issued'];
			$LoginCreated = $getRadioRow['is_login_created'];
			$SystemAllocated = $getRadioRow['is_system_allocated'];
			$DataSheet = $getRadioRow['is_data_sheet_completed'];
			$mail_type = $getRadioRow['mail_type'];
			$getEmpValues  = mysqli_query($db,"select reporting_manager_id,mentor_id,employee_designation,department,official_email from employee_details where employee_id=$emplid");
			$getEmpRow = mysqli_fetch_array($getEmpValues);
			

$Mgmr = $getEmpRow['reporting_manager_id'];
$Role = $getEmpRow['employee_designation'];
$Dept = $getEmpRow['department'];
$Mentor = $getEmpRow['mentor_id'];
$official_email = $getEmpRow['official_email'];

$getRepMgmtname = mysqli_query($db,"select concat(First_name,' ',last_name,' ',MI) as Name from employee_details where employee_id='$Mgmr'");
$getRepMgmtnameRow = mysqli_Fetch_array($getRepMgmtname);
$RepMngrName = $getRepMgmtnameRow['Name'];


$getname = mysqli_query($db,"select concat(First_name,' ',last_name,' ',MI) as Name from employee_details where employee_id='$emplid'");
$getnameRow = mysqli_Fetch_array($getname);
$EmpName = $getnameRow['Name'];
$isFormComplete = 'Y';

if($BioMetric == 'No' || $IDCard =='No' || $LoginCreated =='No' || $SystemAllocated =='No')
	{
		$isFormComplete = 'N';
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='N' where
		employee_id='$emplid'");
	}
else
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='Y' where
		employee_id='$emplid'");
	}
	
	if($DataSheet!='Y')
	{
	$isFormComplete = 'N';	
	}
		
if($Role =='' || $Dept =='' || $Mentor  ==''|| $Mgmr =='' || $official_email=='')
	{
	$isFormComplete = 'N';
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_designated='N' where
	employee_id='$emplid'");
	}

else
	{
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_designated='Y' where
	employee_id='$emplid'");
	}

date_default_timezone_set('Asia/Kolkata');

if($isFormComplete=='N')
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='P' where
		employee_id='$emplid'");
	}
else
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='Y' where
		employee_id='$emplid'");	
		
	}

	