<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
//$MRFIDVal = $_POST['MRFID'];
$AppID = $_POST['AppIDRemove'];
$Reason = $_POST['ReasonNotReport'];
$ModifiedDOJ = $_POST['DOJDate'];
$Status = $_POST['JoineeStatus'];


date_default_timezone_set('Asia/Kolkata');

if($Status == 'Remove')
{
$RemEmp = mysqli_query($db1,"update applicant_Tracker set Potential_future_consideration='N',date_of_joining='0001-01-01 00:00:00',joining_status='Candidate Did Not Show Up',reason_not_joined='$Reason'
 where applicant_id='$AppID'");

}	
else
{
$RemEmp = mysqli_query($db1,"update applicant_Tracker set date_of_joining='$ModifiedDOJ',joining_status='Candidate Did Not Show Up',reason_not_joined='$Reason'
 where applicant_id='$AppID'");	
}

header("Location: BoardingHome.php");