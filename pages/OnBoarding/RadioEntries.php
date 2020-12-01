<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$createduserval = $_SESSION['login_user'];
include('config2.php');
include('Attendance_Config.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_SESSION['Employee_id'];
$WindowsLogin = $_POST['WindowsLogin'];
$OfficeMail = $_POST['OfficeMail'];
$EmployeeShift = $_POST['EmployeeShift'];
$CPPEligible = $_POST['CPPEligible'];
$ISSSAEligible = $_POST['ISSSAEligible'];
$IsExecutive = $_POST['IsExecutive'];
$SL = $_POST['SL'];
$CL = $_POST['CL'];
$PL = $_POST['PL'];
$ISPLEligible = $_POST['ISPLEligible'];
$ISCompEligible = $_POST['ISCompEligible'];
$ISNSAEligible = $_POST['ISNSAEligible'];
$InTime = $_POST['InTime'];
$OutTime = $_POST['OutTime'];
$Radio2='Yes';
$Radio4='Yes';
if($WindowsLogin=='')
{
	$Radio2 = 'No';
	$Radio4 = 'No';
}
$Radio3 = $_POST['r3'];
$Radio1 = $_POST['r1'];

date_default_timezone_set('Asia/Kolkata');
$getEmploymentDetails = mysqli_query($db,"select department,job_role,date_joined,business_unit,reporting_manager_id,
backup_manager_id,First_name as Name from employee_details where employee_id='$emplid'");
$getEmploymentDetailsRow = mysqli_fetch_array($getEmploymentDetails);
$department = $getEmploymentDetailsRow['department'];
$date_joined = $getEmploymentDetailsRow['date_joined'];
$business_unit = $getEmploymentDetailsRow['business_unit'];
$reporting_manager_id = $getEmploymentDetailsRow['reporting_manager_id'];
$backup_manager_id = $getEmploymentDetailsRow['backup_manager_id'];
$Name = $getEmploymentDetailsRow['Name'];
$job_role = $getEmploymentDetailsRow['job_role'];
if($backup_manager_id=='')
{
	$backup_manager_id=0;
}

$getBU = mysqli_query($db,"select value from all_business_units where business_unit='$business_unit'");
$getBURow = mysqli_fetch_array($getBU);
$BU = $getBURow['value'];
$getRoleResp = mysqli_query($db,"select * from all_job_roles where role_desc='$job_role' and role_resp='Manager'");
if(mysqli_num_rows($getRoleResp)>0)
{
	$Resp = 'Manager';
}
else
{
	$Resp = 'Employee';
}
$CheckinTime=date_create($InTime);
$CheckoutTime=date_create($OutTime);
$CheckinFormatted =  date_format($CheckinTime,"H:i:s");
$CheckoutFormatted =  date_format($CheckoutTime,"H:i:s");
$Checkin = $date_joined.' '.$CheckinFormatted;
$Checkout = $date_joined.' '.$CheckoutFormatted;

$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_biometric_authorized='$Radio3',is_id_issued='$Radio1',is_login_created='$Radio2',is_system_allocated='$Radio4' where
employee_id='$emplid'");
$res = mysqli_query($att,"CALL New_Employee_Create('$emplid','$date_joined','$department','$reporting_manager_id','$backup_manager_id','$BU','$ISPLEligible','$ISCompEligible','$CPPEligible','$EmployeeShift','$Resp','$Name','$SL','$PL','$CL','$ISNSAEligible','$ISSSAEligible','$IsExecutive','$createduserval')");
if($department=='RCM' || $department=='PUBLISHING' || $department=='HIM')
{
	$CheckifPMSEntered = mysqli_query($att,"select * from pms_manager_lookup where employee_id='$emplid'");
	if(mysqli_num_rows($CheckifPMSEntered)==0)
	{
			$insertPMSTable = mysqli_query($att,"insert into pms_manager_lookup (employee_id,department,manager_id)
												values
										  ('$emplid','$department','$backup_manager_id');");
	}
	else
	{
		$updatePMSTable = mysqli_query($att,"update pms_manager_lookup set 
										department='$department',manager_id='$backup_manager_id' 
										where employee_id='$emplid'");
	}
}
else
{
	$CheckifPMSEntered = mysqli_query($att,"select * from pms_manager_lookup where employee_id='$emplid'");
	if(mysqli_num_rows($CheckifPMSEntered)==0)
	{
			$insertPMSTable = mysqli_query($att,"insert into pms_manager_lookup (employee_id,department,manager_id)
												values
												('$emplid','$department','$reporting_manager_id');");
	}
	else
	{
		$updatePMSTable = mysqli_query($att,"update pms_manager_lookup set 
										department='$department',manager_id='$reporting_manager_id' where 
										employee_id='$emplid'");
	}
}
echo "CALL New_Employee_Create('$emplid','$date_joined','$department','$reporting_manager_id','$backup_manager_id','$BU','$ISPLEligible','$ISCompEligible','$CPPEligible','$EmployeeShift','$Resp','$Name','$SL','$PL','$CL','$ISNSAEligible','$ISSSAEligible','$IsExecutive','$createduserval')";

if($Radio1 == 'No' || $Radio2 =='No' || $Radio3 =='No' || $Radio4 =='No')
	{
		$isFormComplete = 'N';
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='N' where
		employee_id='$emplid'");
	}
else
	{
		$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_provisions_completed='Y' where
		employee_id='$emplid'");
	}
$getStatus = mysqli_query($db,"select are_documents_uploaded,is_provisions_completed,is_designated,attendance_required,is_data_sheet_completed
from employee_boarding
where employee_id='$emplid'");
$getCompStatus = mysqli_fetch_array($getStatus);
$getDocStatus = $getCompStatus['are_documents_uploaded'];
$getEmpStatus = $getCompStatus['is_designated'];
$getProvStatus = $getCompStatus['is_provisions_completed'];
$getDataStatus = $getCompStatus['is_data_sheet_completed'];
$attendancestatus = $getCompStatus['attendance_required'];
$UpdateCheckin = mysqli_query($att,"update attendance set check_in = '$Checkin' where shift_date='$date_joined' and employee_id='$emplid'");
if($getDataStatus=='N' || $getEmpStatus == 'N' || $getProvStatus=='N')
{	
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set is_formalities_completed='P' where
		employee_id='$emplid'");	
}
$nithinmail = 'nithin.rs@acurussolutions.com';
if($attendancestatus=='Yes' && $Radio5=='Yes')
{
$UpdateEmployee = mysqli_query($db,"update employee_boarding set attendance_required='Yes' where
		employee_id='$emplid'");	
}
elseif($attendancestatus=='No' && $Radio5=='Yes')
{
	
		$getadminteam = mysqli_query($db,"select official_email from employee_details where job_role in ('System Admin Manager','System Admin')");
	
			$UpdateEmployee = mysqli_query($db,"update employee_boarding set attendance_required='Yes' where
			employee_id='$emplid'");
			$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where job_role ='System Admin Manager'");
		$emRow = mysqli_fetch_array($emailrec);
		$emailval = $emRow['official_email'];
		$content = $_POST['NotVal'];

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
            $mail->Subject = 'AHRMS - Attendance Portal';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
			<div>
			<img src='acurus-logo.png' align='right' alt='logo' style='width:184px; margin:0 auto; align:right; display:block;'>
			</div>
		<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
		<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Admin Team,</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Kindly Add the Following User Login in Attendance Portal Group.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee ID : <strong>". $emplid." </strong></td>
					</tr>
					
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>

					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Acurus HR.</td>
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
		
}
else
{
	$UpdateEmployee = mysqli_query($db,"update employee_boarding set attendance_required='No' where
		employee_id='$emplid'");
	
}
	