<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept=mysql_real_escape_string($_POST['inputDept']);
$sql="INSERT INTO all_departments(department,created_date_and_time,created_by) VALUES ('$AddedDept',now(),'Acurus')";
$result=mysql_query($sql);

}
?>