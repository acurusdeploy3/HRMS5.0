<?php 
if(isset($_REQUEST))
{
 include("config2.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept=mysqli_real_escape_string($db,$_POST['inputLevel']);
$sql="INSERT INTO all_levels(level_desc) VALUES ('$AddedDept')";
$result=mysqli_query($db,$sql);

}
?>