<?php 
include("config.php");
include("config1.php");
session_start();
$modifiedby = $_SESSION['login_user'];
$Emplid = $_POST['EmplID'];
$Status = $_POST['Status'];
$LastDate = $_POST['LastDate'];
$reason = $_POST['reason'];
error_reporting(E_ALL && ~E_NOTICE);
$sql0="insert into deactivated_employees select 0,now(),'$Status','$reason',e.*  from employee_Details e where employee_id='$Emplid'";
$result0=mysql_query("insert into deactivated_employees select 0,now(),'$Status','$reason',e.*  from employee_Details e where employee_id='$Emplid'");
$getApplicantID = mysql_query("select applicant_id,concat(First_Name,' ',Last_Name,' ',MI) as Name from employee_details where employee_id='$Emplid'");
$getApplicantIDRow = mysql_fetch_array($getApplicantID);
$ApplicantID = $getApplicantIDRow['applicant_id'];
$EmployeeName = $getApplicantIDRow['Name'];
$ApplicantID = @$ApplicantID;
$sql="update employee_Details set is_active='N',modified_by='$modifiedby',date_left='$LastDate' where employee_id='$Emplid'";
$result=mysql_query($sql);
$sql1="update resource_management_table set is_active='N' , modified_by='$modifiedby' where employee_id='$Emplid'";
$result1=mysql_query($sql1);
$sql2="update employee set ldw='$LastDate' where employee_id='$Emplid'";
$result2=mysql_query($sql2);
$result3 = mysql_query("delete FROM `employee_performance_review_dates` where employee_id='$Emplid'");
$result4 = mysql_query("update employee_resignation_information set is_active='N',status='Process_Completed',process_queue='HR_Manager_Process', exit_interview_status='C',no_due_sysadmin_status='C',no_due_acc_status='C',no_due_manager_status='C',no_due_hr_status='C' where is_active='Y' and employee_id ='$Emplid'");
$AD_server = "ldap://172.18.0.150"; 
		$ds = ldap_connect($AD_server);
		if ($ds) {
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
					$result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
					if (!$result)
						{
							echo 'Not Binded';
						}
						$userDn = "cn=".$Emplid.",ou=Users,dc=acurusldap,dc=com";
						ldap_Delete($ds,$userDn);
   		 ldap_close($ds);
			}
$emaildec = mysql_query("select contact_email as allmail from notification_contact_email where employee_id in (SELECT value FROM `application_configuration` where config_type='EMAIL' and module='EMPLOYEE_DEACTIVATION' and parameter='COMPLETION_MAIL')");
$date = date_create($LastDate);
$dateLDW = date_format($date,'d M Y');
$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password

 $recipients = 
			array(
                 $emailval => $emailval,
     
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
            $mail->Subject = 'Employee Deactivation : '.$EmployeeName.' : '.$Emplid;
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee <strong>".$EmployeeName." : ".$Emplid."</strong> has been relieved from Services in Acurus. This is for your kind information.</td>
                        
					</tr>
                    
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Last Date of Working : <strong>".$dateLDW."</strong></td>
                        
					</tr>
                    <tr>
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
            while($row = mysql_fetch_assoc($emaildec))
			{
				$mail->addAddress($row['allmail']);
			}
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
mysqli_query($db,"update applicant_tracker set date_of_joining='0001-01-01 00:00:00',status='Rejected' where applicant_id in (".$ApplicantID.")");
header("Location: SearchEmployee.php");
?>