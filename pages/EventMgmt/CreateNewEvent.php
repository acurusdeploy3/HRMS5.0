<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$Title= mysqli_real_escape_string($db,$_POST['TitleText']);
$StartDate= mysqli_real_escape_string($db,$_POST['StartDate']);
$endDate= mysqli_real_escape_string($db,$_POST['endDate']);
$endTime= mysqli_real_escape_string($db,$_POST['endTime']);
$startTime= mysqli_real_escape_string($db,$_POST['startTime']);
$endTime=date_create($endTime);
$startTime=date_create($startTime);
$endTimeFormatted =  date_format($endTime,"H:i:s");
$startTimeFormatted =  date_format($startTime,"H:i:s");
$dateFrom = $StartDate.' '.$startTimeFormatted;
$dateTo = $endDate.' '.$endTimeFormatted;
mysqli_query($db,"insert into active_events (event_title,date_from,date_to) values ('$Title','$dateFrom','$dateTo')");
$Eventid = mysqli_insert_id($db);
echo $Eventid;
//header("Location: NewEventDesc.php?id=$Eventid");
?>