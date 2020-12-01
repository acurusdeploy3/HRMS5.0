<?php 

session_start();
$name = $_SESSION['login_user'];
include("config2.php");
$Emplid = $_GET['id'];

$sql="update employee_details set is_personal_data_filled='N' where employee_id='$Emplid'";
$result=mysqli_query($db,$sql);
header("Location: BoardingHome.php");
?>