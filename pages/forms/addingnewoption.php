<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$addabout=mysqli_real_escape_string($db,$_REQUEST['inputAboutacurus']);


$sql="INSERT INTO all_about_acurus(about_desc,created_date_and_time,created_by) VALUES ('$addabout',now(),'Acurus')";
$result1=mysqli_query($db,$sql);
return $result1;
}
 
?>