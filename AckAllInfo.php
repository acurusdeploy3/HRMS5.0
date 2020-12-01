<?php
include("config.php");
session_start();
$id = $_SESSION['login_user'];
$usergrp = $_SESSION['login_user_group'];
if($usergrp=='Employee')
{
mysqli_query($db,"update fyi_transaction set emp_ack='N' where employee_id=$id");
}
if($usergrp=='HR')
{
	mysqli_query($db,"update fyi_transaction set emp_ack='N',hr_ack='N' where employee_id=$id");
	mysqli_query($db,"update fyi_transaction set hr_ack='N' where hr_ack!='N'");
}
$GetTeamCount = mysqli_query($db,"select * from employee_Details where reporting_manager_id='$id'");
$TeamCount = mysqli_num_rows($GetTeamCount);
if($TeamCount>0)
{
	$HasTeam='Y';
}
if($HasTeam=='Y')
{
	$GetTeamMem = mysqli_query($db,"select employee_id from employee_Details where reporting_manager_id='$id'");
	while($GetTeamMemRow = mysqli_fetch_assoc($GetTeamMem))
	{
				mysqli_query($db,"update fyi_transaction set rep_ack='N' where employee_id='".$GetTeamMemRow['employee_id']."'");
	}
		mysqli_query($db,"update fyi_transaction set emp_ack='N' where employee_id=$id");
}
if($usergrp=='HR Manager')
{
	mysqli_query($db,"update fyi_transaction set emp_ack='N',rep_ack='N',hrm_ack='N' where employee_id=$id");
	mysqli_query($db,"update fyi_transaction set hrm_ack='N' where hrm_ack!='N'");
}
?>