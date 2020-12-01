<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$trainer = $_POST['TrainerSel'];
$SessDate = $_POST['sessdate'];
$SessionsCount =   $_POST['SessDuration'];
$Topc = $_POST['topicText'];
$TimeComm =  $_POST['startTime'];
$TrainMod =  $_POST['TrainingMode'];
$Cycle =  $_POST['cycleVal'];
$Occ =  $_POST['OccVal'];
date_default_timezone_set('Asia/Kolkata');
$trainid = $_SESSION['trainingId'];

$InsertTraining = mysqli_query($db,"insert into training_sessions

(training_id,
cycle_id,
occurence_count,
topic,
trainer,
date_of_Session,
session_time,
session_duration,
mode_of_Training,
created_date_and_time,
created_by
,is_Active)

values

('$trainid','$Cycle','$Occ','$Topc','$trainer','$SessDate',STR_TO_DATE('$TimeComm', '%h:%i %p' ),'$SessionsCount','$TrainMod',now(),'$name','Y')");

//header("Location : AllocateSession.php");