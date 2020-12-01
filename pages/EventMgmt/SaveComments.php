<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$FeedBackInfo= mysqli_real_escape_string($db,$_POST['FeedBackInfo']);
mysqli_query($db,"update active_events set additional_comments='$FeedBackInfo' where event_id='$EventID'");
header("Location: EventInfo.php?id=$EventID");
?>