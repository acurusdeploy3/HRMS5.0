<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$FeedBackInfo= mysqli_real_escape_string($db,$_POST['FeedBackInfo']);
$MementoReceived= mysqli_real_escape_string($db,$_POST['r3']);
$DidFamAttend= mysqli_real_escape_string($db,$_POST['r2']);
$DidyouAttend= mysqli_real_escape_string($db,$_POST['r1']);
mysqli_query($db,"update event_invitation_acceptors set has_attended='$DidyouAttend' where event_id='$EventID' and employee_id='$name'");
mysqli_query($db,"update event_invitation_acceptors_family set has_attended='$DidFamAttend' where event_id='$EventID' and employee_in_relation='$name'");
mysqli_query($db,"update event_invitation_acceptors set is_memento_provided='$MementoReceived',is_memento_received='$MementoReceived' where event_id='$EventID' and employee_id='$name'");

mysqli_query($db,"insert into event_feedbacks (event_id,employee_id,feedback)
values
('$EventID','$name','$FeedBackInfo')");
header("Location: EventInfo.php?id=$EventID");
?>