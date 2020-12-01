<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_GET['EventID'];
$empId = $_GET['empId'];
mysqli_query($db,"update event_invitation_acceptors set has_attended='Y' where employee_id='$empId' and event_id='$EventID'");
header("Location: MarkAttendance.php?id=$EventID");
?>