<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$IsAttending = 'Yes';
$isMemEligible='N';
if($IsAttending=='Yes')
{
	$ismemtoEligible = mysqli_query($db,"select * from event_common_memento where event_id=$EventID");
	if (mysqli_num_rows($ismemtoEligible)>0)
	{
		$isMemEligible='Y';
	}
	mysqli_query($db,"insert into event_invitation_acceptors
					   select 0,'$EventID','$name','','','$isMemEligible','N','','Y'");
}
?>