<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];

date_default_timezone_set('Asia/Kolkata');
$trainid= $_GET['id'];
$CycleId= $_GET['CycleID'];
$enrollpart = mysqli_query($db,"insert into training_participants (training_id,employee_id,created_date_and_time,created_by,is_Active,cycle_id,occurence_count)
values
('$trainid','$name',now(),'$name','Y','$CycleId','1')");
$getSessions = mysqli_query($db,"select session_id,trainer from training_Sessions where training_id='$trainid' and is_active='Y' and cycle_id='$CycleId'");
		while($getSessionRow = mysqli_fetch_assoc($getSessions))
		{
			$InsertAttendance = mysqli_query($db,"insert into training_attendance (training_id,session_id,employee_id,trainer_id,created_date_and_time,created_by,cycle_id)
			values ('$trainid','".$getSessionRow['session_id']."','$name','".$getSessionRow['trainer']."',now(),'$name','$CycleId')");
		}
header("Location: TrainingMgmt.php");