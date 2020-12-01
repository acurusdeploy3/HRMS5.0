<?php
//require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$getRepMgmr = mysqli_query($db,"select reporting_manager_id from employee_Details where employee_id='$name'");
$getRepMgmrRow = mysqli_fetch_array($getRepMgmr);
$RepMgmr = $getRepMgmrRow['reporting_manager_id'];

$LeaveType = $_POST['EmployeeLeave'];
$NumDays = $_POST['NumberDays'];
$dateFrom =$_POST['dateFrom'];
$leavefor =$_POST['LeaveFor'];
$dateTo =$_POST['dateTo'];
$reason =$_POST['reason'];
		
		
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by)
values
('$name','$RepMgmr','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name');");

?>