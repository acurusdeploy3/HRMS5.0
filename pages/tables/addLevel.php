<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept=mysql_real_escape_string($_POST['inputLevel']);
$sql="INSERT INTO all_levels(level_desc) VALUES ('$AddedDept')";
$result=mysql_query($sql);

}
?>