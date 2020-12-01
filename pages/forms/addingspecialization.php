<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$addspecialization=mysqli_real_escape_string($db,$_REQUEST['inputSpecialization']);


$sql="INSERT INTO all_specializations(specialization_desc,created_date_and_time,created_by) VALUES 
('$addspecialization',now(),'Acurus')";
$result1=mysqli_query($db,$sql);

}
 
?>