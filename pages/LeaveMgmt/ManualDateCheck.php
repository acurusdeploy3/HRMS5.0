<?php
include("config2.php");
session_start();
$adminLog = $_SESSION['login_user'];
$name = $_POST['AppEmpId'];
$email = $_POST['dateFrom'];
$email1 = $_POST['dateTo'];
if($email=='')
{
$email = $dateToday;
}
if($email1=='')
{
$email1 = $dateToday;
}
$LeaveType = $_POST['EmployeeLeave'];
$PMType = $_POST['PermissionType'];
$LeaveFor = $_POST['LeaveFor'];
$NoHours = $_POST['NumberHours'];
$Ret = 'pos';
if($LeaveType == 'Permission')
{
	$getAvailedPermission = mysqli_query($db,"select * from leave_request where leave_type='Permission' and employee_id='$name' and is_canceled='N' and
	(
	(is_active='N' and is_approved='Y')
	or
	(is_Active='Y' and is_approved='')
	)
 and leave_from='$email'");
 
 $getLeaveType = mysqli_query($db,"select * from leave_request where leave_from='$email' and leave_for in ('Half-Day (First Half)','Half-Day (Second Half)') and employee_id='$name' and is_canceled='N' and
(
(is_active='Y' and is_approved='')
or
(is_active='N' and is_approved='Y')
)");
 if(mysqli_num_rows($getLeaveType)==0)
 {
	$getDate = mysqli_query($db,"select * from leave_request where employee_id='$name' and is_canceled='N' and
(
    (is_approved='Y' and is_active='N')
      or
    (is_approved='' and is_active='Y')
 )
 and
 (
 ('$email' between leave_from and leave_to)
or
  ('$email1' between leave_from and leave_to)
or
  ('$email' < leave_from and '$email1' > leave_to)
 )");
  
 }
 
 if(mysqli_num_rows($getAvailedPermission)==2 || mysqli_num_rows($getDate)>0 || mysqli_num_rows($getLeaveType)>1)
 {
	$Ret = 'neg1';
 }
 else
 {
	 $Ret =  'pos';
 }
}
if($LeaveType!='Casual & Sick' && $LeaveType!='Privilege & Sick' && $LeaveFor=='Half-Day (First Half)')
{
	$getAvailedHD = mysqli_query($db,"select * from leave_request where  employee_id='$name'  and leave_for='Half-Day (First Half)' and is_canceled='N' and is_canceled='N' and
	(
	(is_active='N' and is_approved='Y')
	or
	(is_Active='Y' and is_approved='')
	)
 and leave_from='$email'");
 $getLeaveType = mysqli_query($db,"select * from leave_request where leave_from='$email' and leave_for in ('Late In','Early Out','Half-Day (Second Half)') and employee_id='$name' and is_canceled='N' and
(
(is_active='Y' and is_approved='')
or
(is_active='N' and is_approved='Y')
)");
 if(mysqli_num_rows($getLeaveType)==0)
 {
 $getDateSO = mysqli_query($db,"select * from leave_request where employee_id='$name' and leave_type!='Permission' and is_canceled='N' and
(
    (is_approved='Y' and is_active='N')
      or
    (is_approved='' and is_active='Y')
 )
 and
 (
 ('$email' between leave_from and leave_to)
or
  ('$email1' between leave_from and leave_to)
or
  ('$email' < leave_from and '$email1' > leave_to)
 ) and leave_to<>'0001-01-01'");
 }
 if(mysqli_num_rows($getAvailedHD)>0 || mysqli_num_rows($getDateSO)>0 || mysqli_num_rows($getLeaveType)>1)
 {
	 $Ret =  'neg2';
 }
}
if($LeaveType!='Casual & Sick' && $LeaveType!='Privilege & Sick' && $LeaveFor=='Half-Day (Second Half)')
{
	$getAvailedHD = mysqli_query($db,"select * from leave_request where  employee_id='$name' and leave_type!='Permission' and leave_for='Half-Day (Second Half)' and is_canceled='N' and
	(
(is_active='Y' and is_approved='')
or
(is_active='N' and is_approved='Y')
)
 and leave_from='$email'");
 $getLeaveType = mysqli_query($db,"select * from leave_request where leave_from='$email' and leave_for in ('Late In','Early Out','Half-Day (First Half)') and employee_id='$name' and
(
(is_active='Y' and is_approved='')
or
(is_active='N' and is_approved='Y')
)");
if(mysqli_num_rows($getLeaveType)==0)
 {
 $getDateSO = mysqli_query($db,"select * from leave_request where employee_id='$name' and is_canceled='N' and
(
    (is_approved='Y' and is_active='N')
      or
    (is_approved='' and is_active='Y')
 )
 and
 (
 ('$email' between leave_from and leave_to)
or
  ('$email1' between leave_from and leave_to)
or
  ('$email' < leave_from and '$email1' > leave_to)
 ) and leave_to<>'0001-01-01'");
 }
 if(mysqli_num_rows($getAvailedHD)>0 || mysqli_num_rows($getDateSO)>0 || mysqli_num_rows($getLeaveType)>1)
 {
	 $Ret =  'neg3';
 }
}
if($LeaveType!='Permission' && $LeaveFor!='Half-Day (Second Half)' && $LeaveFor!='Half-Day (First Half)')
{
	$getDate = mysqli_query($db,"select * from leave_request where employee_id='$name'  and is_canceled='N' and leave_type!='Permission' and
(
    (is_approved='Y' and is_active='N')
      or
    (is_approved='' and is_active='Y')
 )
 and
 (
 ('$email' between leave_from and leave_to)
or
  ('$email1' between leave_from and leave_to)
or
  ('$email' < leave_from and '$email1' > leave_to)
 ) and leave_to<>'0001-01-01'");
if($adminLog!='3' && $LeaveType=='On-Duty')
{
	$getDateOD = mysqli_query($db,"select * from leave_request where employee_id='$name'  and is_canceled='N' and leave_type='On-Duty' and
	(
    (is_approved='Y' and is_active='N')
      or
    (is_approved='' and is_active='Y')
 	)
 	and leave_from =curdate()");
}
 $getAvailedPermission = mysqli_query($db,"select * from leave_request where leave_type='Permission' and employee_id='$name' and is_canceled='N' and
	(
	(is_active='N' and is_approved='Y')
	or
	(is_Active='Y' and is_approved='')
	)
 and leave_from='$email'");
	if(mysqli_num_rows($getDate)>0 || mysqli_num_rows($getAvailedPermission)>0 || mysqli_num_rows($getDateOD)>0)
	{
		$Ret =  'neg';
	}
	else
	{
		$Ret =  'pos';
	}
}
echo $Ret;


  	exit();
?>