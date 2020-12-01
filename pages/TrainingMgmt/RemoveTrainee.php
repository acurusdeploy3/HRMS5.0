<?php 
if(isset($_REQUEST))
{
session_start();
$name = $_SESSION['login_user'];
include("config.php");
$train = $_SESSION['trainingId'];
include("ModificationFunc.php");
error_reporting(E_ALL && ~E_NOTICE);
$id = $_GET['id'];
$EmplId = $_GET['Emplid'];
$sql="update training_participants set is_active='N',modified_Date_and_time=now(),modified_by='$name' where participant_id=$id";
$sql1="update training_attendance set is_active='N',modified_Date_and_time=now(),modified_by='$name' where employee_id='$EmplId' and training_id='$train'";
$result=mysql_query($sql);
$result1=mysql_query($sql1);
header("Location: ViewTrainees.php?id=$train");
}
?>