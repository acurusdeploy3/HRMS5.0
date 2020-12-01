<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d h:i:s");
$temp = $_POST['ResIdvalue'];
$id = $_POST['EmpIdvalue'];
$status= $_POST['status'];
$query1 = mysqli_query($db,"select job_role,department from employee_details where employee_id=$id");
$detrow = mysqli_fetch_assoc($query1);
$role = $detrow['job_role'];
$department = $detrow['department'];
$query2 = mysqli_query($db,"Select * from all_hods where employee_id =$id and department='$department'");

$query3 = mysqli_query($db,"select employee_id from employee_details where job_role='HR Manager'");
$detrow1 = mysqli_fetch_assoc($query3);
$hrmid = $detrow1['employee_id'];



$comments=mysqli_real_escape_string ($db,$_POST['reporting_manager_comments']);
if(!empty($id)) {
	if ($_POST['status']=='Process Resignation'){
		if($role == 'HR Manager' && mysqli_num_rows($query2)>0)
		{
        	$chckrslt = mysqli_query($db,"Update employee_resignation_information set process_queue='HR_Manager_Approved',reporting_manager_comments='$comments',is_active='Y',modified_by=$name,modified_date_and_time=now(),allocated_to=(select concat(First_Name,'',MI,'',Last_Name,'-',employee_id) from employee_details where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='HR_ID' and config_type='PROCESSING')),process_queue='HR_Manager_Approved',modified_by=$userid,status = '$status',exit_interview_status='N',no_due_sysadmin_allocated_to=(SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='ADMIN_ID')), no_due_acc_allocated_to=(SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='ACC_ID')) where resignation_id=$temp and is_active='Y'");
        	$query81 ="Update employee_resignation_information set pending_queue_id= concat(SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1),'-acc,',SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1),'-adm,',employee_id,'-emp,',reporting_manager_id,'-rep,',concat(SUBSTRING_INDEX($userid,'-',-1),'-hrm'),concat(SUBSTRING_INDEX(allocated_to,'-',-1),'-hr'))where resignation_id=$temp and is_active='Y'";
			$result81=mysqli_query($db,$query81);

			$query82 ="Update employee_resignation_information r set no_due_sysadmin_allocated_to = (SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` d where d.employee_id=r.reporting_manager_id and d.is_active='Y') where r.employee_id=SUBSTRING_INDEX(no_due_sysadmin_allocated_to,'-',-1) and r.resignation_id=$temp and r.is_active='Y'";
			$result82=mysqli_query($db,$query82);

			$query89 = "Update employee_resignation_information set pending_queue_id= concat(SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1),'-acc,',SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1),'-adm,',employee_id,'-emp,',reporting_manager_id,'-rep,',if(SUBSTRING_INDEX(allocated_to,'-',-1)=$userid,(SUBSTRING_INDEX(allocated_to,'-',-1),'-hrm'),(SUBSTRING_INDEX(allocated_to,'-',-1),'-hr'))  where resignation_id=$temp and is_active='Y'";
			$result89=mysqli_query($db,$query89);

			$query3="select * from nodueformentries where resignation_id=$temp";
			$result3=mysqli_query($db,$query3);
			if(mysqli_num_rows($result3) < 1){
				$query13="select employee_id from employee_resignation_information where resignation_id=$temp";
				$result13=mysqli_query($db,$query13);
				$valuerow13 = mysqli_fetch_array($result13);
				$epid8=$valuerow13['employee_id'];
				$dateleaving = $valuerow13['date_of_leaving'];

				$query31 = mysqli_query($db,"Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
				select resignation_id,(select department from employee_details where employee_id =r.employee_id),'Knowledge Transfer :','No','KnowledgeTransfer:',date(now()),'Acurus'
				FROM employee_resignation_information r where date_of_leaving = date(now()) and is_active='Y'");

				$query32 = mysqli_query($db,"Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
				SELECT resignation_id,Description,value,'No',replace(substring_index(value,'.',-1),' ',''),date(now()),'Acurus' FROM all_fields a,employee_resignation_information r
				where field_name='Details_1' and date_of_leaving = date(now()) and is_active='Y' and category='No Due Form' order by sort_order");

				$query33 = mysqli_query($db,"Insert into exitinterviewformenteries (resignation_id,value,comments,status,value_data,created_date_and_time,created_by)
				SELECT  resignation_id,value,'' as comments,'N' as status,concat(description,sort_order),date(now()),'Acurus'
				FROM `all_fields` a ,employee_resignation_information r where category='Exit Interview Form' and date_of_leaving = date(now()) and is_active='Y'");

				$query34 = mysqli_query($db,"update `employee_resignation_information` set exit_interview_status='N' , process_queue='HR_Manager_Process'
				where date_of_leaving = date(now()) and is_active='Y' and process_queue='HR_Manager_Approved' and employee_id=$epid8");
            }
			header("Location: sendstatusemail.php?rid=$temp");
		}
    	else if ($role == 'HR Manager' && mysqli_num_rows($query2)==0) {
        	$result = mysqli_query($db,"UPDATE employee_resignation_information r set status = '$status',reporting_manager_comments='$comments',is_active='Y',modified_by=$name,process_queue='Manager_Process',modified_date_and_time=now(),pending_queue_id=(SELECT concat(employee_id,'-hod') FROM `all_hods` where department=(select department from employee_details where employee_id=$id)and location=(select business_unit from employee_details where employee_id=$id)) WHERE employee_id=$id and resignation_id=$temp");
			header("Location: sendstatusemail.php?rid=$temp");
        }
    	else if (mysqli_num_rows($query2)>0){
        	
			$result = mysqli_query($db,"UPDATE employee_resignation_information r set status = '$status',reporting_manager_comments='$comments',is_active='Y',modified_by=$name,process_queue='HOD_Process',modified_date_and_time=now(),pending_queue_id=concat($hrmid,'-hrm') WHERE employee_id=$id and resignation_id=$temp");
			header("Location: sendstatusemail.php?rid=$temp");
        }
	else{
	$result = mysqli_query($db,"UPDATE employee_resignation_information set status = '$status',reporting_manager_comments='$comments',is_active='Y',modified_by=$name,process_queue='Manager_Process',modified_date_and_time=now(),pending_queue_id=(SELECT concat(employee_id,'-hod') FROM `all_hods` where department=(select department from employee_details where employee_id=$id)and location=(select business_unit from employee_details where employee_id=$id))WHERE employee_id=$id and resignation_id=$temp");
	header("Location: sendstatusemail.php?rid=$temp");
	//header("Location: sendstatusemail.php?id=$id");
	}
	}
	else if ($_POST['status']=='Hold Resignation'){
		$result = mysqli_query($db,"UPDATE employee_resignation_information set status = '$status',reporting_manager_comments='$comments',is_active='Y',modified_by=$name,process_queue='Manager_Hold',modified_date_and_time=now() WHERE employee_id=$id and resignation_id=$temp");
	//header("Location: sendstatusemail.php?id=$id");

	header("Location: sendstatusemail.php?rid=$temp");
	}
	else if ($_POST['status']=='Request for Cancellation of Resignation')
	{  
		$result = mysqli_query($db,"UPDATE employee_resignation_information set status = '$status',reporting_manager_comments='$comments' ,process_queue='Manager_Cancel',modified_by=$name,modified_date_and_time=now(),pending_queue_id=concat($id,'-emp') WHERE employee_id=$id and resignation_id=$temp");
		header("Location: sendcancellationreqemail.php?rid=$temp");
	}
}
?>