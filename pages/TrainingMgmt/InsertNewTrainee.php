<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
$SelbyRole = count($_POST["SelByRole"]);
$idRow = $_SESSION['trainingId'];
$CycleID = $_POST['cycleVal'];
$ExisTrainees  = mysqli_query($db,"select employee_id from training_participants where training_id='$idRow' and Is_active='Y'");
$ExixProRow = mysqli_fetch_array($ExisTrainees);
$TIDs = $ExixProRow['employee_id'];
	if($SelbyRole > 0)
	{
		for($m=0; $m<$SelbyRole; $m++)
		{
			$Role[] = $_POST["SelByRole"][$m];
		}
		$sRole     = "'" . implode ( "', '", $Role ) . "'";
		$empQue = mysqli_query($db,"select employee_id from resource_management_Table where designation in ($sRole) and employee_id not in (select employee_id from training_participants where training_id='$idRow' and Is_active='Y') and employee_id not in (select trainer from training_sessions where training_id='$idRow' and is_active='Y') and is_active='Y'");
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
			$empQueDept = mysqli_query($db,"select employee_id from resource_management_Table where department in ($sDept) and employee_id not in (select employee_id from training_participants where training_id='$idRow' and Is_active='Y')  and employee_id not in (select trainer from training_sessions where training_id='$idRow' and is_active='Y') and designation not in ($sRole) and is_active='Y'");
		}
		else
		{
			$empQueDept = mysqli_query($db,"select employee_id from resource_management_Table where department in ($sDept) and employee_id not in (select employee_id from training_participants where training_id='$idRow' and Is_active='Y') and employee_id not in (select trainer from training_sessions where training_id='$idRow' and is_active='Y') and is_active='Y'");
		}
	}

	$roleCnt = mysqli_num_rows($empQue);
	if($roleCnt>0)
	{
		while($RoleEmp = mysqli_fetch_assoc($empQue))
		{
		$insertPart = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)

		values ('$idRow','".$RoleEmp['employee_id']."',now(),'$name','$CycleID','1')");

		$getSessions = mysqli_query($db,"select session_id,trainer from training_Sessions where training_id='$idRow' and is_active='Y' and cycle_id='$CycleID'");
		while($getSessionRow = mysqli_fetch_assoc($getSessions))
		{
			$InsertAttendance = mysqli_query($db,"insert into training_attendance (training_id,session_id,employee_id,trainer_id,created_date_and_time,created_by,cycle_id)
			values ('$idRow','".$getSessionRow['session_id']."','".$RoleEmp['employee_id']."','".$getSessionRow['trainer']."',now(),'$name','$CycleID')");
		}


		}

	}
	$DeptCnt = mysqli_num_rows($empQueDept);
	if($DeptCnt>0)
	{
		while($DepEmp = mysqli_fetch_assoc($empQueDept))
		{
		$insertPartDp = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)

		values ('$idRow','".$DepEmp['employee_id']."',now(),'$name','$CycleID','1')");

		$getSessions = mysqli_query($db,"select session_id,trainer from training_Sessions where training_id='$idRow' and is_active='Y' and cycle_id='$CycleID'");
		while($getSessionRow = mysqli_fetch_assoc($getSessions))
		{
			$InsertAttendance = mysqli_query($db,"insert into training_attendance (training_id,session_id,employee_id,trainer_id,created_date_and_time,created_by,cycle_id)
			values ('$idRow','".$getSessionRow['session_id']."','".$DepEmp['employee_id']."','".$getSessionRow['trainer']."',now(),'$name','$CycleID')");
		}


		}
	}

$Trainees = count($_POST["TraineesSelVal"]);
if($Trainees > 0)
{
		for($p=0; $p<$Trainees; $p++)
		{
			$checkdp = mysqli_query($db,"select * from training_participants where training_id='$idRow' and employee_id='".$_POST["TraineesSelVal"][$p]."' and is_active='Y'");
			if(mysqli_num_rows($checkdp)==0)

			{
			$sql3 = mysqli_query($db,"Insert into training_participants (training_id,employee_id,created_Date_and_time,created_by,cycle_id,occurence_count)

			values ('$idRow','".$_POST["TraineesSelVal"][$p]."',now(),'$name','$CycleID','1')");
			$getSessions = mysqli_query($db,"select session_id,trainer from training_Sessions where training_id='$idRow' and is_Active='Y' and cycle_id='$CycleID'");
			while($getSessionRow = mysqli_fetch_assoc($getSessions))
			{
			$InsertAttendance = mysqli_query($db,"insert into training_attendance (training_id,session_id,employee_id,trainer_id,created_date_and_time,created_by,cycle_id)
			values ('$idRow','".$getSessionRow['session_id']."','".$_POST["TraineesSelVal"][$p]."','".$getSessionRow['trainer']."',now(),'$name','$CycleID')");
			}
			}

			

		}
}

?>
