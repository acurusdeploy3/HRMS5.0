<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$newDesignation = mysqli_real_escape_string($db,$_REQUEST['inputDesignation']);


$sql="INSERT INTO all_designations(designation_desc) VALUES ('$newDesignation')";
$result1=mysqli_query($db,$sql);

}
 
?>

