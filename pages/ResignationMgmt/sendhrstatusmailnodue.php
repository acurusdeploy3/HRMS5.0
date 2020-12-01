<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

		$id = $_GET['id'];


$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");

$emailhr  = mysqli_query ($db,"SELECT * FROM `application_configuration` where config_type='EMAIL' and parameter like 'HR_MAIL' and module='RESIGNATION' ");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$emailcon =mysqli_query($db,"SELECT contact_email FROM employee_details e
inner join notification_contact_email n on e.employee_id=n.employee_id
where job_role in ('Accounts Manager') and e.is_active='Y'");
$emroacc= mysqli_fetch_array($emailcon);
$accmail = $emroacc['contact_email'];

$emailcon1 =mysqli_query($db,"SELECT value FROM `application_configuration` where module='ALL' and parameter='ADMIN_MAIL'");
$emroadm= mysqli_fetch_array($emailcon1);
$admmail = $emroadm['contact_email'];


$emailcon2 =mysqli_query($db,"SELECT distinct contact_email from notification_contact_email
 where employee_id=
(SELECT SUBSTRING_INDEX(allocated_to, '-', -1) FROM employee_resignation_information r
where is_active='Y' and employee_id=$id and SUBSTRING_INDEX(allocated_to, '-', -1) <> '')");
$emrohr= mysqli_fetch_array($emailcon2);
$hrrmail = $emrohr['contact_email'];

$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Job_Role,reporting_manager_id FROM `employee_details`where employee_id=$id and is_active='Y'");


$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Job_Role,reporting_manager_id FROM `employee_details`where employee_id=$id and is_active='Y' ");

$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];

$mngrdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfManager FROM `employee_details`where employee_id=$repid and is_active='Y' ");
$mngrpro= mysqli_fetch_array($mngrdet);
$repname=$mngrpro['NameOfManager'];

 
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['contact_email'];
$repemailval = $emRow['reporting_manager_contact_email'];
	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
                 $accmail => $accmail,
				 $admmail  => $admmail,
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
            $mail->Subject = 'AHRMS - Exit - No due Formalities '.$SenderNam.' - '.$id;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear HR/Admin Manager/Accounts Manager,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly process the <b> No Due Formalities </b> of the following employee in HRMS</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Employee Name : ". $SenderNam ."</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards</td>
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
			{
				//echo $id;
				if($usergrp == 'HR Manager'){
				header("Location: resignationprocessingform.php");
				}
				else
				{header("Location: resignationapprovalform.php");}	
			}
 
 ?>