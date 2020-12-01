<?php
session_start();
//$effectivefrom = date("Y-m-d");
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];

include('config2.php');
//include("FYITransaction.php");
//$MRFIDVal = $_POST['MRFID'];
$EmployeeId = $_GET['EmployeeId'];
//$EmployeeName = $_POST['EmployeeName'];


$emailrec = mysqli_query ($db,"SELECT contact_email,concat(n.First_Name,'',n.MI,' ',n.Last_Name) as EmpName,date_format(c.Date_Of_Completion_of_Probation,'%d %b %Y') as doc
FROM `employee_details` n 
left join notification_contact_email ed on n.employee_id=ed.employee_id 
inner join cos_master c on n.employee_id=c.employee_id
where n.employee_id='$EmployeeId'");
echo "SELECT contact_email,concat(n.First_Name,'',n.MI,' ',n.Last_Name) as EmpName,date_format(c.Date_Of_Completion_of_Probation,'%d %b %Y') as doc
FROM `employee_details` n 
left join notification_contact_email ed on n.employee_id=ed.employee_id 
inner join cos_master c on n.employee_id=c.employee_id
where n.employee_id='$EmployeeId'";
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['contact_email'];
$EmployeeName = $emRow['EmpName'];
$doc = $emRow['doc'];

 $hrmail =mysqli_query($db,"select contact_email from notification_contact_email where employee_id in(select substring_index(substring_index(value,',',3),',',-2) from application_configuration
where config_type='LIST_VIEW' and module ='COS'and parameter='HR_ID')");
$hrmailRow = mysqli_fetch_array($hrmail);
$hremailval = $hrmailRow['contact_email'];

$hrmanagermail =mysqli_query($db,"select contact_email from notification_contact_email where employee_id in(select substring_index(substring_index(value,',',3),',',-2) from application_configuration
where config_type='COS_HANDLING' and module ='COS'and parameter='HR_ID')");
$hrmanagermailRow = mysqli_fetch_array($hrmanagermail);
$hrmemailval = $hrmanagermailRow['contact_email'];
 
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

 $recipients = 
			array(
                 $emailval => $emailval,
                 $hremailval => $hremailval,
                 $hrmemailval => $hrmemailval,
     
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
            $mail->Subject = 'Confirmation of Services : Acurus Solutions';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>This is to inform you that your Employment confirmation in Acurus got extended due to performance issue (Attendance / Work performance / Professional ethics) in your probationary period. </td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>HR department will provide the <b>Performance Improvement Plan letter</b> shortly. </td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>HRMS Login : &nbsp;<a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>ahrms.acurussolutions.com</a></td>
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
header("Location: ConfirmServices.php");	
	
	?>