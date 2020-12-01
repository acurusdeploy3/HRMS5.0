<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$id= $_GET['empId'];
$eventid = $_SESSION['eventid'];
mysqli_query($db,"delete from event_management_team where id='$id'");
header("Location: EventReq.php?id=$eventid");
?>