<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$newbankname = mysqli_real_escape_string($db,$_REQUEST['inputnamebank']);


$sql="INSERT INTO tblbankname(bank_name,created_date_and_time,created_by) VALUES ('$newbankname',now(),'Acurus')";
$result1=mysqli_query($db,$sql);

}
 
?>

