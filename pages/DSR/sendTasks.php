<?php
//require_once("queries.php");
session_start();
include('config.php');
$name = $_SESSION['login_user'];

$saveid = $_POST["saveid"];
$dateval = $_POST['dateval'];

$date = date("Y-m-d h:i:s");

$dirmanager = mysqli_query($db,"select tl_id,manager_id from dsr_summary where tl_id=manager_id and date='$dateval' and employee_id='$saveid' ");
$getsameid = mysqli_fetch_array($dirmanager);
$tlid= $getsameid['tl_id'];
$mngid = $getsameid['manager_id'];

$directemployee = mysqli_query($db,"SELECT p.employee_id FROM `pms_manager_lookup` p left join employee_details d on p.employee_id=d.employee_id where (d.reporting_manager_id='2' and manager_id='2' and is_active='Y' and p.employee_id='$saveid' and p.employee_id not in(select reporting_manager_id from employee_details where is_active='Y'))or(d.reporting_manager_id='1' and manager_id='1' and p.employee_id='$saveid' and is_active='Y' and p.employee_id not in(select reporting_manager_id from employee_details where is_active='Y'))  ");				
	$id = mysqli_fetch_array($directemployee);
	$dirid = $id['employee_id'];



	if($dirid==$saveid){
	$res="Update dsr_summary set is_submitted='Y',is_sent='Y',is_approved='Y',status='WFH',modified_by='$name',modified_date_time=now()  where employee_id='$saveid' and date='$dateval' and is_active='Y'";
    $result = mysqli_query($db,$res);
}
	elseif($tlid!='' && $mngid!=''){
		$res="Update dsr_summary set is_submitted='Y',is_sent='Y',status='WFH',modified_by='$name',modified_date_time=now()  where employee_id='$saveid' and date='$dateval' and is_active='Y'";
        $result = mysqli_query($db,$res);
	}
	 
    else{

    $res="Update dsr_summary set is_submitted='Y',status='WFH',modified_by='$name',modified_date_time=now()  where employee_id='$saveid' and date='$dateval' and is_active='Y'";
    $result = mysqli_query($db,$res);
	}

?>