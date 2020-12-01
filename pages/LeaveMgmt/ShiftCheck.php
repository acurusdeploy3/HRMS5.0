<?php
include("config2.php");
include("Attendance_Config.php");
session_start();
$name = $_SESSION['login_user'];
$EmpID = $_POST['AppEmpId'];
$email = $_POST['dateFrom'];
$email1 = $_POST['dateTo'];
$ShiftType = $_POST['EmployeeShift'];

$Ret = 'pos';
$CheckforModified = mysqli_query($att,"select * from employee_shift where end_date>=curdate() and employee_id=$EmpID");
if(mysqli_num_rows($CheckforModified)>1)
{
	$getChangedShift = mysqli_query($att,"select * from employee_shift where employee_id= '$EmpID' and
			start_date='$email' and end_date='$email1'");
			if(mysqli_num_rows($getChangedShift)==0)
			{
				$IfStartbetween = mysqli_query($att,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where employee_id='$EmpID'
									and start_date = '$email' and end_date < '$email1'");
									
			$IfEndbetween = mysqli_query($att,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where employee_id='$EmpID'
									and start_date > '$email' and end_date = '$email1'");
			$getExtras = mysqli_query($att,"SELECT * FROM `employee_shift` where employee_id=$EmpID and start_Date >= '$email' and end_date  <='$email1';");
			}
		if(mysqli_num_rows($IfStartbetween)>0 || mysqli_num_rows($IfEndbetween)>0 || mysqli_num_rows($getExtras)>0 )
		{
			$Ret='neg';
		}	
}
echo $Ret;

  	exit();
?>