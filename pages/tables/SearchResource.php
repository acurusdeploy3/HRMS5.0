<?php
include("config.php");
session_start();
$desn = $_POST['desgSelect'];
$emp = $_POST['empSelect'];
$proj = $_POST['proSelect'];
$dept = $_POST['deptSelect'];
$_SESSION['emp_id']=$emp;
$_SESSION['project']=$proj;
$_SESSION['desgn']= $desn;
$_SESSION['dept']= $dept;
//header("Location: ResourceMgmt.php");
?>