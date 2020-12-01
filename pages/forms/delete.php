<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["TaskId"])) {
	$result = $db_handle->runQuery("UPDATE `actionitem_follow1` SET `Status`= 'Deleted' WHERE TaskId='" . $_GET["TaskId"] . "'");

	if(!empty($result)){
	$result1 = $db_handle->runQuery("UPDATE `actionitem` SET `Status`= 'Deleted' WHERE TaskId='" . $_GET["TaskId"] . "'");
	}
	if(!empty($result1)){
		if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

		//header("Location:index.php");
	}
}
?>

