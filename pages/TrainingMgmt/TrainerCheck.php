<?php
include("config2.php");

$trainerid = $_POST['trainerid'];
$sessiondate = $_POST['sessiondate'];
$sessiontime = $_POST['sessiontime'];
  	$sql = "select * from training_sessions where trainer='$trainerid' and date_of_session='$sessiondate' and session_time='$sessiontime'";
  	$results = mysqli_query($db, $sql);
  	if (mysqli_num_rows($results) > 0) {
  	  echo "taken";	
  	}else{
  	  echo 'not_taken';
  	}
  	exit();

?>