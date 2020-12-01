<?php 

session_start();
$name = $_SESSION['login_user'];
include("config2.php");
$Emplid = $_POST['EmployeeIDAsset'];
$AssetID = $_POST['EmployeeAsset'];

$sql="insert into employee_assets (employee_id,asset_mgmt_id)
values
('$Emplid','$AssetID')";
$result=mysqli_query($db,$sql);
header("Location: CompleteFormalities.php?id=$Emplid");
?>