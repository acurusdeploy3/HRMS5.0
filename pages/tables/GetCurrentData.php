<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EmployeeID = $_POST['EmployeeID'];
$getData = mysql_query("select a.department,a.reporting_manager_id,concat(b.First_Name,' ',b.last_name,' ',b.mi) as Manager from employee_details a inner join employee_details b
on a.reporting_manager_id=b.employee_id
where a.employee_id=$EmployeeID");
$getDataRow = mysql_fetch_array($getData);
$data['result'] = $getDataRow;
echo json_encode($data);
?>