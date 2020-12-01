<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);

$AddedDept =  mysql_real_escape_string($_POST['inputBand']);
$AddedDesc =  mysql_real_escape_string($_POST['inputBandDesc']);
$sql="INSERT INTO all_bands(band_id,band_desc) VALUES ('$AddedDept','$AddedDesc')";
$result=mysql_query($sql);

}
?>