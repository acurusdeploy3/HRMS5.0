<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$ActivityText= mysqli_real_escape_string($db,$_POST['ActivityText']);
$StartDate= mysqli_real_escape_string($db,$_POST['DateofSession']);
$startTime= mysqli_real_escape_string($db,$_POST['SessionTime']);
$NumberHours= $_POST['NumberMinutes'];

$startTime=date_create($startTime);
$startTimeFormatted =  date_format($startTime,"H:i:s");
$TimetoAdd = $NumberHours*60;
$timestamp = strtotime($startTimeFormatted) + $TimetoAdd;
$endTimeFormatted = date('H:i:s', $timestamp);
echo $endTimeFormatted;
$dateFrom = $StartDate.' '.$startTimeFormatted;
$dateTo = $StartDate.' '.$endTimeFormatted;
mysqli_query($db,"insert into event_agenda
select 0,'$EventID','$dateFrom','$dateTo','Y','$ActivityText'");
//header("Location: EventAgenda.php?id=$EventID");
?>