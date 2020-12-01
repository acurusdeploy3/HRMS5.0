<?php
session_start();
require_once("queries.php");
$date = date("Y-m-d");
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');

		$rid = $_GET['rid'];

		$getempid= mysqli_query ($db,"SELECT employee_id FROM employee_resignation_information where resignation_id=$rid");
		$ememRow = mysqli_fetch_array($getempid);
		$id=$ememRow['employee_id'];
		$deptname= mysqli_query($db,"select department from employee_details where employee_id=$id");
		$emdepRow = mysqli_fetch_array($deptname);
		$deptnameval=$emdepRow['department'];
		$hodname= mysqli_query($db,"select employee_id from all_hods where department='$deptnameval'");
		$emdhodRow = mysqli_fetch_array($hodname);
		$loginvalue=$emdhodRow['employee_id'];
		
		
$getuserdet1= mysqli_query($db,"select distinct(contact_email) as contact_email from notification_contact_email where employee_id='$loginvalue'");
$emhodrow = mysqli_fetch_array($getuserdet1);
$mailhod=$emhodrow['contact_email'];


$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");

$emailhr  = mysqli_query ($db,"SELECT * FROM `application_configuration` where config_type='EMAIL' and parameter like 'HR_MAIL' and module='RESIGNATION'");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$emailcon =mysqli_query($db," SELECT reason_for_resignation,employee_comments,
reporting_manager_comments,process_queue,status,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds,hr_comments,hr_reason_update
FROM `employee_resignation_information`where employee_id=$id and resignation_id=$rid order by resignation_id desc limit 1 ");

$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Job_Role,reporting_manager_id,Gender FROM `employee_details`where employee_id=$id and is_active='Y' ");

$hrmandet=mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfHREmployee,
employee_id as hrempid FROM `employee_details`where job_role='HR Manager' and is_active='Y' ");
$hrrow= mysqli_fetch_array($hrmandet);

$emro= mysqli_fetch_array($emailcon);
$content = $emro['reason_for_resignation'];
$comments =$emro['employee_comments'];
$comments1 =$emro['reporting_manager_comments'];
$comments2 =$emro['hr_comments'];
$dateofsub =$emro['ds'];
$status1 =$emro['status'];
$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];
$Gender = $empro['Gender'];
if($Gender=='Male')
{
	$his='his';
}
else
{
	$his='her';
}
$repid=$empro['reporting_manager_id'];
$mngrdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfManager FROM `employee_details`where employee_id=$repid and is_active='Y' ");
$mngrpro= mysqli_fetch_array($mngrdet);
$repname=$mngrpro['NameOfManager'];
 
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['contact_email'];
$repemailval1 = $emRow['reporting_manager_contact_email'];
$mangerval = mysqli_query($db,"select contact_email from  notification_contact_email where employee_id in (SELECT reporting_manager_id FROM `employee_resignation_information` where employee_id=$id)");
$mngrrow = mysqli_fetch_array($mangerval);
$repemailval = $mngrrow['contact_email'];
	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
                 $mailhr => $mailhr,
				 $repemailval  => $repemailval,
				 $emailval => $emailval,
				 $mailhod => $mailhod,
                 
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
            $mail->Subject = 'AHRMS - Exit - Resignation Cancellation Mail - '.$SenderNam.' - '.$id;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Hi Everyone,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>As per the discussion with the employee $SenderNam on $date about $his resignation request $his concern will be addressed . Hence , a request is placed to withdraw $his resignation.</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>". $repname ." - ". $repid ."</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>". $repemailval ."</td>
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
				$getuserdet= mysqli_query($db,"select * from all_hods where employee_id='$loginvalue'");
				if(mysqli_num_rows($getuserdet) > 0)
				{
					header("Location: departmentresignationform.php");
				}
				else if($usergrp == 'HR Manager'){
				header("Location: resignationprocessingform.php");
				}
				else
				{header("Location: resignationapprovalform.php");}
			
			}
 
 ?>