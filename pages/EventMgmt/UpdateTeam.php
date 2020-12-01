<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$AdditionalEmp = $_POST['AdditionalEmp'];
$RoleSel = $_POST['RoleSel'];
$EventID = $_POST['EventID'];
if($AdditionalEmp!='')
	{
		mysqli_query($db,"insert into event_management_team
							select 0,'$EventID','$AdditionalEmp','$RoleSel';");
	}
	header("Location: EventReq.php?id=$EventID");
	?>