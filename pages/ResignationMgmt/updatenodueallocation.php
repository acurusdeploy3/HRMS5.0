<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

$temp = $_POST['ResIdvalue1'];
$id = $_POST['EmpIdvalue1'];
$allocated_to= $_POST['no_due_acc_allocated_to'];
$scnname = $_POST['ScnIdvalue1'];
$temp1 = $_POST['ResIdvalue2'];
$id1 = $_POST['EmpIdvalue2'];
$allocated_to1= $_POST['no_due_adm_allocated_to'];
$scnname1 = $_POST['ScnIdvalue2'];
	if($scnname1 == 'Admin')
	{
		$result = mysqli_query($db,"UPDATE employee_resignation_information set no_due_sysadmin_allocated_to = '$allocated_to1',modified_by=$name WHERE employee_id=$id1 and resignation_id=$temp1");
		$result1 = mysqli_query($db,"Update employee_resignation_information set pending_queue_id=REPLACE(pending_queue_id, '$name-adm',concat(SUBSTRING_INDEX(no_due_sysadmin_allocated_to,'-',-1),'-adm'))");
		header("Location: sendallocationemail.php?rid=$temp1&scnname=$scnname1");
	}
	else if($scnname == 'Accounts')
	{
		echo '<script language="javascript">';
		echo 'alert("message successfully sent")';
		echo '</script>';
		$result = mysqli_query($db,"UPDATE employee_resignation_information set no_due_acc_allocated_to = '$allocated_to',modified_by=$name WHERE employee_id=$id and resignation_id=$temp");
		$result1 = mysqli_query($db,"Update employee_resignation_information set pending_queue_id=REPLACE(pending_queue_id, '$name-acc',concat(SUBSTRING_INDEX(no_due_acc_allocated_to,'-',-1),'-acc'))");
		header("Location: sendallocationemail.php?rid=$temp&scnname=$scnname");
	}
	else{}
	
	//header("Location: sendstatusemail.php?id=$id");
?>