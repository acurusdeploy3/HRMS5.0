<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_SESSION['Employee_id'];
$OMail = $_POST['OfficialMail'];
$Mgmr = $_POST['RepMgmr'];
$Role = $_POST['RoleSelect'];
$Lev = $_POST['LevelSel'];
$Wks = $_POST['WKS'];
$Dept = $_POST['DeptSelect'];
$Band = $_POST['BandSelect'];
$Mentor = $_POST['MentorSel'];
if($Lev=='None')
{
	$Lev='';
}
if($Band=='None')
{
	$Band='';
}
$degn = $Band.' '.$Role.' '.$Lev;
date_default_timezone_set('Asia/Kolkata');


$getreportingmanagerId = mysqli_query($db,"select official_email,reporting_manager_id from employee_details where employee_id='$Mgmr'");
$getRMRow = mysqli_fetch_array($getreportingmanagerId);
$getRMail = $getRMRow['official_email'];
if($Dept=='RCM' || $Dept=='HIM' || $Dept=='PUBLISHING')
{
	if($Mgmr=='265' || $Mgmr=='371' || $Mgmr=='603' || $Mgmr=='1032' || $Mgmr=='1' || $Mgmr=='940')
    {
    	$BackupManager = $Mgmr;
    }
	else
    {
    	$BackupManager = $getRMRow['reporting_manager_id'];
    }
}
else
{
	$BackupManager = $Mgmr;
}

$UpdateEmployee = mysqli_query($db,"update employee_details set employee_designation='$degn',official_Email='$OMail',Department='$Dept',reporting_manager_id='$Mgmr',backup_manager_id='$BackupManager',workstation='$Wks',mentor_id='$Mentor' where employee_id='$emplid'");
$UpdateAttEmp = mysqli_query($db,"update employee set manager_id='$Mgmr',bkup_manager_id='$BackupManager',primary_manager_id='$Mgmr' where employee_id='$emplid'");

$CheckResMgmt = mysqli_query($db,"select department,designation,reporting_manager,created_date_and_time,created_by,is_Active,band,level from resource_management_table where employee_id=$emplid");
$ResMgmtRow = mysqli_fetch_assoc($CheckResMgmt);
$departmentRes = $ResMgmtRow['department'];
$DesgnRes = $ResMgmtRow['designation'];
$RepMgmrRes = $ResMgmtRow['reporting_manager'];
$LevelRes = $ResMgmtRow['level'];
$BandRes = $ResMgmtRow['band'];

if(mysqli_num_rows($CheckResMgmt)>0)
{
	$updateNew =mysqli_query($db,"update resource_management_table set modified_Date_and_time=now(),modified_by='$name',
	department='$Dept',designation='$Role',reporting_manager='$Mgmr',effective_from=curdate(),band='$Band',level='$Lev',signed_loa_doc='0'
	where employee_id='$emplid'");
}
else
{
	$updateresMgmt = mysqli_query($db,"insert into resource_management_table

(employee_id,department,designation,reporting_manager,created_date_and_time,created_by,is_Active,band,level,signed_loa_doc,effective_from)

values
('$emplid','$Dept','$Role','$Mgmr',now(),'$name','Y','$Band','$Lev',0,curdate())");	
}

if($Role =='' || $Dept =='' || $Mentor  ==''|| $Mgmr =='' || $OMail=='')
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
	
$getVal = mysqli_query($db,"select * from notification_contact_email where employee_id=$emplid");

if(mysqli_num_rows($getVal)==0)
{
mysqli_query($db,"insert into notification_contact_email (employee_id,contact_email,reporting_manager_contact_email)
values 
('$emplid','$OMail','$getRMail')");
}
else
{		
	mysqli_query($db,"update notification_contact_email set contact_email='$OMail',reporting_manager_contact_email='$getRMail' where employee_id='$emplid'");
}
$getStatus = mysqli_query($db,"select are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed
from employee_boarding
where employee_id='$emplid'");
$getCompStatus = mysqli_fetch_array($getStatus);
$getDocStatus = $getCompStatus['are_documents_uploaded'];
$getEmpStatus = $getCompStatus['is_designated'];
$getProvStatus = $getCompStatus['is_provisions_completed'];
$getDataStatus = $getCompStatus['is_data_sheet_completed'];

if($getEmpStatus == 'N' || $getProvStatus=='N' || $getDataStatus=='N')
{	
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='P' where
		employee_id='$emplid'");	
}	
