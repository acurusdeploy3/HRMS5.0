<?php 
if(isset($_REQUEST))
{
 include("config2.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept =  mysqli_real_escape_string($db,$_POST['inputBand']);
$AddedDesc =  mysqli_real_escape_string($db,$_POST['inputBandDesc']);
$sql="INSERT INTO all_bands(band_id,band_desc) VALUES ('$AddedDept','$AddedDesc')";
$result=mysqli_query($db,$sql);

}
?>