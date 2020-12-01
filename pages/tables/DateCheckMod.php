<?php
include("config2.php");
session_start();
$name = $_SESSION['login_user'];
$email = $_POST['DateFromEdit'];
$email1 = $_POST['DateToEdit'];
$Emplid = $_SESSION['EmpId'];
$projID = $_POST['rowId'];
$AllocatedPer = $_POST['ModifiedAllocatedPer'];
$ChckInBw = mysqli_query($db,"select * from employee_projects where employee_id='$Emplid'  and is_active='Y' and '$email' >= allocated_from and '$email1' <= allocated_to and id!='$projID'");
	 $CheckStartProj = mysqli_query($db,"select sum(allocateD_percentage) as allocated from employee_projects where employee_id='$Emplid' and is_active='Y' and '$email' between allocated_from and allocated_to and id!='$projID'");
	  $CheckStartProjRow = mysqli_fetch_array($CheckStartProj);
	  $CheckStartProjPercentage = $CheckStartProjRow['allocated'];
	   $CheckEndProj = mysqli_query($db,"select sum(allocateD_percentage) as allocated from employee_projects where employee_id='$Emplid' and is_active='Y' and '$email1' between allocated_from and allocated_to and id!='$projID'");
	  $CheckEndProjRow = mysqli_fetch_array($CheckEndProj);
	  $CheckEndProjPercentage = $CheckEndProjRow['allocated'];
	  $AvailStart = 100-$CheckStartProjPercentage;
	  $AvailEnd = 100-$CheckEndProjPercentage;
	if($AllocatedPer > $AvailStart || $AllocatedPer > $AvailEnd)
	{
		$Ret =  'neg';
	}
	else
	{
		$Ret =  'pos';
	}	
echo $Ret;
  	exit();
?>