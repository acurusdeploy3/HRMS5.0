<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
include("FYITransaction.php");
//$MRFIDVal = $_POST['MRFID'];
$EmployeeId = $_POST['EmployeeIdExtend'];
$MonthsExtended = $_POST['MonthsExtended'];
$EmployeeName = $_POST['EmployeeNameExtend'];
$DatePRob = $_POST['ProbationDateExtend'];
$ReasonExtended = $_POST['ReasonText'];


$transaction = "Extension of Services";
$module = "Boarding";
$date = date('Y-m-d');
$ProjectName = "Your Probation Has been extended for next the".$MonthsExtended." Month(s).";

FYITransaction($EmployeeId,$transaction,$module,$ProjectName,$date);



$probationenddate = date('Y-m-d', strtotime($DatePRob. ' + '.$MonthsExtended.' months'));
$getApplicantDetails = mysqli_query($db,"update employee_details set is_probation_extended='Y',extended_probation='$MonthsExtended',probation_end_date='$probationenddate',reason_extended='$ReasonExtended' where employee_id='$EmployeeId'");
$emailrec = mysqli_query ($db,"SELECT contact_email FROM `notification_contact_email` where employee_id='$EmployeeId'");
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['contact_email'];
 $content = $_POST['NotVal'];

			$senderName = 'Acurus HRMS'; //Enter the sender name
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
            $mail->Subject = 'Extension of Probation : Acurus Solutions';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>

					<tr style='background-color:#fff;'>

						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$EmployeeName.",</td>
					</tr>

					<tr style='background-color:#fff;'>

						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Probation Period has been extended for the next ".$MonthsExtended." Month(s).</td>
					</tr>

					<tr style='background-color:#fff;'>

						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly Contact Your HR for Further Queries.</td>
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



            $transaction = "Extension of Services";
            $module = "Boarding";
            $date = date('Y-m-d');
            $ProjectName = "Your Services have been Extended for the next ".$MonthsExtended." Month(s).";

            mysqli_query($db,"insert into fyi_transaction

            		(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by)

            			values

            		('$EmployeeId','$EmployeeName','$transaction','$module','$ProjectName','$date','Y',now(),'Acurus')");



//header("Location :GeneratePDF/HIPAADownload.php");
header("Location: GeneratePDF/HIPAADownload.php");
