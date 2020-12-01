<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$isEvalRequired = $_POST['isEvalRequired'];
$TrainFreqency = $_POST['TrainFreq'];
$SessionsCount =   $_POST['SessionText'];
$DateCommence = $_POST['dateFrom'];
$TrainDept =  $_POST['TrainingDept'];
$TrainMod =  $_POST['TrainingModule'];
$MandFoAl =  $_POST['MandForAll'];
$OccPerFreq =  $_POST['TrainFreqOcc'];
date_default_timezone_set('Asia/Kolkata');
$_SESSION['sesscnt'] = $SessionsCount;
$InsertTraining = mysqli_query($db,"insert into active_trainings(training_module_id,training_Department,frequency,current_occurence,total_occurence_per_frequency,
is_evaluation_required,created_Date_and_time,created_by,is_active,cycle_id)
values
('$TrainMod','$TrainDept','$TrainFreqency','1','$OccPerFreq','$isEvalRequired',now(),'$name','Y','1')");
$idRow = mysqli_insert_id($db);
$_SESSION['trainingId'] = $idRow;
if($TrainFreqency!='Once')
{
	if($OccPerFreq == '1')
	{ 
		if($TrainFreqency=='Weekly')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("7 days"));
		}
		if($TrainFreqency=='Monthly')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("30 days"));
		}
		if($TrainFreqency=='Quarterly')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("90 days"));
		}
		if($TrainFreqency=='Half Yearly')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("180 days"));
		}
		if($TrainFreqency=='Annually')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("180 days"));
		}
		if($TrainFreqency=='Daily')
		{
			$AppDate= date_add(date(),date_interval_create_from_date_string("1 days"));
		}
		
		
		$InsertFutTraining = mysqli_query($db,"insert into future_trainings (training_id,training_module_id,training_department,frequency,occurence_count,total_occurence_per_frequency,
	is_evaluation_required,created_Date_and_time,created_by,is_active,cycle_id,app_start_Date)
	values
	('$idRow','$TrainMod','$TrainDept','$TrainFreqency','1','$OccPerFreq','$isEvalRequired',now(),'$name','Y','2',$AppDate);");	
	}
	else
	{
		$InsertFutTraining = mysqli_query($db,"insert into future_trainings (training_id,training_module_id,training_department,frequency,occurence_count,total_occurence_per_frequency,
	is_evaluation_required,created_Date_and_time,created_by,is_active,cycle_id)
	values
	('$idRow','$TrainMod','$TrainDept','$TrainFreqency','2','$OccPerFreq','$isEvalRequired',now(),'$name','Y','1');");	
	}
}	

if($MandFoAl!='Yes')
{
	$SelbyRole = count($_POST["SelByRole"]);
	if($SelbyRole > 0)
	{
		for($m=0; $m<$SelbyRole; $m++)  
		{  	
			$Role[] = $_POST["SelByRole"][$m];	
		}
		$sRole     = "'" . implode ( "', '", $Role ) . "'";
		$empQue = mysqli_query($db,"select employee_id from resource_management_Table where designation in ($sRole) and employee_id not in (select employee_id from all_trainers) and is_active='Y'");
	}

	$SelbyDept = count($_POST["SelByDept"]);
	if($SelbyDept > 0)
	{
		for($n=0; $n<$SelbyDept; $n++)  
		{  	
			$Dept[] = $_POST["SelByDept"][$n];	
		}
		$sDept     = "'" . implode ( "', '", $Dept ) . "'";
		if($sRole!='')
		{
			$empQueDept = mysqli_query($db,"select employee_id from resource_management_Table where department in ($sDept) and designation not in ($sRole) and employee_id not in (select employee_id from all_trainers) and is_active='Y'");
		}
		else
		{
			$empQueDept = mysqli_query($db,"select employee_id from resource_management_Table where department in ($sDept) and employee_id not in (select employee_id from all_trainers) and is_active='Y'");	
		}
	}
	
	$roleCnt = mysqli_num_rows($empQue);
	if($roleCnt>0)
	{
		while($RoleEmp = mysqli_fetch_assoc($empQue))
		{
		$insertPart = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)
	
		values ('$idRow','".$RoleEmp['employee_id']."',now(),'$name','1','1')");
		}
	}
	$DeptCnt = mysqli_num_rows($empQueDept);
	if($DeptCnt>0)
	{
		while($DepEmp = mysqli_fetch_assoc($empQueDept))
		{
		$insertPartDp = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)
	
		values ('$idRow','".$DepEmp['employee_id']."',now(),'$name','1','1')");
		}
	}
	
$Trainees = count($_POST["TraineesSelVal"]);
if($Trainees > 0)
{
		for($p=0; $p<$Trainees; $p++)  
		{  	
			$checkdp = mysqli_query($db,"select * from training_participants where training_id='$idRow' and employee_id='".$_POST["TraineesSelVal"][$p]."'");
			if(mysqli_num_rows($checkdp)==0)
				
			{
				$sql3 = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)
	
				values ('$idRow','".$_POST["TraineesSelVal"][$p]."',now(),'$name','1','1')"); 
			}
		
			
		}
}
}
else
{
	$getEmployees = mysqli_query($db,"select employee_id from employee_details where employee_id not in (select employee_id from all_trainers) and is_active='Y'");
	$emplCount = mysqli_num_rows($getEmployees);
	if($emplCount > 0)
	{
		while($getEmployeeRow = mysqli_fetch_assoc($getEmployees))
		{

				$sql3 = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)
	
				values ('$idRow','".$getEmployeeRow['employee_id']."',now(),'$name','1','1')"); 
		}
		
		
	}
}
header("Location: AllocateSession.php");