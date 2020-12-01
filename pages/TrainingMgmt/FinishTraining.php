<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
include('ModificationFunc.php');
//$MRFIDVal = $_POST['MRFID'];
$trainingID = $_GET['id'];
	
			include("config2.php");	
			$getCycleId  = mysqli_query($db,"select cycle_id,frequency,total_occurence_per_frequency from active_trainings where training_id=$trainingID");
			$getCycleIdRow = mysqli_fetch_array($getCycleId);
			$CycleIdCurrent = $getCycleIdRow['cycle_id'];
			$frequency = $getCycleIdRow['frequency'];
			$OPF = $getCycleIdRow['total_occurence_per_frequency'];
			$cycleIdNext = $CycleIdCurrent+1;
		
	//InActive Current Active Training
mysqli_query($db,"update active_trainings set date_completed=curdate() where training_id='$trainingID'");
$resQuery = mysqli_query($db,"insert into history_Active_trainings SELECT 0,now(),e.*  FROM active_Trainings e where training_id = '$trainingID'");
$resQuery1 = mysqli_query($db,"insert into history_training_sessions SELECT 0,now(),e.*  FROM training_sessions e where training_id = '$trainingID'");
$resQuery2 = mysqli_query($db,"insert into history_training_participants SELECT 0,now(),e.*  FROM training_participants e where training_id = '$trainingID'");
$resQuery3 = mysqli_query($db,"insert into history_training_attendance SELECT 0,now(),e.*  FROM training_attendance e where training_id = '$trainingID'");
$resQuery4 = mysqli_query($db,"insert into history_training_evaluation SELECT 0,now(),e.*  FROM training_evaluation e where training_id = '$trainingID'");

mysqli_query($db,"update training_sessions set is_Active='N' where training_id='$trainingID' and cycle_id='$CycleIdCurrent'");
mysqli_query($db,"update training_attendance set is_Active='N' where training_id='$trainingID' and cycle_id='$CycleIdCurrent'");
mysqli_query($db,"update training_evaluation set is_Active='N' where training_id='$trainingID' and cycle_id='$CycleIdCurrent'");
mysqli_query($db,"update training_participants set is_Active='N' where training_id='$trainingID' and cycle_id='$CycleIdCurrent'");
if($frequency=='Once')
{
	mysqli_query($db,"update active_trainings set is_Active='N',date_completed='0001-01-01' where training_id='$trainingID'");
}
else
{
	


if($frequency=='Weekly')
{
		$daystoincrease =round(7/$OPF);
}
if($frequency=='Monthly')
{
		$daystoincrease =round(30/$OPF);
}
if($frequency=='Quarterly')
{
		$daystoincrease =round(90/$OPF);
}
if($frequency=='Half Yearly')
{
		$daystoincrease =round(150/$OPF);
}
if($frequency=='Annually')
{
		$daystoincrease =round(365/$OPF);
}
$startDate = date('Y-m-d');
$EDC = date("Y-m-d", strtotime("$startDate +$daystoincrease day"));
mysqli_query($db,"update active_trainings set cycle_id='$cycleIdNext',date_completed='0001-01-01',date_started='$EDC' where training_id='$trainingID'");
}
header("Location: TrainingMgmt.php");
