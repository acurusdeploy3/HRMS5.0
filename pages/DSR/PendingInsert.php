<?php
//require_once("queries.php");
session_start();
include('config.php');

$name = $_SESSION['login_user'];
$date = date("Y-m-d h:i:s");


$selectReIDS = $_POST['selectvalues'];
foreach($selectReIDS as $reqID)
{
	$strval1 = explode("|",$reqID);
	$idval1 = $strval1[0];
	$dval1 = $strval1[1];
	$post='status'.$idval1;
	$status = $_POST[$post];

$list = mysqli_query($db,"select e.reporting_manager_id as tl_id,p.manager_id from employee_details e left join pms_manager_lookup p on e.employee_id=p.employee_id where e.employee_id='$idval1' and e.is_active='Y'");
$getlist=mysqli_fetch_assoc($list);

$tlid=$getlist['tl_id'];
$managerid = $getlist['manager_id'];
	
	if($name==$managerid){
    $res="update dsr_summary set  is_submitted='Y',is_sent='Y',is_approved='Y',status='$status',modified_by='$name',modified_date_time=now() where employee_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);
	header('Location: PendingDSR.php');
    }
else{
	$res="update dsr_summary set  is_submitted='Y',is_sent='Y',status='$status',modified_by='$name',modified_date_time=now() where employee_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);
	header('Location: PendingDSR.php');
}
}

?>