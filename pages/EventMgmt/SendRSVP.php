<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$AboutInfo= mysqli_real_escape_string($db,$_POST['AboutInfo']);
mysqli_query($db,"update active_events set event_rsvp_subject='$AboutInfo' where event_id='$EventID'");
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo,event_rsvp_subject FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);	

	
			$senderName = 'Acurus'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
			if($getEventDetailsRow['event_logo']!=' ')
			{
				$image = '../../uploads/'.$getEventDetailsRow['event_logo'];
				$Style= 'width: 100%;margin:0 auto;display:block;height: 100%;';
				$align='center';
			}
			else
			{
				$image= 'acurus-logo.png';
				$Style= 'width:184px; margin:0 auto; align:right; display:block;';
				$align='right';
			}

 //$recipients = $email;
			
			require 'PHPMailerAutoload.php';

$mail = new PHPMailer();
            // Set up SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'RSVP : '.$getEventDetailsRow['event_title'];
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='".$image."' align='".$align."' alt='logo' style='".$Style."' display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear All,</td>
					</tr>
					
					
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$getEventDetailsRow['event_rsvp_subject']."</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>ahrms.acurussolutions.com</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>We Hope you can Make it!</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'></td>
					</tr>
				</tbody>
			
	</table>
	</body></html>");
            $mail->AltBody = 'This is a plain-text message body';
            //$mail->addAttachment('images/phpmailer_mini.gif');

            // Set the from/to
            $mail->setFrom($username, $senderName);
            // foreach ($recipients as $address => $name) {
                // $mail->addAddress($address, $name);
            // }
			
			$sql=mysqli_query($db,"SELECT official_email FROM `employee_details` where employee_id in (select employee_id from event_invitees where event_id='$EventID' and is_active='Y')
            and official_email is not null and official_email!=''");
			$RecCount = mysqli_num_rows($sql);
			if($RecCount<=90)
			{
				foreach ($sql as $row) {
				$mail->addAddress($row['official_email']);
					}
          	  if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
         		   }
			}
			else
			{
				$limitCnt = $RecCount/90;
				$cntr = 0;
				$Batch = 0;
				while($cntr<=$limitCnt)
				{
					$sql=mysqli_query($db,"SELECT official_email FROM `employee_details` where employee_id in (select employee_id from event_invitees where event_id='$EventID' and is_active='Y') 
                                    and official_email is not null and official_email!='' limit $Batch,90");
					
               		foreach ($sql as $row)
					{
							$mail->addAddress($row['official_email']);
					}
               	 	if (!$mail->send())
                    {
              		  echo "Mailer Error: " . $mail->ErrorInfo;
                    }
					$Batch=$Batch+90;
					$cntr=$cntr+1;
                	$mail->clearAllRecipients(); 
					
				}
			}
			

	
header("Location: EventInfo.php?id=$EventID");
?>