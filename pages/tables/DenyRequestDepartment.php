<?php
//require_once("queries.php");
session_start();
include('config.php');
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$id = $_POST['DeptID'];
$reason = $_POST['reason'];
mysql_query("update resource_change_Request set status='Denied',remarks='$reason' where id='$id'");




$getReqDetails= mysql_query("select r.id,raised_by,raised_for,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, concat(b.first_name,' ',b.last_name,' ',b.mi) as Manager,
raised_request,new_value,old_value,reason_for_change,r.remarks,r.status
from
resource_change_request r  inner join employee_details a on r.raised_for=a.employee_id
inner join employee_details b on r.raised_by=b.employee_id
 where r.id=$id");

$getReqDetailsRow = mysql_fetch_array($getReqDetails);
$CurrentReport = $getReqDetailsRow['Current_reporting']; 
$New_Reporting = $getReqDetailsRow['New_Reporting']; 
$EmpName = $getReqDetailsRow['name']; 
$raised_for = $getReqDetailsRow['raised_for']; 
$old_value = $getReqDetailsRow['old_value']; 
$new_value = $getReqDetailsRow['new_value']; 

$getRaiserDetails = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,official_email from employee_Details e inner join resource_change_request
a on e.employee_id=a.raised_by where a.id=$id;");
$getRaiserDetailsRow = mysql_fetch_array($getRaiserDetails);
$RaiserName = $getRaiserDetailsRow['name'];
$emailval = $getRaiserDetailsRow['official_email'];
$senderName = 'Acurus HRMS'; //Enter the sender name
$username = 'notifications@acurussolutions.com'; //Enter your Email
$password = 'ukkupzzernjykeap';// Enter the Password
$recipients = 
			array(
                 $emailval => $emailval,
     
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
            $mail->Subject = 'Resource Change : Request Denied';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Resource Change Request has been Denied.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$raised_for."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Department</strong></td>
					</tr>
						
<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Existing Department :  <strong>". 
$old_value."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Department :  <strong>". 
$new_value."</strong></td>
					</tr>
							
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason for Denial : <strong>".$reason." </strong></td>
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