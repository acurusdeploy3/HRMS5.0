<?php
require_once("config.php");
require_once("queries.php");

$temp = $_GET["kye_id"];
if(!empty($temp)) {
	$result = mysqli_query($db,"UPDATE kye_details set is_Active = 'N' WHERE kye_id=".$_GET["kye_id"]);
	$result1 = mysqli_query($db,"UPDATE employee_documents_tracker set is_Active = 'N' WHERE doc_id=(select doc_id from kye_details where kye_id=$temp)");
	storeDataInHistory($temp , "kye_details","kye_id"); 
	if(!empty($result)){
		header("Location:kyeform.php");
	}
}
?>
