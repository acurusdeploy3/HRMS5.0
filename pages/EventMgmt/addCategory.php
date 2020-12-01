<?php 
if(isset($_REQUEST))
{
	include("config.php");
	error_reporting(E_ALL && ~E_NOTICE);
	$AddedDept =  mysqli_real_escape_string($db,$_POST['inputRole']);
	$sql="insert into event_Category select 0,'$AddedDept'";
	$result=mysqli_query($db,$sql);
}
?>