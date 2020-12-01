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
$dateleft= $_POST['dateleft'];
$ScnIDValue= $_POST['ScnIDValue'];
$query1 = mysqli_query($db,"select * from employee_resignation_information where resignation_id=$temp");
$detrow = mysqli_fetch_assoc($query1);
$hrexistcomments = $detrow['hr_comments'];
$hr_comments=mysqli_real_escape_string ($db,$_POST['hr_comments']);
if(!empty($id)) {
		$result = mysqli_query($db,"UPDATE employee_resignation_information set hr_comments=
		concat('$hrexistcomments','|','$name','--','$hr_comments'),actual_date_of_leaving='$dateleft',Date_Of_Leaving='$dateleft',modified_by=$name,modified_date_and_time=now() WHERE employee_id=$id and resignation_id=$temp");
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

		if($ScnIDValue == 'hr')
		{
			header("Location: senddateofleavingupdtmail.php?rid=$temp");
		}
		else
		{
			header("Location: senddateofleavingupdtmail.php?rid=$temp");
		}
}
?>