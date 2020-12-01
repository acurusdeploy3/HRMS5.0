<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$trainer = $_POST['TrainerSel'];
$SessDate = $_POST['sessdate'];
$SessionsCount = $_POST['SessDuration'];
$Topc = $_POST['topicText'];
$TimeComm =  $_POST['startTime'];
$TrainMod =  $_POST['TrainingMode'];
$Cycle =  $_POST['cycleVal'];
$Occ =  $_POST['OccVal'];
date_default_timezone_set('Asia/Kolkata');
$trainid = $_SESSION['trainingId'];
$getSessions = mysqli_query($db,"select * from training_Sessions where training_id=$trainid");

if(mysqli_num_Rows($getSessions)==0)
{
	mysqli_query($db,"update active_trainings set date_started='$SessDate' where training_id=$trainid and cycle_id=$Cycle and is_active='Y'");
}
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

('$trainid','$Cycle','$Occ','$Topc','$trainer','$SessDate','$TimeComm','$SessionsCount','$TrainMod',now(),'$name','Y')");
$getSessionId= mysqli_insert_id($db);
$getParticipants = mysqli_query($db,"select employee_id from training_participants where training_id='$trainid' and cycle_id='$Cycle' and occurence_count='$Occ' and is_active='Y'");

while($GetEmployees  = mysqli_fetch_assoc($getParticipants))
{
	$InsertAttendance = mysqli_query($db,"insert into training_attendance (training_id,session_id,employee_id,trainer_id,created_date_and_time,created_by,cycle_id)
	values ('$trainid','$getSessionId','".$GetEmployees['employee_id']."','$trainer',now(),'$name','$Cycle')");
}

//header("Location : AllocateSession.php");