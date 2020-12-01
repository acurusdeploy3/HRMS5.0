<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];

date_default_timezone_set('Asia/Kolkata');
$trainid = $_SESSION['trainingId'];
$sessid= $_GET['id'];
$EmployeId= $_GET['EmpId'];
$startsession = mysqli_query($db,"update training_attendance set has_attended='N', modified_by='$name' where session_id='$sessid' and employee_id='$EmployeId'");




header("Location: ViewSession.php?id=$trainid");