<?php 
if(isset($_REQUEST))
{
	session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
 include("config2.php");
 $Emplid = $_SESSION['EmpId'];
error_reporting(E_ALL && ~E_NOTICE);
$ProjectID=$_POST['ProjectIDText'];
$ProjectName=$_POST['ProjectNameText'];
$ProjectMgmr=$_POST['PMSelect'];

mysqli_query($db,"Insert into all_projects (Project_Name,Project_Id,project_manager) values ('$ProjectName','$ProjectID','$ProjectMgmr')");

 include("config1.php");
mysqli_query($db4,"Insert into all_projects (Project_Name,Project_Id,project_manager) values ('$ProjectName','$ProjectID','$ProjectMgmr')");
}
?>