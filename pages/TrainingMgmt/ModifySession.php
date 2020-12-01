<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$trainer = $_POST['TrainerSelModify'];
$SessDate = $_POST['sessdateMdify'];
$SessionsCount = $_POST['SessDurationModify'];
$Topc = $_POST['topicTextModify'];
$TimeComm =  $_POST['startTimeModify'];
$TrainMod =  $_POST['TrainingModeModify'];
date_default_timezone_set('Asia/Kolkata');
$sessionid = $_POST['sessionid'];

$updatesession = mysqli_query($db,"update training_sessions set trainer ='$trainer',topic='$Topc',date_of_session='$SessDate',session_duration='$SessionsCount',mode_of_Training='$TrainMod',session_time='$TimeComm',modified_Date_and_time=now(),modified_by='$name' where session_id='$sessionid' and is_Active='Y'");

$modifyattendance = mysqli_query($db,"update training_attendance set trainer_id='$trainer' where session_id='$sessionid' and is_Active='Y'");

//header("Location : AllocateSession.php");