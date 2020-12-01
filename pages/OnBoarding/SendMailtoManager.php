<?php
session_start();
$emplid= $_SESSION['Employee_id'];
include('config2.php');
$getEmpValues  = mysqli_query($db,"select reporting_manager_id,mentor_id,employee_designation,department from employee_details where employee_id=$emplid");
$getEmpRow = mysqli_fetch_array($getEmpValues);
$Mgmr = $getEmpRow['reporting_manager_id'];
$getname = mysqli_query($db,"select concat(First_name,' ',last_name,' ',MI) as Name from employee_details where employee_id='$emplid'");
$getnameRow = mysqli_Fetch_array($getname);
$EmpName = $getnameRow['Name'];
$getRadioValues  = mysqli_query($db,"select is_biometric_authorized,is_id_issued,is_login_created,is_system_allocated,is_data_sheet_completed,mail_type from employee_boarding where employee_id=$emplid");
$getRadioRow = mysqli_fetch_array($getRadioValues);
$BioMetric = $getRadioRow['is_biometric_authorized'];
$IDCard = $getRadioRow['is_id_issued'];
$LoginCreated = $getRadioRow['is_login_created'];
$SystemAllocated = $getRadioRow['is_system_allocated'];
$DataSheet = $getRadioRow['is_data_sheet_completed'];
$mail_type = $getRadioRow['mail_type'];
$getMngrEm = mysqli_query($db,"select official_email from employee_details where employee_id='$Mgmr'");
$getMngrEmROw = mysqli_fetch_array($getMngrEm);
$emailval = $getMngrEmROw['official_email'];
$getLogins = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id=$emplid ");
$getLoginsROw = mysqli_fetch_array($getLogins);
$SystemLogin = $getLoginsROw['system_login'];
$system_login_password = $getLoginsROw['system_login_password'];
$mail_login = $getLoginsROw['mail_login'];
$mail_login_password = $getLoginsROw['mail_login_password'];
$os_type = $getLoginsROw['os_type'];

			$senderName = 'Acurus'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

		$recipients = 
			array(
					$emailval => $emailval,
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
            $mail->Subject = 'New Reporting Employee';
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
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><strong>".$EmpName." : ".$emplid."</strong> will be your New Direct Report.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly walk them through with the setting up of System Login & Official Email.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>System Login : <strong>".$SystemLogin."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Login Password : <strong>".$system_login_password."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email : <strong>".$mail_login."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Official Email Password: <strong>".$mail_login_password."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>OS Type: <strong>".$os_type."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Email Type: <strong>".$mail_type."</strong></td>
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
			header("Location: BoardingHome.php");
?>