<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$VotedFor = $_GET['empId'];

mysqli_query($db,"insert into poll_results (poll_criteria,voted_by,voted_for) values ('Captain','$name','$VotedFor')");
header("Location: EventPollVC.php");
?>