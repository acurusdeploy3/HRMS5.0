<?php
//require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

$id = $_POST['EmpIdvalue'];
$allocated_to= $_POST['report_allocated_to'];
$Repid =$_POST['RepIdvalue'];
$splitval = explode( '-', $allocated_to ) ;
$allocatedval= $splitval[1];
if(!empty($id)) {
		$result = mysqli_query($db,"update report_master set Allocated_Employee = '$allocatedval' where Report_ID = '$Repid' and Is_Active='Y'");
	header("Location: ReportsAllocationScreen.php");
}
?>