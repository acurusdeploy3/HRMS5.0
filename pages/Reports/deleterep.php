<?php
require_once("config.php");
require_once("queries.php");
$userid=$_SESSION['login_user'];
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");

$temp = $_GET["Allocation_ID"];
$empid= $_GET["empld_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE report_allocation_table set is_active = 'N' , modified_date_and_time='".$date."',modified_by='$userid' WHERE Allocation_ID=".$_GET["Allocation_ID"]);
	storeDataInHistory($temp , "report_allocation_table","Allocation_ID");
	if(!empty($result)){
		header("Location:ReportsAllocationScreenHR.php?employee_id=$empid");
	}
}
?>

