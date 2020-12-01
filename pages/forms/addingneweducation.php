<?php 
include("config.php");
if(isset($_REQUEST))
{
error_reporting(E_ALL && ~E_NOTICE);
$addeducation=mysqli_real_escape_string($db,$_REQUEST['inputEducation']);
$sql="INSERT INTO all_qualifications(qualification_desc,created_date_and_time,created_by) VALUES ('$addeducation',now(),'Acurus')";
$result1=mysqli_query($db,$sql);
}
?>
