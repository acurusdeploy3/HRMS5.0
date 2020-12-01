<?php 
if(isset($_REQUEST))
{
 include("config2.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept =  mysqli_real_escape_string($db,$_POST['inputRole']);
$sql="INSERT INTO all_designations(designation_desc) VALUES ('$AddedDept')";
$result=mysqli_query($db,$sql);

}
?>