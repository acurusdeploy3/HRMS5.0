<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["certifications_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE employee_certifications set is_Active = 'N' WHERE certifications_id=".$_GET["certifications_id"]);
	storeDataInHistory($temp , "employee_certifications","certifications_id");
	if(!empty($result)){
		header("Location:certificationform.php");
	}
}
?>
