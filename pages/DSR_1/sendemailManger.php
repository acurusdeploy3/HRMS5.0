<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$dateval = $_GET['dateval'];
//require_once('excel.php?dateval='.$dateval);



$getdate = mysqli_query($db,"select date_format('$dateval','%Y%m%d') as date1");
$getdaterow = mysqli_fetch_array($getdate);
$date1 = $getdaterow['date1'];
//$date1 = date("Ymd");

$date = date('d M Y');
$id = $_SESSION['login_user'];

$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");
$emailrecRow = mysqli_fetch_array($emailrec);
$contactmail=$emailrecRow['contact_email'];
$managermail=$emailrecRow['reporting_manager_contact_email'];


$emailhr  = mysqli_query ($db,"select value from application_configuration where module='DSR' and config_type='EMAIL' and parameter='SUPPORT_MAIL'");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$deptdetails = mysqli_query($db,"select concat(First_Name,'',MI,' ',Last_Name) as Name,department,reporting_manager_id from  employee_details  where employee_id=$id");
$deptdetailsrow = mysqli_fetch_array($deptdetails);
$department = $deptdetailsrow['department'];
$SenderNam = $deptdetailsrow['Name'];
$Repmgmr = $deptdetailsrow['reporting_manager_id'];
$selectmom = mysqli_query ($db,"SELECT official_email from employee_details where employee_id='$Repmgmr'");
$selectmomrow = mysqli_fetch_array($selectmom);
$MomEMail = $selectmomrow['official_email'];


$full_path_to_file='D:\DSR_DOWNLOADS\DSR_FILES\DSR_'.$date1.'_'.$department.'_'.$SenderNam.'&Team.xlsx';

	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

			  $recipients = 
			array(
				 $managermail  => $managermail,
				 $mailhr  => $mailhr, 
              	$MomEMail => $MomEMail,
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
            $mail->Subject = 'DSR_'.$date1.'_'.$department.'_'.$SenderNam.' & team' ;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:160px;height:60px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear All,</td>
					</tr>

					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Please find daily status report for <b>". $SenderNam ." - ".$id ."</b> & Team</td>
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
			$mail->AddAttachment($full_path_to_file,"DSR_".$date1."_".$department."_".$SenderNam."&Team.xls");
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
				$mail->AddCC($contactmail);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } 
			else
			{
				$finalupdate = mysqli_query($db,"update dsr_summary set is_completed='Y' where manager_id='$id' and date='$dateval' and is_approved='Y'");
				//echo "update dsr_summary set is_completed='Y' where manager_id='$name' and date='$dateval' and is_approved='Y'";
				header("Location: MyTeamDSRReports.php");
			}
 
 ?>