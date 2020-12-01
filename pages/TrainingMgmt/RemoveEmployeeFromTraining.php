<?php
include('config.php');

if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	$tid = $_GET['tId'];
	
		$result = mysql_query("delete from active_training_employees where id= $id")
		or die(mysql_error());
		
	
	header("Location: ViewTraining.php?id=$tid");
	
	
}
else
{
	header("Location: TrainingMgmt.php");
}
?>