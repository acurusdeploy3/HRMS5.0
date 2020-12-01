<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$Desc= mysqli_real_escape_string($db,$_POST['AboutInfo']);
$reg_last_date= mysqli_real_escape_string($db,$_POST['RegLastDate']);
$IsRegRequired= mysqli_real_escape_string($db,$_POST['IsRegRequired']);
$EventLoc= mysqli_real_escape_string($db,$_POST['EventLoc']);
$Venue= mysqli_real_escape_string($db,$_POST['EventVenue']);
$Website= mysqli_real_escape_string($db,$_POST['EventWeb']);
$Category= mysqli_real_escape_string($db,$_POST['CategorySel']);
$StartDate= mysqli_real_escape_string($db,$_POST['StartDate']);
$endDate= mysqli_real_escape_string($db,$_POST['endDate']);
$endTime= mysqli_real_escape_string($db,$_POST['endTime']);
$startTime= mysqli_real_escape_string($db,$_POST['startTime']);
$endTime=date_create($endTime);
$startTime=date_create($startTime);
$endTimeFormatted =  date_format($endTime,"H:i:s");
$startTimeFormatted =  date_format($startTime,"H:i:s");
$dateFrom = $StartDate.' '.$startTimeFormatted;
$dateTo = $endDate.' '.$endTimeFormatted;
if($reg_last_date=='')
{
	$reg_last_date='0001-01-01';
}
mysqli_query($db,"update active_events set event_desc='$Desc',event_location='$Venue',date_from='$dateFrom',
date_to='$dateTo',event_category='$Category',event_website='$Website',reg_last_date='$reg_last_date',location_type='$EventLoc',is_registration_required='$IsRegRequired' where event_id='$EventID';");
$DocType='EVENT_LOGO';
$uploaddir = '../../uploads/';
if($_FILES['ResumeFileDoc']['name']!='')
{
$uploadfile = $uploaddir . basename($_FILES['ResumeFileDoc']['name']);
$FileData = pathinfo($uploadfile);
$FileExt=$FileData['extension'];
$FileNamewithoutextension = pathinfo($uploadfile, PATHINFO_FILENAME);
if (move_uploaded_file($_FILES['ResumeFileDoc']['tmp_name'], $uploadfile)) 
 {
	 date_default_timezone_set('Asia/Kolkata');
	$dat =date("Ymd_Hi");
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_'.$DocType.'_'.$EventID.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$namewithdir = $dat.'_'.$DocType.'_'.$EventID.'.'.$FileExt ;
		mysqli_query($db,"update active_events set event_logo='$namewithdir' where event_id='$EventID';");
    } 
	else 
	{
       date_default_timezone_set('Asia/Kolkata');
		$dat =date("Ymd_Hi");
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_'.$DocType.'_'.$EventID.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$namewithdir = $dat.'_'.$DocType.'_'.$EventID.'.'.$FileExt ;
		mysqli_query($db,"update active_events set event_logo='$namewithdir' where event_id='$EventID';");
		echo 'Failed';
    }
}
if($_SESSION['fromEventsHome']!='Y')
{
	header("Location: NewEventSpeakers.php?id=$EventID");
}
else
{
	header("Location: EventInfo.php?id=$EventID");
}
?>