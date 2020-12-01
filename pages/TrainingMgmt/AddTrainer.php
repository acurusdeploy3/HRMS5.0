<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);
date_default_timezone_set('Asia/Kolkata');
$AddedDept=mysql_real_escape_string($_POST['TrainerSelNew']);
$sql="INSERT INTO all_trainers(employee_id,created_date_and_time,created_by,is_active) VALUES ('$AddedDept',now(),'Acurus','Y')";
$result=mysql_query($sql);
}
?>