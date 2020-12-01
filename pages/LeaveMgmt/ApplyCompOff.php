<?php
//require_once("queries.php");
session_start();
include('config.php');
include('Attendance_Config.php');
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$name'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$tracker_id = json_decode(stripslashes($_POST['ApprovalID']));
$EmployeeID = $_POST['EmployeeID'];
$Hours = $_POST['CurrentHours'];
$mins = $_POST['CurrentMins'];
$UnitType = $_POST['UnitType'];
if($UnitType=='Half Day')
{
	$Unit = 0.5;
}
else
{
	$Unit =1;
}
foreach($tracker_id as $id)
{
	echo $id.'<br>';
	mysqli_query($att,"update extra_hours_tracker set is_applied='Y' where extra_hours_tracker_id='$id'");
}
$ApprovedHours =$Hours.':'.$mins;
$Expirydate = date('Y-m-d', strtotime("+60 days"));
mysqli_query($att,"insert into comp_off_tracker
(employee_id,comp_off_date,duration,unit,expiry_Date,is_availed,approved_by,approved_date,remarks,extended_by,extended_date,from_time,to_time)
values
('$EmployeeID',curdate(),'$ApprovedHours','$Unit','$Expirydate','N','$EmpName',now(),'','','0001-01-01 00:00:00','0001-01-01 00:00:00','0001-01-01 00:00:00');");

mysqli_query($att,"update employee_leave_tracker set comp_off_closing=comp_off_closing +$Unit,comp_off_earned=comp_off_earned+$Unit where employee_id='$EmployeeID'
and month=month(curdate()) and year=year(curdate());");
?>