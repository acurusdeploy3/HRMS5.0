<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d h:i:s");
$temp = $_POST['ResIdvalue'];
$id = $_POST['EmpIdvalue'];
$process=$_POST['process'];
$hod_comments=mysqli_real_escape_string ($db,$_POST['hod_comments']);
$query1 = mysqli_query($db,"select job_role from employee_details where employee_id=$id");
$detrow = mysqli_fetch_assoc($query1);
$role = $detrow['job_role'];
if(!empty($id)) {
	if ($_POST['process']=='Approve'){
	$result = mysqli_query($db,"UPDATE employee_resignation_information set status = 'Process Resignation',hod_comments='$hod_comments',is_active='Y',modified_by=$name,process_queue='HOD_Process',modified_date_and_time=now(),pending_queue_id=(SELECT concat(value,'-hrm') FROM `application_configuration` where parameter='HR_MNGR_ID' and module='All') 	WHERE employee_id=$id and resignation_id=$temp");
	header("Location: sendstatusemail.php?rid=$temp");
	//header("Location: departmentresignationform.php");
	}
	if ($_POST['process']=='Deny'){
	$query2 ="Update employee_resignation_information set date_of_cancellation_of_resignation='".$date."' , hod_comments = '$hod_comments', date_of_leaving='0001-01-01',status = 'Cancel Resignation' , process_queue ='HOD_Cancelled',modified_date_and_time=now() where employee_id=$id and resignation_id='$temp' and is_active='Y' and ((status='Process Resignation' and process_queue='Manager_Process' or (status='Resignation Request Sent' and process_queue='Manager_Process')))order by resignation_id desc limit 1 ";
	$result2=mysqli_query($db,$query2);
	storeDataInHistory($temp , "employee_resignation_information","resignation_id");
	$result1 = mysqli_query($db,"UPDATE employee_resignation_information set is_active='N' WHERE employee_id=$id and resignation_id=$temp");
	 if(!$result2){
			$message="Problem adding to database . Please Retry";			
			} else {
		header("Location:sendcancellationmail.php?rid=$temp");
		//header("Location: departmentresignationform.php");
		}
	}
}
?>