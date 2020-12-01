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
	$dval1 = $strval1[2];
	$empid = $strval1[1];
	$idval1 = $strval1[0];
     
	 $dsrstatus = mysqli_query($db,"select * from dsr_summary where employee_id='$empid' and date='$dval1'");
	 $statuscheck = mysqli_fetch_array($dsrstatus);
	 
	  $leavelist = mysqli_query($db,"select * from leave_request where (employee_id='$empid' and '$dval1'between leave_from and leave_to and is_active='N' and is_approved='Y' and  reason<>'WFH') or(employee_id='$empid' and '$dval1'between leave_from and leave_to and is_active='Y' and is_approved='' and  reason<>'WFH')");
	  $num = mysqli_num_rows($leavelist);
	  $list = mysqli_fetch_array($leavelist);	

$checkin = mysqli_query($db,"select * from attendance where employee_id='$empid' and shift_date='$dval1'");
$checkintime = mysqli_fetch_array($checkin);
$time = $checkintime['Check_In'];	
	  
	        if($num < 1 && $statuscheck['status']=='NA' ){
						$status="WFH";
					}
                 
					elseif($num<1){
						$status=$statuscheck['status'];
					}
	              elseif($num > 0){
					
					if($list['is_approved']=='Y' && $list['number_of_days']>='1'){
					if($list['leave_type']=='Sick'){
					$status="SL";
					}
					elseif($list['leave_type']=='Privilege'){
					$status= "Pl";
					}
					elseif($list['leave_type']=='Casual'){
					$status="CL";
			       }
			       elseif($list['leave_type']=='Privilege & Sick'){
					$status ="PL&SL";
					}
					elseif($list['leave_type']=='Casual & Sick'){
					$status = "CL&SL";
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					$status = "C-OFF";
					}
					elseif($list['leave_type']=='On-Duty'){
					$status = "OD";
					}
                    elseif($list['leave_type']=='Maternity'){
					$status = "ML";
					}
			 }
			 elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)' ){
						
						if($list['leave_type']=='Sick'){
					$status = "SL[SH]/".$statuscheck['status']."";
					}
					elseif($list['leave_type']=='Casual'){
					$status = "CL[SH]/".$statuscheck['status']."";
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					$status = "C-OFF[SH]/".$statuscheck['status']."";
					}
					}
			elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)' ){
						
						if($list['leave_type']=='Sick'){
					$status = "SL[SH]/".$statuscheck['status']."";
					}
					elseif($list['leave_type']=='Casual'){
					$status = "CL[SH]/".$statuscheck['status']."";
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					$status = "C-OFF[SH]/".$statuscheck['status']."";
					}
					}
			 
			 }
			 else{
			 }
					
	 
    $pmsid = mysqli_query($db,"SELECT manager_id FROM `pms_manager_lookup`where manager_id=$empid group by manager_id;");
    $getid = mysqli_fetch_assoc($pmsid);
    $mngid = $getid['manager_id'];	

	$list = mysqli_query($db,"select e.reporting_manager_id as tl_id,p.manager_id from employee_details e left join pms_manager_lookup p on e.employee_id=p.employee_id where e.employee_id=$empid and e.is_active='Y'");
$getlist=mysqli_fetch_assoc($list);

$tlid=$getlist['tl_id'];
$managerid = $getlist['manager_id'];

    if($empid==$mngid)
	{
	 $res= "Update dsr_summary  set is_submitted='Y',is_sent='Y',is_approved='Y',status='$status',modified_date_time=now(),modified_by='$name' where dsr_summary_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);
	}
	elseif($name==$empid){
		$res= "Update dsr_summary set is_submitted='Y',is_sent='Y',status='$status',modified_date_time=now(),modified_by='$name' where dsr_summary_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);
	}
    elseif($name ==$managerid){
	$res="Update dsr_summary  set is_submitted='Y',is_sent='Y',is_approved='Y',status='$status',modified_date_time=now(),modified_by='$name' where dsr_summary_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);

	}
		
	elseif($name==$tlid){
		$res= "Update dsr_summary set is_submitted='Y',is_sent='Y',status='$status',modified_date_time='".$date."',modified_by='$name' where dsr_summary_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);

	}
	else{
	}
	
}
exit();
?>