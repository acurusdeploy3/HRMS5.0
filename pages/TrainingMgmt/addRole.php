<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept =  mysql_real_escape_string($_POST['inputRole']);
$sql="INSERT INTO all_designations(designation_desc) VALUES ('$AddedDept')";
$result=mysql_query($sql);

}
?>