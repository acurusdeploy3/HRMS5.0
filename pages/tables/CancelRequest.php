<?php
//require_once("queries.php");
session_start();
include('config.php');
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$id = $_GET['id'];
$getRequestorName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$name'");
$getRequestorName = mysql_fetch_array($getRequestorName);
$RequestorName = $getRequestorName['name'];
mysql_query("update resource_change_Request set is_active='N' where id='$id'");
 $GetHRMAIL=mysql_query("select official_email from employee_details where employee_id in (select `value` from application_configuration where config_type='ID' and module='ALL'
and parameter='HR_MNGR_ID');");
$getHRMailRow = mysql_fetch_array($GetHRMAIL);
$emailval = $getHRMailRow['official_email'];
$getNewMgmr=mysql_query("select official_email from employee_details where employee_id in (select `new_value` from resource_change_request where id=''$id' );;");
$getNewMgmrRow = mysql_fetch_array($getNewMgmr);
$getNewMgmrMail = $getNewMgmrRow['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$recipients = 
			array(
                 $emailval => $emailval,
     			 $getNewMgmrMail => $getNewMgmrMail,
            );
			require 'PHPMailerAutoload.php';
			$mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'Resource Change : Request Cancelled';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear All,</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName.' : '.$name." has cancelled the Resource Change Request.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>AHRMS Support.</td>
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
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }




header("Location: ResourceChangeRequest.php");
?>