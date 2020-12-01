<?php 
if(isset($_REQUEST))
{
session_start();
$name = $_SESSION['login_user'];
include("config.php");
$Emplid = $_SESSION['EmpId'];
include("ModificationFunc.php");
error_reporting(E_ALL && ~E_NOTICE);
$id = $_GET['id'];
$tabname = 'employee_projects';
$primKey = 'id';
StoreDatainHistory($id, $tabname,$primKey);

$sql="update employee_projects set is_active='N',modified_Date_and_time=now(),modified_by='$name' where id=$id";
$result=mysql_query($sql);
header("Location: ViewResource.php?id=$Emplid");
}
?>