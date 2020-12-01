<?php
include("config.php");

$DateofSession = $_POST['DateofSession'];
$SessionTime = $_POST['SessionTime'];
$startTime=date_create($SessionTime);
$startTimeFormatted =  date_format($startTime,"H:i:s");
$dateFrom = $DateofSession.' '.$startTimeFormatted;


$NumberHours= $_POST['NumberMinutes'];
$TimetoAdd = $NumberHours*60;
$timestamp = strtotime($startTimeFormatted) + $TimetoAdd;
$endTimeFormatted = date('H:i:s', $timestamp);
$dateTo = $DateofSession.' '.$endTimeFormatted;

$EventID = $_POST['EventID'];
  	$sql = "select * from event_agenda where
( ('$dateFrom' > session_date and '$dateFrom' < session_to_date)
  or
  ('$dateTo' > session_date and '$dateTo' < session_to_date)
  or
  (session_date > '$dateFrom' and session_date < '$dateTo')
  or
  (session_to_date > '$dateFrom' and session_to_date < '$dateTo')
  or
(session_date = '$dateFrom' and session_to_date='$dateTo')
)
and event_id=$EventID and is_active='Y'";
  	$results = mysqli_query($db, $sql);
	
  	if (mysqli_num_rows($results) > 0) {
  	  echo "taken";	
  	}
	else{
		
  	  echo 'not_taken';
  	}
  	exit();
?>