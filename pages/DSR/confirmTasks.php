<?php
//require_once("queries.php");
session_start();
include('config.php');

$name = $_SESSION['login_user'];
$date = date("Y-m-d h:i:s");

$cnt = mysqli_real_escape_string($db,$_POST['count']);
$edate = $_POST['edate'];
$employeeid = $_POST['employeeid'];

$list = mysqli_query($db,"select e.reporting_manager_id as tl_id,p.manager_id from employee_details e left join pms_manager_lookup p on e.employee_id=p.employee_id where e.employee_id=$employeeid and e.is_active='Y'");
$getlist=mysqli_fetch_assoc($list);

$tlid=$getlist['tl_id'];
$managerid = $getlist['manager_id'];

$getalltasks = mysqli_query($db,"select * from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where employee_id='$employeeid' and date='$edate' and is_active='Y' and is_submitted='Y'");


if(mysqli_num_rows($getalltasks) < 1)
{}
else{
	$i = 1;
	while($row = mysqli_fetch_assoc($getalltasks)){
       if($name==$managerid)
	   {
		$res= "Update dsr_comments set manager_comments='".$_POST['mngrcomm_'.$row['dsr_id']]."' where dsr_id='".$row['dsr_id']."'and summary_id='".$row['dsr_summary_id']."'";
		$result = mysqli_query($db,$res);
		echo $res;
		header('Location: MyDSRReports.php');
	}
	
	elseif($name==$tlid)
	{
		$res= "Update dsr_comments set tl_comments='".$_POST['tlcomm_'.$row['dsr_id']]."' where dsr_id='".$row['dsr_id']."'and summary_id='".$row['dsr_summary_id']."'";
		$result = mysqli_query($db,$res);
		echo $res;
		header('Location: MyDSRReports.php');
	}
	
	else{
	}
	}	
	}
		
		
//$res="Update dsr_summary set is_sent='Y',modified_date_and_time='".$date."',modified_by='$name' where employee_id='$saveid' and date='$dateval' and is_active='Y'";
//$result = mysqli_query($db,$res);

?>