<?php
//require_once("queries.php");
session_start();
include('config.php');

$name = $_SESSION['login_user'];
$date = date("Y-m-d h:i:s");

$cnt = mysqli_real_escape_string($db,$_POST['count']);
$edate = $_POST['edate'];
$employeeid = $_POST['employeeid'];

$getalltasks = mysqli_query($db,"select * from dsr_summary where employee_id='$employeeid' and date='$edate' and is_active='Y' and is_sent='Y'");

if(mysqli_num_rows($getalltasks) < 1)
{}
else{
	$i = 1;
	while($row = mysqli_fetch_assoc($getalltasks)){
		$res= "Update dsr_summary set is_approved='Y',manager_comments='".$_POST['mngrcomm_'.$row['dsr_summary_id']]."',modified_date_and_time='".$date."',modified_by='$name' where employee_id='$employeeid' and date='$edate' and dsr_summary_id='".$row['dsr_summary_id']."'";
		$result = mysqli_query($db,$res);
		echo $res;
		header('Location: MyTeamDSRReports.php');
		}
	} 
//$res="Update dsr_summary set is_sent='Y',modified_date_and_time='".$date."',modified_by='$name' where employee_id='$saveid' and date='$dateval' and is_active='Y'";
//$result = mysqli_query($db,$res);

?>