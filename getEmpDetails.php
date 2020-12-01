<?php
include("config.php");

$empID = $_POST['empId'];
	$sql = "select a.employee_id,employee_name,department,event_type,contact_email from employee a left join birthdays_anniversaries b on a.employee_id=b.employee_id left join notification_contact_email c on a.employee_id=c.employee_id  where b.id='$empID'";
  	$results = mysqli_query($db, $sql);
	$ResultRow = mysqli_fetch_array($results);
	$Name = $ResultRow['employee_name'];
	$data['result']=$ResultRow;
	echo json_encode($data);
  	exit();
?>