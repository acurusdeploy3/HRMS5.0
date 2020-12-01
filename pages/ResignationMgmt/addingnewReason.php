<?php 
include("config.php");
if(isset($_REQUEST))
{
error_reporting(E_ALL && ~E_NOTICE);
$addreason=mysqli_real_escape_string($db,$_REQUEST['inputReason']);
$sql="INSERT INTO all_reasons(resignation_reason,category,created_date_and_time,created_by) VALUES 
('$addreason','No Due Form',now(),'Acurus')";
$result1=mysqli_query($db,$sql);
}
?>