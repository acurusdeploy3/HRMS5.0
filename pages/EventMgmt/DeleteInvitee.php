<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$id= $_GET['empId'];
$eventid = $_SESSION['eventid'];
mysqli_query($db,"update event_invitees set is_active='N' where employee_id='$id'");
header("Location: NewEventInvitees.php?id=$eventid");
?>