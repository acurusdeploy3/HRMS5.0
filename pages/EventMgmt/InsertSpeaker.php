<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$SpeakerName= mysqli_real_escape_string($db,$_POST['SpeakerName']);
$SpeakerContact= mysqli_real_escape_string($db,$_POST['SpeakerContact']);
$Gender= mysqli_real_escape_string($db,$_POST['Gender']);
$SpeakerType= mysqli_real_escape_string($db,$_POST['SpeakerType']);
$SpeakerEmail= mysqli_real_escape_string($db,$_POST['SpeakerEmail']);
$AboutSpeaker= mysqli_real_escape_string($db,$_POST['AboutSpeaker']);
if($Gender=='Male')
{
	$Name = 'Shri. '.$SpeakerName;
}
else
{
	$Name = 'Shmt. '.$SpeakerName;
}
mysqli_query($db,"insert into event_external_invitees
(event_id,name,`desc`,email,contact_info,is_memento_eligible)
values
('$EventID','$Name','$AboutSpeaker','$SpeakerEmail','$SpeakerContact','Y')");


$speakerId = mysqli_insert_id($db);
$uploaddir = '../../uploads/';
$DocType='SPEAKER_PHOTO';
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
        $new_name = $uploaddir.$dat.'_'.$DocType.'_'.$SpeakerName.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$namewithdir = $dat.'_'.$DocType.'_'.$SpeakerName.'.'.$FileExt ;
		mysqli_query($db,"update event_external_invitees set photograph='$namewithdir' where id='$speakerId';");
    } 
	else 
	{
       date_default_timezone_set('Asia/Kolkata');
		$dat =date("Ymd_Hi");
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_'.$DocType.'_'.$SpeakerName.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$namewithdir = $dat.'_'.$DocType.'_'.$SpeakerName.'.'.$FileExt ;
		mysqli_query($db,"update event_external_invitees set photograph='$namewithdir' where id='$speakerId';");
		echo 'Failed';
    }
}



header("Location: NewEventSpeakers.php?id=$EventID");
?>