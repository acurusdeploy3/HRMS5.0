<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

		$rid = $_GET['rid'];
		$scnname = $_GET['scnname'];
$getempid= mysqli_query ($db,"SELECT employee_id FROM employee_resignation_information where resignation_id=$rid");
$ememRow = mysqli_fetch_array($getempid);
$id=$ememRow['employee_id'];
		
if($scnname == 'Admin')		
{
$emailcon2 =mysqli_query($db,"SELECT distinct contact_email from notification_contact_email
 where employee_id=
(SELECT SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1) FROM employee_resignation_information r
where is_active='Y' and employee_id=$id and SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1) <> '')");
$emrohr= mysqli_fetch_array($emailcon2);
$hrrmail = $emrohr['contact_email'];

$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee FROM `employee_details`where employee_id=$id and is_active='Y' ");
$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];

	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
                 $hrrmail => $hrrmail,                 
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
            $mail->Subject = 'AHRMS - Exit - No due Formalities -'.$SenderNam.' - '.$id;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Hi,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly complete the <b>NO DUE FORMALITIES</b> for the following employee</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><b>Employee Details</b></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:8px;'>". $SenderNam ." - ". $id ."</td>
					</tr>
				
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Acurus Solutions</td>
					</tr>					
                    <tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>HRMS Login : &nbsp;<a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>ahrms.acurussolutions.com</a></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>P.S : Do not reply to this Mail.</td>
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
				echo "<script>alert('hi');</script>";
            } 
			else
			{	if($usergrp=='System Admin Manager' || $usergrp=='System Admin' )
				{
					header("Location: nodueadminform.php");
				}
				else{
				header("Location: resignationapprovalform.php");}
			}
}
else if($scnname == 'Accounts')		
{
$emailcon2 =mysqli_query($db,"SELECT distinct contact_email from notification_contact_email
 where employee_id=
(SELECT SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1) FROM employee_resignation_information r
where is_active='Y' and employee_id=$id and SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1) <> '')");
$emrohr= mysqli_fetch_array($emailcon2);
$hrrmail = $emrohr['contact_email'];


$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee FROM `employee_details`where employee_id=$id and is_active='Y' ");
$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];

	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
                 $hrrmail => $hrrmail,                 
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
            $mail->Subject = 'Acurus Solutions - Resignation - No due Formalities';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Hi,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly complete the <b>NO DUE FORMALITIES</b> for the following employee</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><b>Employee Details</b></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:8px;'>". $SenderNam ." - ". $id ."</td>
					</tr>
				
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Acurus Solutions</td>
					</tr>					
                    <tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>HRMS Login : &nbsp;<a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>ahrms.acurussolutions.com</a></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>P.S : Do not reply to this Mail.</td>
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
			{	if($usergrp=='Accounts Manager' || $usergrp=='Accountant' )
				{
					header("Location: nodueaccform.php");
				}else{
				
				header("Location: resignationapprovalform.php");
			}}
}

 
 ?>