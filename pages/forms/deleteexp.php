<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["work_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE employee_work_history set is_active = 'N' WHERE work_id='$temp'");
	storeDataInHistory($temp ,"employee_work_history","work_id"); 
	if(!empty($result)){
		header("Location: experienceform.php");
	}
}
?>
