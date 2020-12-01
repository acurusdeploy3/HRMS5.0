<?php
include("config2.php");

$email = $_POST['email'];
$email1 = $_POST['email1'];
$date1=date_create($email);
$date2=date_create($email1);
$diff=date_diff($date2,$date1);
 $difval = $diff->format("%a");
$int = (int)$difval;
echo $int+1;
  	exit();
?>