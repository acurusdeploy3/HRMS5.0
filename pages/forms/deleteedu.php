<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["qualifications_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE employee_qualifications set is_active = 'N' WHERE qualifications_id=".$_GET["qualifications_id"]);
	
	storeDataInHistory($temp , "employee_qualifications","qualifications_id"); 
	
	if(!empty($result)){
		header("Location:educationform.php");
	}
}
?>