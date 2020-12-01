<?php
include("config2.php");

$email = $_POST['email'];
  	$sql = "SELECT * FROM employee_Details WHERE employee_id='$email'";
  	$results = mysqli_query($db, $sql);
  	if (mysqli_num_rows($results) > 0) {
  	  echo "taken";	
  	}else{
  	  echo 'not_taken';
  	}
  	exit();

?>