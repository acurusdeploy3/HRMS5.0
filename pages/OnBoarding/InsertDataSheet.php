<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_SESSION['Employee_id'];
$BusinessUnit = $_POST['BusinessUnitSelect'];
$ESIC = $_POST['ESICNumber'];
$LocOffice = $_POST['LocalOfficeSelect'];
$SalaryMode = $_POST['SalaryPaymentModeSelect'];
$PFNum = $_POST['ProvidentFundNumber'];
$DispNum = $_POST['DispensaryNumber'];
$PAnNum = $_POST['PANNumber'];
$Bank = $_POST['SalaryBankSelect'];
$Branch = $_POST['BankBranch'];
$AccNu = $_POST['AccountNumber'];
$IFSc = $_POST['IFSCCode'];
$DOAss = $_POST['DOA'];
$AssMa = $_POST['AssMarks'];
$TySpeed = $_POST['TypingSpeed'];
$CondBy = $_POST['ConductedBySelect'];
$MaxSc = $_POST['MaxScore'];
$Accuracy = $_POST['TypingAcc'];
$degn = $Band.' '.$Role.' '.$Lev;
date_default_timezone_set('Asia/Kolkata');

if($DOAss==''){ $DOAss ='0001-01-01';}

if(!mysqli_query($db,"update employee_details set business_unit='$BusinessUnit',pf_number='$PFNum',esic_number='$ESIC',dispensary='$DispNum',local_office='$LocOffice',salary_payment_mode='$SalaryMode',
bank_name='$Bank',account_number='$AccNu',branch='$Branch',ifsc_code='$IFSc',
date_of_assessment='$DOAss',conducted_by='$CondBy',marks_obtained='$AssMa',max_score='$MaxSc',typing_speed='$TySpeed',typing_accuracy='$Accuracy' where employee_id='$emplid'"))

{
	 echo("Error description: " . mysqli_error($db));
}



$CheckforPAN = mysqli_query($db,"select document_type,document_number from kye_details where employee_id='$emplid' and document_type='PERMANANT ACCOUNT NUMBER' and is_active='Y'");
$CheckforPANRow = mysqli_fetch_array($CheckforPAN);
$PANOLD = $CheckforPANRow['document_number'];
$CntCheck = mysqli_num_rows($CheckforPAN);

if($CntCheck==0 && $PAnNum!='')
{
	$insertPan = mysqli_query($db,"insert into kye_details
(employee_id,document_type,document_number,has_Expiry,valid_from,valid_to,createD_date_and_time,created_by,is_Active)

values

('$emplid','PERMANANT ACCOUNT NUMBER','$PAnNum','N','0001-01-01','0001-01-01',now(),'$name','Y')");
}
elseif($PANOLD != $PAnNum)
{
	$updatepan = mysqli_query($db,"update kye_details set document_number='$PAnNum',modified_Date_and_time=now(),modified_by='$name' where employee_id='$emplid' and is_active='Y' and document_type='PERMANANT ACCOUNT NUMBER'");
}
else
{
	$updatepan = mysqli_query($db,"update kye_details set document_number='$PAnNum' where employee_id='$emplid'");
}

if($PAnNum =='' || $BusinessUnit =='' || $LocOffice =='' || $SalaryMode=='' || $Bank=='' ||  $AccNu==''  ||  $IFSc=='' ||  $Branch=='' || $PFNum=='')
	{
		
		$UpdateEmployee6 = mysqli_query($db,"update employee_boarding set is_data_sheet_completed='N' where
		employee_id='$emplid'");
	}

else
	{
			$UpdateEmployee5 = mysqli_query($db,"update employee_boarding set is_data_sheet_completed='Y' where
			employee_id='$emplid'");
	}
	
	
$getStatus = mysqli_query($db,"select are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed
from employee_boarding
where employee_id='$emplid'");
$getCompStatus = mysqli_fetch_array($getStatus);
$getDocStatus = $getCompStatus['are_documents_uploaded'];
$getEmpStatus = $getCompStatus['is_designated'];
$getProvStatus = $getCompStatus['is_provisions_completed'];
$GetDataStatus = $getCompStatus['is_data_sheet_completed'];

if($getEmpStatus == 'N' || $getProvStatus=='N' || $GetDataStatus=='N')
{	
	$UpdateEmployee2 = mysqli_query($db,"update employee_boarding set is_formalities_completed='P' where
		employee_id='$emplid'");	
}	