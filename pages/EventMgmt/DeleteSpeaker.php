<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$id= $_GET['id'];
$eventid = $_SESSION['eventid'];
mysqli_query($db,"update event_external_invitees set is_active='N' where id='$id'");
header("Location: NewEventSpeakers.php?id=$eventid");
?>