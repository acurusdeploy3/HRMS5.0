<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];

date_default_timezone_set('Asia/Kolkata');
$trainid = $_SESSION['trainingId'];
$sessid= $_GET['id'];
$startsession = mysqli_query($db,"update training_Sessions set is_completed='Y' where session_id='$sessid'");




header("Location: ViewSession.php?id=$trainid");