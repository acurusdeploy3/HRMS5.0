<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EmployeeID = $_POST['EmplID'];
$EmployeeManager = $_POST['EmployeeManager'];
$EmployeeDepartment = $_POST['EmployeeDepartment'];
$CurrentDept = $_POST['CurrentDepartment'];
$CurrentMgmr = $_POST['MgmrID'];
$addedEmail='karthika.d@acurussolutions.com';
$getEmpName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$EmployeeID'");
$getRequestorName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$name'");
$getCurrentRMName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$CurrentMgmr'");
$getNewRMName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$EmployeeManager'");
$getEmpNameRow = mysql_fetch_array($getEmpName);
$getRequestorName = mysql_fetch_array($getRequestorName);
$getCurrentRMNameRow = mysql_fetch_array($getCurrentRMName);
$getNewRMNameRow = mysql_fetch_array($getNewRMName);

$EmpName = $getEmpNameRow['name'];
$RequestorName = $getRequestorName['name'];
$CurrentRMName = $getCurrentRMNameRow['name'];
$NewRMName = $getNewRMNameRow['name'];
$GetHRMAIL=mysql_query("select official_email from employee_details where employee_id in (select `value` from application_configuration where config_type='ID' and module='ALL'
and parameter='HR_MNGR_ID');");
$getHRMailRow = mysql_fetch_array($GetHRMAIL);
$emailval = $getHRMailRow['official_email'];
$reason = mysql_real_escape_string($_POST['reason']);

$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
if($EmployeeManager!='' && $EmployeeDepartment==$CurrentDept)
{
$SendReq = mysql_query("insert into resource_change_Request
(raised_by,raised_for,raised_request,new_value,old_value,created_by,reason_for_change,status) VALUES
('$name','$EmployeeID','Reporting Manager Change','$EmployeeManager','$CurrentMgmr','$name','$reason','Request under Process')");

$ReqChange = 'Reporting Manager';
$ReqValue = $NewRMName;
			
			$recipients = 
			array(
                 $emailval => $emailval,
            	$addedEmail => $addedEmail,
     
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
            $mail->Subject = 'AHRMS : Reporting Manager Change Request';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName.' : '.$name." has requested for Resource Change.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Reporting Manager</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change From : <strong>". 
$CurrentRMName." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change To: <strong>". 
$NewRMName."</strong></td>
					</tr>
							
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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

$name=$_SESSION['login_user'];			
$getNewMgmrDetails = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,official_email from employee_Details where employee_id='$EmployeeManager'");
$getNewMgmrDetailsRow = mysql_fetch_array($getNewMgmrDetails);
$NewMGmr = $getNewMgmrDetailsRow['name'];
$emailval = $getNewMgmrDetailsRow['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$recipients = 
			array(
                 $emailval => $emailval,
                 $email => $email,
            );
			$mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'AHRMS : Reporting Change : Request Received';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$NewMGmr.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName." : ".$name." has raised request to change you as the direct report for the following Employee. You will be notified once the HR has approved the request.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
		<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
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
                echo "Mailer Error1: " . $mail->ErrorInfo;
            }	
}
elseif($EmployeeDepartment!=$CurrentDept && $EmployeeManager=='')
{
	$SendReq = mysql_query("insert into resource_change_Request
(raised_by,raised_for,raised_request,new_value,old_value,created_by,reason_for_change,status) VALUES
('$name','$EmployeeID','Department Change','$EmployeeDepartment','$CurrentDept','$name','$reason','Request under Process')");
$ReqChange = 'Department';
$ReqValue = $EmployeeDepartment;
$recipients = 
			array(
                 $emailval => $emailval,
				$addedEmail => $addedEmail,
     
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
            $mail->Subject = 'AHRMS : Department Change Request';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName.' : '.$name." has requested for Resource Change.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Department</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change To : <strong>". 
$EmployeeDepartment." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change From: <strong>". 
$CurrentDept."</strong></td>
					</tr>
							
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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
}
else
{
$SendReq = mysql_query("insert into resource_change_Request   
(raised_by,raised_for,raised_request,new_value,old_value,created_by,reason_for_change,status) VALUES
('$name','$EmployeeID','Reporting Manager Change','$EmployeeManager','$CurrentMgmr','$name','$reason','Request under Process')");
$SendReq1 = mysql_query("insert into resource_change_Request  
(raised_by,raised_for,raised_request,new_value,old_value,created_by,reason_for_change,status) VALUES
('$name','$EmployeeID','Department Change','$EmployeeDepartment','$CurrentDept','$name','$reason','Request under Process')");
$ReqChange = 'Department & Reporting Manager';
$ReqValue = 'Department : '.$EmployeeDepartment.', Reporting Manager : '.$NewRMName;


$recipients = 
			array(
                 $emailval => $emailval,
					$addedEmail => $addedEmail,
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
            $mail->Subject = 'AHRMS : Reporting Manager Change Request';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName.' : '.$name." has requested for Resource Change.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Reporting Manager</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change From : <strong>". 
$CurrentRMName." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change To: <strong>". 
$NewRMName."</strong></td>
					</tr>
							
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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
			
			
$name = $_SESSION['login_user'];
			$recipients = 
			array(
                 $emailval => $emailval,
            $addedEmail => $addedEmail,
     
            );
			$mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'AHRMS : Department Change Request';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName.' : '.$name." has requested for Resource Change.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Department</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change To : <strong>". 
$EmployeeDepartment." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change From: <strong>". 
$CurrentDept."</strong></td>
					</tr>
							
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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
$name=$_SESSION['login_user'];			
$getNewMgmrDetails = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,official_email from employee_Details where employee_id='$EmployeeManager'");
$getNewMgmrDetailsRow = mysql_fetch_array($getNewMgmrDetails);
$NewMGmr = $getNewMgmrDetailsRow['name'];
$emailval = $getNewMgmrDetailsRow['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$recipients = 
			array(
                 $emailval => $emailval,
                 $email => $email,
            );
			$mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'AHRMS : Reporting Change : Request Received';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$NewMGmr.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$RequestorName." : ".$name." has raised request to change you as the direct report for the following Employee. You will be notified once the HR has approved the request.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
		<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
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
                echo "Mailer Error1: " . $mail->ErrorInfo;
            }	
			
			
			
			
			
			
			
			
			
			
			
			
		
		
}
$name = $_SESSION['login_user'];
$getRaiserDetails = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,official_email from employee_Details where employee_id='$name'");
$getRaiserDetailsRow = mysql_fetch_array($getRaiserDetails);
$RaiserName = $getRaiserDetailsRow['name'];
$emailval = $getRaiserDetailsRow['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$recipients = 
			array(
                 $emailval => $emailval,
                 $email => $email,
            );
			$mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;

            // Build the message
            $mail->Subject = 'AHRMS : '.$ReqChange.' Change : Request Received';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$RaiserName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Resource Change Request has been Received. You will be notified once the HR Process the Request.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
		<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmployeeID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
			<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Change Requested : <strong>".$ReqChange."</strong></td>
					</tr>
									
					<tr style='background-color:#fff;'>
		<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Value: <strong>". 
$ReqValue."</strong></td>
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
                echo "Mailer Error1: " . $mail->ErrorInfo;
            }
?>