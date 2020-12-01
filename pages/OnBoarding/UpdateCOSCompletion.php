<?php
session_start();
$effectivefrom = date("Y-m-d");
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');

$EmpIdvalue = $_POST['EmpIdvalue'];
$CosIdValue = $_POST['CosIdValue'];
$process = $_POST['process'];

$empdet=mysqli_query($db,"select * from cos_master WHERE cos_master_id=$CosIdValue");
$detais = mysqli_fetch_array($empdet);
$EmployeeId=$EmpIdvalue;


if(!empty($CosIdValue)) {
	if($process =='IN_PIP')
	{
	$sql="update `cos_master` set is_extended='Y',COS_Process_Queue_ID='10',is_active='N',extension_count='1',modified_date_and_time=now(),	modified_by='$name',COS_Pending_Queue_ID='-' where employee_id='$EmpIdvalue' and COS_Master_ID='$CosIdValue'";
	$result=mysqli_query($db,$sql);
	header("Location: UpdateExtension.php?EmployeeId=$EmployeeId");
	}
	else
	{
	$sql="update `cos_master` set COS_Pending_Queue_ID=concat(substring_index('$AllIdvalue',' -',1),'-','$Role'),COS_Process_Queue_ID='6',	is_active='N',modified_date_and_time=now(),
	modified_by='$name' where employee_id='$EmpIdvalue' and COS_Master_ID='$CosIdValue'";
	$result=mysqli_query($db,$sql);
	
	$result1="update cos_review_summary set is_active='N',modified_date_and_time=now(),modified_by='$name' where employee_id='$EmpIdvalue' and COS_Master_ID='$CosIdValue'";
	
	$result2="update cos_transaction set is_active='N',modified_date_and_time=now(),modified_by='$name' where employee_id='$EmpIdvalue' and COS_Master_ID='$CosIdValue'";
	
	$getDetails = mysqli_query($db,"select concat(first_name,' ',MI,' ',Last_Name) as EmployeeName from employee_details WHERE employee_id='$EmployeeId'");
	$details = mysqli_fetch_array($getDetails);
	$EmployeeName = $details['EmployeeName'];
	
	$getApplicantDetails = mysqli_query($db,"update employee_details set is_services_confirmed='Y',services_effective_date='$effectivefrom' where employee_id='$EmployeeId'");
	
	$transaction = "Confirmation of Services";
	$module = "Boarding";
	$date = $effectivefrom;
	$ProjectName = "Your Services have been confirmed.";

	mysqli_query($db,"insert into fyi_transaction(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by) values ('$EmployeeId','$EmployeeName','$transaction','$module','$ProjectName','$date','Y',now(),'Acurus')");
	header("Location: UpdateConfirmation.php?EmployeeId=$EmployeeId");
	}
}
?>