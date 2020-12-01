<?php
include("config.php");
include("config2.php");

$MRFID = $_POST['MRFID'];
  	$sql = "select hiring_location,type_of_employment from mrf_table where concat('MR',mrf_id)='$MRFID'";
  	$results = mysqli_query($db1, $sql);
	$locationRow = mysqli_fetch_array($results);
	$Location = $locationRow['hiring_location'];
	$type_of_employment = $locationRow['type_of_employment'];
	if($type_of_employment=='Full Time ')
	{
		$jobType = 'Permanent';
		
	}
	else
	{
		$jobType = 'Temporary';
	}
		$getHighestEmpIdT = mysqli_query($db,"select (employee_id+1) as suggested,business_unit,job_type from employee_details where employee_id not like '%30%' and job_type='$jobType' order by created_date_and_time desc limit 1");
		if(mysqli_num_rows($getHighestEmpIdT)>0)
		{
			$getHighestEmpIdRow = mysqli_fetch_array($getHighestEmpIdT);
			$HighestEmpId = $getHighestEmpIdRow['employee_id'];
			$SuggestedEmpId = $HighestEmpId+1;
			//echo $SuggestedEmpId;
		}
	else
	{
		$getHighestEmpId = mysqli_query($db,"select (employee_id+1) as suggested,business_unit,job_type from employee_details where employee_id not like '%30%' order by created_date_and_time desc limit 1");
		if(mysqli_num_rows($getHighestEmpId)>0)
			{
			$getHighestEmpIdRow = mysqli_fetch_array($getHighestEmpId);
			$HighestEmpId = $getHighestEmpIdRow['employee_id'];
			$SuggestedEmpId = $HighestEmpId+1;
			//echo $SuggestedEmpId;
			}
	}
$data['result'] = $getHighestEmpIdRow;	

echo json_encode($data);
  	exit();
?>