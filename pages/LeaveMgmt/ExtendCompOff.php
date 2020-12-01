<?php
//require_once("queries.php");
session_start();
include('config.php');
include('Attendance_Config.php');
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$name'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$CoffId = $_POST['CoffId'];
$ExtDays = $_POST['ExtDays'];
mysqli_query($att,"update comp_off_tracker set extended_date=DATE_ADD(expiry_date, INTERVAL $ExtDays DAY),extended_by='$EmpName' where id='$CoffId'");
echo $CoffId;
?>