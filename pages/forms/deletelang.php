<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["language_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE employee_languages set is_Active = 'N' WHERE language_id=".$_GET["language_id"]);
	storeDataInHistory($temp , "employee_languages","language_id");
	if(!empty($result)){
		header("Location:miscform.php");
	}
}
?>
