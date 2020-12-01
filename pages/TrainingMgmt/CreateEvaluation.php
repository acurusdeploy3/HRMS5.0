<?php 
if(isset($_REQUEST))
{
session_start();
$name = $_SESSION['login_user'];
include("config2.php");
$train=$_SESSION['trainingId'];
$AssDate = $_POST['AssDate'];
$AssOn = $_POST['AssessmentOn'];
$MaxScoreVal = $_POST['MaxScore'];
$CycleID = $_POST['cycleVal'];
$EmpIdCount=count($_POST['EmployeeID']);

error_reporting(E_ALL && ~E_NOTICE);
$GetCount = mysqli_query($db,"SELECT * FROM `training_evaluation` where training_id='$train' and is_active='Y' group by evaluation_count");
$cntRows = mysqli_num_rows($GetCount);
$CycleCnt = $cntRows+1;

$i=0;
while($i< $EmpIdCount)
{
	if($_POST['StatusSelect'][$i]=='Did Not Attend')
	{
	$insertEval = mysqli_query($db,"insert into training_evaluation(training_id,employee_id,date_of_assessment,marks_obtained,max_score,status,created_date_and_time,created_by,is_active,evaluation_count,evaluation_topic,cycle_id)
	values
	('$train','".$_POST['EmployeeID'][$i]."','$AssDate','Did Not Attend','$MaxScoreVal','Did Not Attend',now(),'$name','Y','$CycleCnt','$AssOn','$CycleID')");
	}
	else
		{
	$insertEval = mysqli_query($db,"insert into training_evaluation(training_id,employee_id,date_of_assessment,marks_obtained,max_score,status,created_date_and_time,created_by,is_active,evaluation_count,evaluation_topic,cycle_id)
	values
	('$train','".$_POST['EmployeeID'][$i]."','$AssDate','".$_POST['MarksObtained'][$i]."','$MaxScoreVal','".$_POST['StatusSelect'][$i]."',now(),'$name','Y','$CycleCnt','$AssOn','$CycleID')");		
		}
	$i++;
	
}

header("Location: ViewEvaluation.php?id=$train");
}
?>