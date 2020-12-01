<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["family_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE employee_family_particulars set is_active = 'N' WHERE family_id=".$_GET["family_id"]);
	storeDataInHistory($temp , "employee_family_particulars","family_id");
	if(!empty($result)){
		header("Location:familyform.php");
	}
}
?>

