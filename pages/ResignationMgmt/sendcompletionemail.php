<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

		$id = $_SESSION['login_user'];
		$resignation_id = $_GET['resignation_id'];

		$notid=$_GET['notid'];


$emailhr  = mysqli_query ($db,"SELECT * FROM `application_configuration` where config_type='EMAIL' and parameter like 'HR_MAIL' and module='RESIGNATION' ");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$emailcon =mysqli_query($db," SELECT employee_id,reason_for_resignation,status,employee_comments,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds,
date_format(date(date_of_leaving),'%d-%b-%Y') as dl,
reporting_manager_id FROM `employee_resignation_information`where resignation_id=$resignation_id order by resignation_id desc limit 1 ");

$emaildec = mysqli_query($db,"select contact_email as allmail from notification_contact_email where employee_id in
(SELECT value FROM `application_configuration` where config_type='EMAIL' and module='EMPLOYEE_DEACTIVATION'
and parameter='COMPLETION_MAIL')");


$emro= mysqli_fetch_array($emailcon);
$content = $emro['reason_for_resignation'];
$comments =$emro['employee_comments'];
$dateofsub =$emro['ds'];
$dateofleaving =$emro['dl'];
$emid =$emro['employee_id'];
$repid =$emro['reporting_manager_id'];


$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Employee_ID,Job_Role,Department FROM `employee_details`where employee_id=$emid");

$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];
$SenderID = $empro['Employee_ID'];
$SenderDepartment = $empro['Department'];

$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$repid");

 
$emRow = mysqli_fetch_array($emailrec);
$repemailval = $emRow['contact_email'];

$deptname= mysqli_query($db,"select department from employee_details where employee_id=$emid");
$emdepRow = mysqli_fetch_array($deptname);
$deptnameval=$emdepRow['department'];

$hodname= mysqli_query($db,"select employee_id from all_hods where department='$deptnameval'");
$emdhodRow = mysqli_fetch_array($hodname);
$loginvalue=$emdhodRow['employee_id'];
	
$getuserdet1= mysqli_query($db,"select distinct(contact_email) as contact_email from notification_contact_email where employee_id='$loginvalue'");
$emhodrow = mysqli_fetch_array($getuserdet1);
$mailhod=$emhodrow['contact_email'];

if($notid=='Y')
{
$query3 ="Update employee_resignation_information set is_notification_sent='Y' where employee_id=$id and is_active='Y' order by resignation_id desc limit 1 ";
$result3=mysqli_query($db,$query3);
}
	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
				 $repemailval => $repemailval,                
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
            $mail->Subject = 'AHRMS - Exit - Resignation Process Completed - '.$SenderNam.' - '.$SenderID;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Hi everyone,</td>
					</tr>

					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>The Exit Formalities for the following employee has been successfully completed and for your necessary action</td>
					</tr>		
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:8px;'>Employee name & Emp. ID: <b>". $SenderNam ." - ". $SenderID ."</b></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:8px;'>Department: <b>". $SenderDepartment ."</b></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:8px;'>Separation Date: <b>". $dateofleaving ."</b></td>
					</tr>					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:8px;'>Exit Status: <b> Relieved </b></td>
					</tr>						
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards,</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:8px;'>Acurus Solutions</td>
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
			foreach ($emaildec as $allmail)
			{
				$mail->addAddress($allmail['allmail']);
			}
			 if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } 
            //send the message, check for errors
            
			else
			{
				echo "<script>alert('Process Completed for the Employee');</script>";
				if($usergrp == 'HR Manager')
				{
						header("Location: resignationprocessingform.php");
				}
				else
				{
						header("Location: noduehrform.php");
				}
			}
 
 ?>