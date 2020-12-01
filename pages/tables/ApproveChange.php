<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$id = $_GET['id'];
$NewVal = $_GET['NewVal'];
$EmpID = $_GET['EmpID'];
$getEmpName = mysql_query("select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_Details where employee_id='$EmpID'");
$getEmpNameRow = mysql_fetch_array($getEmpName);
$EmpName = $getEmpNameRow['name'];
$updateResourceMgmt = mysql_query("insert into resource_management_table
select 0, employee_id, project_id, department,
designation, reporting_manager, created_date_and_time, modified_Date_and_time,
created_by, modified_by, is_active, band, level, signed_loa_doc, effective_from
from resource_management_Table where employee_id=$EmpID order by created_date_and_time desc limit 1");
$insertId = mysql_insert_id($db);
$updateRes = mysql_query("update resource_management_Table set department='$NewVal', is_active='N', effective_from=curdate(),created_date_and_time=now() where res_management_id=$insertId");
$updateChange = mysql_query("update resource_change_Request set status='Approved' where id='$id'");

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
            $mail->Subject = 'Resource Change : Request Approved';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Resource Change Request has been approved.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested for : <strong>".$EmpName.' : '.$EmpID."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested Change : <strong>Department</strong></td>
					</tr>
									
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>New Department: <strong>". 
$NewVal."</strong></td>
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