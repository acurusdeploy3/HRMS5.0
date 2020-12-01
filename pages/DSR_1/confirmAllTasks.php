<?php
//require_once("queries.php");
session_start();
include('config.php');

$name = $_SESSION['login_user'];
$date = date("Y-m-d h:i:s");


$selectReIDS = $_POST['selectvalues'];
foreach($selectReIDS as $reqID)
{
	$strval1 = explode("|",$reqID);
	$dval1 = $strval1[1];
	$idval1 = $strval1[0];
	
	$res= "Update dsr_summary set is_approved='Y',manager_comments=employee_comments,modified_date_and_time='".$date."',modified_by='$name' where employee_id='$idval1' and date='$dval1'";
	$result = mysqli_query($db,$res);
	echo $res;
}
?>