<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
//$MRFIDVal = $_POST['MRFID'];
$isEvalRequired = $_POST['EvalReqChk'];
$Time = $_POST['TimeTxt'];
$TrainFreqency = $_POST['TrainFreq'];
$Trainer = $_POST['TrainerSel'];
$RequestedBy =   $_POST['ReqFrom'];
$SessionsCount =   $_POST['SessionText'];
$DateCommence = $_POST['dateFrom'];
$TrainDept =  $_POST['TrainingDept'];
$TrainMod =  $_POST['TrainingModule'];



$InsertTraining = mysql_query("insert into active_employee_trainings
(training_module_id,trainer_id,date_from,is_evaluation_required,created_date_and_time,created_by,department,requested_by,training_frequency,session_count,allocated_time)
values
('$TrainMod','$Trainer',str_to_Date('$DateCommence','%m/%d/%Y'),if('$isEvalRequired'='Yes','Yes','No'),now(),'$name','$TrainDept','$RequestedBy','$TrainFreqency','$SessionsCount','$Time')");

$getID =  mysql_query("select training_id from active_employee_trainings where training_module_id='$TrainMod' and trainer_id='$Trainer' and date_from = str_to_Date('$DateCommence','%m/%d/%Y') and created_by = '$name' and department = '$TrainDept' and requested_by = '$RequestedBy' and training_frequency='$TrainFreqency' and session_count='$SessionsCount'");

$idRow = mysql_fetch_array($getID);
$TrainingID  = $idRow['training_id']; 

$Trainees = count($_POST["TraineesSelVal"]);
if($Trainees > 0)
{
		for($m=0; $m<$Trainees; $m++)  
		{  	
		
			$sql3 = mysql_query("insert into active_training_employees (employee_id,trainer_id,training_id,date_of_commencement,date_added) values('".$_POST["TraineesSelVal"][$m]."','$Trainer','$TrainingID',str_to_Date('$DateCommence','%m/%d/%Y'),now())"); 
			
		}
}

 
 
 