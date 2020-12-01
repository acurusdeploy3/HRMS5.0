<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//$MRFIDVal = $_POST['MRFID'];
$EmployeeIDSys= $_POST['EmployeeIDSys'];
$OfficeMail =mysqli_real_escape_string($db,$_POST['OfficeMail']);
$OfficeMailPwd = mysqli_real_escape_string($db,$_POST['OfficeMailPwd']);
date_default_timezone_set('Asia/Kolkata');
$getEntries = mysqli_query($db,"select * from boarding_admin where employee_id='$EmployeeIDSys'");
if(mysqli_num_rows($getEntries)==0)
{
$BoardingAdmin = mysqli_query($db,"insert into boarding_admin (employee_id,system_login,system_login_password,mail_login,mail_login_password,os_type)
values
('$EmployeeIDSys','$WindowsLogin','$WindowsLoginPwd','$OfficeMail','$OfficeMailPwd','$OSType')");
$updateemployeedetails =mysqli_query($db,"update employee_Details set official_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
$updateNotification = mysqli_query($db,"update notification_contact_email set contact_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
}
else
{
$BoardingAdmin = mysqli_query($db,"update boarding_admin set 
mail_login='$OfficeMail',mail_login_password='$OfficeMailPwd' where employee_id='$EmployeeIDSys'");
$updateemployeedetails =mysqli_query($db,"update employee_Details set official_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
$updateNotification = mysqli_query($db,"update notification_contact_email set contact_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
}	
//mailtoHR
$emailrec  = mysqli_query ($db,"SELECT raised_by FROM `employee_data_change_request` where raised_for='$EmployeeIDSys'");
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['raised_by'];
$emailrec1 = mysqli_query($db,"SELECT official_email FROM `employee_details` where employee_id='$emailval'");
$emRow1 = mysqli_fetch_array($emailrec1);
$emailval1 = $emRow1['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
   $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

		$recipients = 
			array(
			
				 $emailval1 => $emailval1,	
            );
			
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
            $mail->Subject = 'AHRMS - Employee Official Email';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
			<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
		<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
		<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Hello There,</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Admin Team has Successfully created the requested Email & Login. Kindly Check.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee ID : <strong>". $EmployeeIDSys." </strong></td>
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
			
			//endofMailtoHR
			
			//mailtoEmp
$getLogins = mysqli_query($db,"SELECT mail_login,mail_login_password FROM `boarding_admin` where employee_id='$EmployeeIDSys'");
$getLoginsROw = mysqli_fetch_array($getLogins);
$mail_login = $getLoginsROw['mail_login'];
$mail_login_password = $getLoginsROw['mail_login_password'];
$getEmID = mysqli_query($db,"select concat(First_name,' ',last_name,' ',MI) as Name,official_email,reporting_manager_id from employee_details where employee_id='$EmployeeIDSys'");
$emRow = mysqli_fetch_array($getEmID);
$EmpName = $emRow['Name'];
$emailval = $emRow['official_email'];
$Mgmr = $emRow['reporting_manager_id'];
$getRepMgmtname = mysqli_query($db,"select concat(First_name,' ',last_name,' ',MI) as Name from employee_details where employee_id='$Mgmr'");
$getRepMgmtnameRow = mysqli_Fetch_array($getRepMgmtname);
$RepMngrName = $getRepMgmtnameRow['Name'];

			$senderName = 'Acurus'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

		$recipients = 
			array(
					$emailval => $emailval,
				);
			
			

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
            $mail->Subject = 'Acurus Solutions : New Official Email';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
			<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
		<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
		<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$EmpName.",</td>
					</tr>					
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Please find below your access credentials for your Acurus Email.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email : <strong>".$mail_login."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email Password: <strong>".$mail_login_password."</strong></td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'></td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>PS: Do not reply to this EMAIL.</td>
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
					
			//end of mail to mailtoEmp
			
		//mailtoManager

$getMngrEm = mysqli_query($db,"select official_email from employee_details where employee_id='$Mgmr'");
$getMngrEmROw = mysqli_fetch_array($getMngrEm);
$emailval = $getMngrEmROw['official_email'];		


$senderName = 'Acurus'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

		$recipients = 
			array(
					$emailval => $emailval,
				);
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
            $mail->Subject = 'Creation of New Official Email';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
			<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
		<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
		<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$RepMngrName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>New Acurus Email has been created for <strong>".$EmpName." : ".$EmployeeIDSys."</strong> </td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly walk them through with the setting up of new Official Email.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email : <strong>".$mail_login."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email Password: <strong>".$mail_login_password."</strong></td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'></td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>PS: Do not reply to this EMAIL.</td>
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
			else
			{
				$BoardingAdmin = mysqli_query($db,"update employee_data_change_request set 
				status='Completed' where raised_for='$EmployeeIDSys'");
				header("Location: BoardingHome.php");
			}
			