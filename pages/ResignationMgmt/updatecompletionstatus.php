<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

$resignation_id = $_GET['resignation_id'];

$empdet=mysqli_query($db,"select * from employee_resignation_information WHERE resignation_id=$resignation_id");
$detais = mysqli_fetch_array($empdet);
$emplid=$detais['employee_id'];


if(!empty($resignation_id)) {
	$result = mysqli_query($db,"UPDATE employee_resignation_information set status = 'Process_Completed',is_active='N',exit_interview_status='C',process_queue='HR_Completed',modified_by=$name WHERE resignation_id=$resignation_id");
	
	$result3=mysqli_query($db,"insert into deactivated_employees select 0,now(),'Resigned',(select REASON_FOR_RESIGNATION from employee_resignation_information where resignation_id=$resignation_id),e.* from employee_details e where employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");

	$result1 = mysqli_query($db,"UPDATE employee_details set is_active='N',date_left='".$detais['Date_Of_Leaving']."',modified_by='$name' WHERE employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");

	$result2 = mysqli_query($db,"UPDATE employee set LDW='".$detais['Date_Of_Leaving']."' WHERE employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");

	$result4=mysqli_query($db,"insert into history_resource_management_table select 0,now(),e.* from resource_management_table e where employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");
	
	$result5 = mysqli_query($db,"UPDATE resource_management_table set is_active='N' WHERE employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");
	
	$result6 = mysqli_query($db,"Delete from employee_performance_Review_Dates  WHERE employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id)");
	
	$result7 = mysqli_query($db,"Delete from leave_request  WHERE employee_id in (select employee_id from employee_resignation_information where resignation_id=$resignation_id) and is_active='Y'");
	
	$AD_server = "ldap://172.18.0.150"; 
		$ds = ldap_connect($AD_server);
		if ($ds) {
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
					$result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
					if (!$result)
						{
							echo 'Not Binded';
						}
						$EmpID=$emplid;
						$userDn = "cn=".$EmpID.",ou=Users,dc=acurusldap,dc=com";
						ldap_Delete($ds,$userDn);
   		 ldap_close($ds);
			}

	
	header("Location: sendcompletionemail.php?resignation_id=$resignation_id");
	//header("Location: sendstatusemail.php?id=$id");
}
?>