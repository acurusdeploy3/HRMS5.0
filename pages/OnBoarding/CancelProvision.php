<?php 

session_start();
$name = $_SESSION['login_user'];
include("config2.php");
$assetID = $_GET['id'];
$EmpId = $_GET['EmpId'];

$sql="update employee_assets set is_Active='N' where asset_mgmt_id='$assetID'";
$result= mysqli_query($db,$sql);
header("Location: CompleteFormalities.php?id=$EmpId");
?>