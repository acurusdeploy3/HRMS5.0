<?php
session_start();
include('config.php');
include('config2.php');
include('ModificationFunc.php');
$rId = $_POST['ResourceID'];
//$empId = $_SESSION['EmpId'];
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$tabname = 'resource_management_table';
$primKey = 'res_management_id';
$Depatment = $_POST['DeptSelect'];
if($_POST['BandSelect']=='NIL')
{
	$Band='';
}
else
{
$Band = $_POST['BandSelect'];
}
if($_POST['LevelSel']=='NIL')
{
	$Lev='';
}
else
{
$Lev =   $_POST['LevelSel'];
}
$effectfrom = $_POST['dateFrom'];
$Mgmr = $_POST['RepMgmr'];
$Role = $_POST['RoleSelect'];
//StoreDatainHistory($rId, $tabname,$primKey);


include("config2.php");
$InsertNew = mysqli_query($db,"update resource_management_table set department='$Depatment',designation='$Role'
,reporting_manager='$Mgmr',band='$Band',level='$Lev',effective_from='$effectfrom' where res_management_id='$rId'");		
 