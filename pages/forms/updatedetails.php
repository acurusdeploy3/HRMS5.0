<?php
require_once("config.php");
require_once("queries.php");
session_start();
$empId=$_SESSION['login_user'];
if(!empty($empId)) {
	$result = mysqli_query($db,"Update employee_details set is_personal_data_filled = 'Y' where employee_id = $empId ");
	if(!empty($result)){
		header("Location:thankyouform.php");
	}	
}
?>
