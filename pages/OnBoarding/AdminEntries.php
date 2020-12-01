<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$EmployeeIDSys= $_POST['EmployeeIDSys'];
$OfficeMail =mysqli_real_escape_string($db,$_POST['OfficeMail']);
$OfficeMailPwd = mysqli_real_escape_string($db,$_POST['OfficeMailPwd']);
$OSType = mysqli_real_escape_string($db,$_POST['OSType']);
$Workstation = mysqli_real_escape_string($db,$_POST['Workstation']);
$WindowsLogin = mysqli_real_escape_string($db,$_POST['WindowsLogin']);
$WindowsLoginPwd = mysqli_real_escape_string($db,$_POST['WindowsLoginPwd']);
date_default_timezone_set('Asia/Kolkata');
$getEntries = mysqli_query($db,"select * from boarding_admin where employee_id='$EmployeeIDSys'");
if(mysqli_num_rows($getEntries)==0)
{
$BoardingAdmin = mysqli_query($db,"insert into boarding_admin (employee_id,system_login,system_login_password,mail_login,mail_login_password,os_type)
values
('$EmployeeIDSys','$WindowsLogin','$WindowsLoginPwd','$OfficeMail','$OfficeMailPwd','$OSType')");
$updateemployeedetails =mysqli_query($db,"update employee_Details set official_email='$OfficeMail', workstation='$Workstation' where employee_id='$EmployeeIDSys'");
$updateNotification = mysqli_query($db,"update notification_contact_email set contact_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
}
else
{
$BoardingAdmin = mysqli_query($db,"update boarding_admin set system_login='$WindowsLogin',system_login_password='$WindowsLoginPwd',
mail_login='$OfficeMail',mail_login_password='$OfficeMailPwd',os_type='$OSType' where employee_id='$EmployeeIDSys'");
$updateemployeedetails =mysqli_query($db,"update employee_Details set official_email='$OfficeMail', workstation='$Workstation' where employee_id='$EmployeeIDSys'");
$updateNotification = mysqli_query($db,"update notification_contact_email set contact_email='$OfficeMail' where employee_id='$EmployeeIDSys'");
}

if($WindowsLogin!='' && $OfficeMail!='')
{
	 $UpdateBoarding = mysqli_query($db,"update employee_boarding set is_login_created='Yes',is_system_allocated='Yes' where employee_id='$EmployeeIDSys'");
}

$getEmpValues  = mysqli_query($db,"select reporting_manager_id,mentor_id,employee_designation,department,official_email from employee_details where employee_id='$EmployeeIDSys'");
$getEmpRow = mysqli_fetch_array($getEmpValues);
		
$Mgmr = $getEmpRow['reporting_manager_id'];
$Role = $getEmpRow['employee_designation'];
$Dept = $getEmpRow['department'];
$Mentor = $getEmpRow['mentor_id'];
$official_email = $getEmpRow['official_email'];



if($Role =='' || $Dept =='' || $Mentor  ==''|| $Mgmr =='' || $official_email=='')
	{
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_designated='N' where
	employee_id='$EmployeeIDSys'");
	}
	else
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_designated='Y' where
	employee_id='$EmployeeIDSys'");
	}

$getProvValues = mysqli_query($db,"select is_biometric_authorized,is_id_issued,is_login_created,is_system_allocated from employee_boarding where employee_id='$EmployeeIDSys'");
$getProvValuesRow = mysqli_fetch_array($getProvValues);
$Radio1 = $getProvValuesRow['is_biometric_authorized'];
$Radio2 = $getProvValuesRow['is_id_issued'];
$Radio3 = $getProvValuesRow['is_login_created'];
$Radio4 = $getProvValuesRow['is_system_allocated'];	
if($Radio1 == 'No' || $Radio2 =='No' || $Radio3 =='No' || $Radio4 =='No')
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='N' where
		employee_id='$emplid'");
	}
else
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='Y' where
		employee_id='$emplid'");
	}

$getStatus = mysqli_query($db,"select are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed,mail_type
from employee_boarding
where employee_id='$EmployeeIDSys'");
$getCompStatus = mysqli_fetch_array($getStatus);
$getDocStatus = $getCompStatus['are_documents_uploaded'];
$getEmpStatus = $getCompStatus['is_designated'];
$getProvStatus = $getCompStatus['is_provisions_completed'];
$getDataStatus = $getCompStatus['is_data_sheet_completed'];
$mail_type = $getCompStatus['mail_type'];
if($getEmpStatus == 'N' || $getProvStatus=='N' || $getDataStatus=='N')
{	
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='P' where
		employee_id='$EmployeeIDSys'");	
}
else
{
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='Y' where
		employee_id='$EmployeeIDSys'");	
}	

	
	
	
	
$emailrec  = mysqli_query ($db,"SELECT created_by FROM `employee_Details` where employee_id='$EmployeeIDSys'");
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['created_by'];
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
            $mail->Subject = 'AHRMS : Employee Boarding : System Admin';
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

//mailtoEmp
$getLogins = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id='$EmployeeIDSys'");
$getLoginsROw = mysqli_fetch_array($getLogins);
$SystemLogin = $getLoginsROw['system_login'];
$system_login_password = $getLoginsROw['system_login_password'];
$mail_login = $getLoginsROw['mail_login'];
$mail_login_password = $getLoginsROw['mail_login_password'];
$os_type = $getLoginsROw['os_type'];
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
            $mail->Subject = 'Acurus Solutions : Welcome Aboard!';
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
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Welcome to Acurus Solutions. We hope you will find work here Rewarding and Challenging.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>We are looking forward to work with you and we are very certain that you will be a key contributor for the growth of Acurus. In turn, your career will blossom.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Employee ID for Acurus is :<strong>".$EmployeeIDSys."</strong>. You will be Reporting to <strong>".$RepMngrName."</strong> for the deeds you will have to perform in any case.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Use the link <a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'><strong>ahrms.acurussolutions.com</a></strong>, to access the HRMS for new updates from HR, Leaves & Attendance Management etc.</td>
					</tr>
					
					
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Please find below your access credentials for your Email and your PC / Laptop.</td>
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
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Wish you the Best! Happy Working!</td>
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
            $mail->Subject = 'AHRMS : Employee Boarding : New Reporting Employee';
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
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><strong>".$EmpName." : ".$EmployeeIDSys."</strong> will be your New Direct Report.</td>
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