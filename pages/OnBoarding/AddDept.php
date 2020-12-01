<?php 
if(isset($_REQUEST))
{
 include("config2.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept=mysqli_real_escape_string($db,$_POST['inputDept']);
$sql="INSERT INTO all_departments(department,created_date_and_time,created_by) VALUES ('$AddedDept',now(),'Acurus')";
$result=mysqli_query($db,$sql);

}
?>