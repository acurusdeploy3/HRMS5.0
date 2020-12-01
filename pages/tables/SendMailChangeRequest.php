<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_GET['emplid'];
date_default_timezone_set('Asia/Kolkata');
$InsertEmpData = mysql_query("insert into employee_data_change_request (raised_by, raised_for, raised_request, created_date_and_time, created_by, is_active,status)
values ('$name','$emplid','Mail_Change',now(),'$createdby','Y','Requested')");
$emailrec  = mysql_query ("SELECT value FROM `application_configuration` where config_type='Boarding_admin'");
$emailrec1 = mysql_query ("SELECT employee_id, official_email FROM `employee_details` where job_role='System Admin Manager'");
$emRow = mysql_fetch_array($emailrec);
$emRow1 = mysql_fetch_array($emailrec1);
$emailval = $emRow['value'];
$emailval1 = $emRow1['official_email'];
$empdet = mysql_query("Select concat(First_Name,' ',MI,' ',Last_Name) as Name,Official_Email from employee_details where employee_id = $emplid");
$empdetRow = mysql_fetch_array($empdet);
$EmpName = $empdetRow['Name'];
$EmpMail = $empdetRow['Official_Email'];

$senderName = 'Acurus HRMS'; //Enter the sender name
   $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

		$recipients = 
			array(
			
                 $emailval => $emailval,
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
            $mail->Subject = 'AHRMS - Employee Official Email & System Login';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
			<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
		<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
		<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Admin Team,</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly create an  Official <b>Acurus Email</b> for the Following Employee.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee ID : <strong>". $emplid." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Existing Email ID : <strong>". $EmpMail." </strong></td>
					</tr>
					
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Acurus HR.</td>
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
			else
				{
					header("Location: SearchEmployee.php");
					//echo "hi";
					$_SESSION['smessage'] = 'Success';
					echo $_SESSION['smessage'];
				}