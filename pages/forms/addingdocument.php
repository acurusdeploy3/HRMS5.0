<?php 
include("config.php");
if(isset($_REQUEST))
{

error_reporting(E_ALL && ~E_NOTICE);

$adddocument=mysqli_real_escape_string($db,$_REQUEST['inputDocument']);


$sql="INSERT INTO all_document_types(document_type,created_Date_and_time,created_by) VALUES ('$adddocument',now(),'Acurus')";
$result1=mysqli_query($db,$sql);

}
 
?>