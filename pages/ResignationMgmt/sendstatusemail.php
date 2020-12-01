<?php
require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
//$loginvalue = $_SESSION['login_user'];
include('config.php');

		$rid = $_GET['rid'];
		$getempid= mysqli_query ($db,"SELECT employee_id,substring_index(pending_queue_id,'-',-1) as queuename FROM employee_resignation_information where resignation_id=$rid");
		$ememRow = mysqli_fetch_array($getempid);
		$id=$ememRow['employee_id'];
		$queuename=$ememRow['queuename'];
		
$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");

$emailhr  = mysqli_query ($db,"SELECT * FROM `application_configuration` where config_type='EMAIL' and parameter like 'HR_MAIL' and module='RESIGNATION' ");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$emailcon =mysqli_query($db," SELECT resignation_id,reason_for_resignation,status,employee_comments,
reporting_manager_comments,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds
FROM `employee_resignation_information`where employee_id=$id and resignation_id = $rid and is_active='Y' order by resignation_id desc limit 1 ");

$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Job_Role,reporting_manager_id FROM `employee_details`where employee_id=$id and is_active='Y' ");

$emro= mysqli_fetch_array($emailcon);
$content = $emro['reason_for_resignation'];
$comments =$emro['employee_comments'];
$comments1 =$emro['reporting_manager_comments'];
$dateofsub =$emro['ds'];
$resignation_id=$emro['resignation_id'];
$status1 =$emro['status'];
$empro= mysqli_fetch_array($empdet);
$SenderNam = $empro['NameOfEmployee'];
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
$deptname= mysqli_query($db,"select department from employee_details where employee_id=$id");
$emdepRow = mysqli_fetch_array($deptname);
$deptnameval=$emdepRow['department'];

$hodname= mysqli_query($db,"select employee_id from all_hods where department='$deptnameval'");
$emdhodRow = mysqli_fetch_array($hodname);
$loginvalue=$emdhodRow['employee_id'];
	
$getuserdet1= mysqli_query($db,"select distinct(contact_email) as contact_email from notification_contact_email where employee_id='$loginvalue'");
$emhodrow = mysqli_fetch_array($getuserdet1);
$mailhod=$emhodrow['contact_email'];

	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
				 $repemailval  => $repemailval,
				 $emailval => $emailval,  
				 $mailhod => $mailhod,
				 $mailhr => $mailhr,
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
            $mail->Subject = 'AHRMS - Exit - Resignation Request Status - '.$SenderNam.' - '.$id ;
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'><b>Employee Details</b></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:8px;'>". $SenderNam ." - ". $id ."</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:8px;'>". $emailval ."</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'> Date Of Submission of Resignation: ". $dateofsub ."</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'><b>Status for the request of Resignation: ". $status1 ."</b></td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Regards,</td>
					</tr>
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Acurus Solutions.</td>
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
			$sql= mysqli_query($db,"select contact_email from  notification_contact_email where employee_id in (select value from application_configuration where parameter='HR_MNGR_ID' and module='All')");
			foreach($sql as $row)
			{
				 $mail->addAddress($row['contact_email']);
			}
			$sql2 = mysqli_query($db,"select contact_email from  notification_contact_email where employee_id in (select substring_index(pending_queue_id,'-',1) from employee_resignation_information where
			employee_id=$id and resignation_id = $rid and is_active='Y')");
			foreach($sql2 as $row1)
			{
				 $mail->addAddress($row1['contact_email']);
			}
			
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } 
			else
			{	
				$getuserdet= mysqli_query($db,"select * from all_hods where employee_id='$name'");
				if(mysqli_num_rows($getuserdet) > 1){	
					if($queuename == 'rep')
					{						
					header("Location: resignationapprovalform.php");
					}
					else
					{
						header("Location: departmentresignationform.php");
					}
					}
				else
				{
					header("Location: resignationapprovalform.php");
					//echo "hi";
				}
			}
 
 ?>