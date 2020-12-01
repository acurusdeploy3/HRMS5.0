<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$newbloodgroup = mysqli_real_escape_string($db,$_REQUEST['inputBloodGroup']);


$sql="INSERT INTO all_blood_groups(blood_group,created_date_and_time,created_by) VALUES ('$newbloodgroup',now(),'Acurus')";
$result1=mysqli_query($db,$sql);

}
 
?>

