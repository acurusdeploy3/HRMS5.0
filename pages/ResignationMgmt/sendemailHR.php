<?php
session_start();

$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$date = date("d M Y");
include('config.php');

		$id = $_SESSION['login_user'];

		$notid=$_GET['notid'];
$emailrec  = mysqli_query ($db,"SELECT distinct(contact_email) as contact_email,reporting_manager_contact_email FROM notification_contact_email where employee_id=$id");


$emailhr  = mysqli_query ($db,"SELECT * FROM `application_configuration` where config_type='EMAIL' and parameter like 'HR_MAIL' and module='RESIGNATION' ");
$emhrRow = mysqli_fetch_array($emailhr);
$mailhr=$emhrRow['value'];

$emailcon =mysqli_query($db," SELECT reason_for_resignation,status,employee_comments,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds FROM `employee_resignation_information`where employee_id=$id and is_active='Y' order by resignation_id desc limit 1 ");

$empdet= mysqli_query($db," SELECT concat(First_name,' ',MI,' ',Last_Name) as NameOfEmployee,Job_Role,Gender FROM `employee_details`where employee_id=$id and is_active='Y' ");

$emro= mysqli_fetch_array($emailcon);
$content = $emro['reason_for_resignation'];
$comments =$emro['employee_comments'];
$dateofsub =$emro['ds'];

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

$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['contact_email'];
$repemailval = $emRow['reporting_manager_contact_email'];
$deptname= mysqli_query($db,"select department,round(datediff(date(now()),date_joined)/365,1) as nofyr,substring_index(datediff(date(now()),date_joined)/365,'.',1) as yrs,substring_index(round(datediff(date(now()),date_joined)/365,1),'.',-1) as mnths from employee_details where employee_id=$id");
$emdepRow = mysqli_fetch_array($deptname);
$deptnameval=$emdepRow['department'];
$nofyr=$emdepRow['nofyr'];
$yrs=$emdepRow['yrs'];
$mnths=$emdepRow['mnths'];
if($nofyr>=4.6){
$showvalue = "**Note:";
$todisplayval = " This employee is eligible for gratuity";
$subjval="Completed 4.6 + of services ";
$contentval =" and has completed ".$yrs." years ".$mnths." month(s) of service as of ".$date;

}

$hodname= mysqli_query($db,"select employee_id from all_hods where department='$deptnameval'");
$emdhodRow = mysqli_fetch_array($hodname);
$loginvalue=$emdhodRow['employee_id'];
	
$getuserdet1= mysqli_query($db,"select distinct(contact_email) as contact_email from notification_contact_email where employee_id='$loginvalue'");
$emhodrow = mysqli_fetch_array($getuserdet1);
$mailhod=$emhodrow['contact_email'];
$hradminmailval='karthika.ej@acurussolutions.com';

echo $mailhod;

if($notid=='Y')
{
$query3 ="Update employee_resignation_information set is_notification_sent='Y' where employee_id=$id and is_active='Y' order by resignation_id desc limit 1 ";
$result3=mysqli_query($db,$query3);
}
	
	//$db_handle = new DBController();
			$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
	if($usergrp == 'HR Manager'){
		$recipients = 
			array(
                 $repemailval => $repemailval,
				 $mailhod => $mailhod,
        $hradminmailval => $hradminmailval,
            );
	}
	else{
			  $recipients = 
			array(
                 $mailhr => $mailhr,
				 $mailhod => $mailhod,
              	$hradminmailval => $hradminmailval,
                 
            );
	}
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
            $mail->Subject = 'AHRMS - Exit - Request for Resignation - '.$SenderNam.' - '.$id.' - '.$subjval;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear All,</td>
					</tr>

					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$id." - ".$SenderNam ." has submitted $his <b>Resignation Letter</b> on ". $dateofsub . $contentval.".</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Reason for Resignation : <b>". $content ."</b></td>
					</tr>
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:8px; padding-top:15px;'>Employee Comments : <b>". $comments ."</b></td>
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
			foreach ($recipients as $address => $name) {
                $mail->addAddress($address, $name);
             }
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } 
			else
			{
				header("Location: employeeresignationform.php"); 
			}
 
 ?>