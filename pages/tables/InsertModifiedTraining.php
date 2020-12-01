<?php
session_start();

$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
//$MRFIDVal = $_POST['MRFID'];
$isEvalRequired = $_POST['EvalReqChk'];
$trainingid = $_POST['trainid'];
$Time = $_POST['TimeTxt'];
$TrainFreqency = $_POST['TrainFreq'];
$Trainer = $_POST['TrainerSel'];
$RequestedBy =   $_POST['ReqFrom'];
$SessionsCount =   $_POST['SessionText'];
$DateCommence = $_POST['dateFrom'];
$TrainDept =  $_POST['TrainingDept'];
$TrainMod =  $_POST['TrainingModule'];

$insertupdatedtraining =mysql_query("update active_employee_trainings 
set training_module_id ='$TrainMod',
trainer_id='$Trainer',
date_from=str_to_Date('$DateCommence','%m/%d/%Y'),
modified_date_and_time=now(),
modified_by='$name',
department='$TrainDept',
requested_by='$RequestedBy',
training_frequency='$TrainFreqency',
session_count='$SessionsCount',
allocated_time='$Time' where training_id='$trainingid'");

$Trainees = count($_POST["TraineesSelVal"]);
if($Trainees > 0)
{
		for($m=0; $m<$Trainees; $m++)  
		{  	
		
			$sql3 = mysql_query("insert into active_training_employees (employee_id,trainer_id,training_id,date_of_commencement,date_added) values('".$_POST["TraineesSelVal"][$m]."','$Trainer','$trainingid',str_to_Date('$DateCommence','%m/%d/%Y'),now())"); 
			
		}
}

 
 
 