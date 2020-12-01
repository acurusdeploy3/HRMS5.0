<?php
session_start();
include('config.php');
$empId = $_SESSION['EmpId'];
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$tabname = 'resource_management_table';
$primKey = 'res_management_id';
$getMngrName = mysql_query("select first_name as name from employee_details where employee_id='$empId'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];

	$query = mysql_query ("insert into employee_notification (employee_to_be_notified,module,date_to_be_notified,created_date_and_time,created_by,notification_message,action_against_employee,link_href,notification_type)
values

('$name','Resource Management',DATE_ADD(CURDATE(), INTERVAL 1 DAY),now(),'$name','Upload LOA for ".$repMngrName." : ".$empId."','".$empId."','ResourceMgmtCount.php','Remainder')");


header("Location: ViewResource.php?id=$empId");
			  
			  
			
 
 
 