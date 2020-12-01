<?php 
if(isset($_REQUEST))
{
 include("config2.php");
error_reporting(E_ALL && ~E_NOTICE);
session_start();

$name = $_SESSION['login_user'];
$AddedDoc=mysqli_real_escape_string($db,$_POST['inputDoc']);
$sql="insert into all_document_types (document_type,created_Date_and_time,created_by,authorized_for)

values
('$AddedDoc',now(),'$name','HR')";
$result=mysqli_query($db,$sql);

}
?>