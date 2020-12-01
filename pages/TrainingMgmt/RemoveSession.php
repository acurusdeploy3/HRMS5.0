<?php 
if(isset($_REQUEST))
{
session_start();
$name = $_SESSION['login_user'];
include("config.php");
$Emplid = $_SESSION['EmpId'];
$train = $_SESSION['trainingId'];
include("ModificationFunc.php");
error_reporting(E_ALL && ~E_NOTICE);
$id = $_GET['id'];
$sql="update training_Sessions set is_active='N',modified_Date_and_time=now(),modified_by='$name' where session_id=$id";
$sql1="update training_attendance set is_active='N',modified_Date_and_time=now(),modified_by='$name' where session_id=$id";
$result=mysql_query($sql);
$result1=mysql_query($sql1);
header("Location: ViewSchedule.php?id=$train");
}
?>