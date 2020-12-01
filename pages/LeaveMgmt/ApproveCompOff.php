<?php
//require_once("queries.php");
session_start();
include('config.php');
include('Attendance_Config.php');
$name = $_SESSION['login_user'];
$tracker_id = $_POST['tracker_id'];
$Hours = $_POST['Hours'];
$mins = $_POST['mins'];
$reason = mysqli_real_escape_string($att,$_POST['reason']);
$ApprovedHours =$Hours.':'.$mins.':'.'00';
mysqli_query($att,"update extra_hours_tracker set approved_hours='$ApprovedHours',is_approved='Y',comments='$reason' where extra_hours_tracker_id='$tracker_id'");
?>