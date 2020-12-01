<?php
//require_once("queries.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];

include('config.php');
include('Attendance_Config.php');
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$name'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$Req = $_GET['id'];
$History = mysqli_query($db,"insert into history_leave_request SELECT 0,now(),e.*  FROM leave_request e where req_id = '$Req'");
mysqli_query($db,"update leave_request set status='Leave Canceled',is_approved='',is_active='N',is_canceled='Y',date_of_cancelation=now() where req_id='$Req';");
mysqli_query($db,"update fyi_transaction set message = replace(message,' Granted',' Cancelled') where module_id='$Req' and module_name='Attendance Management'");
$getData = mysqli_query($db,"select employee_id,leave_type,leave_from,leave_to,number_of_days,is_combined_leave,combination_1,combination_2,leave_for,tracker_id,comp_off_tracker,reason from leave_request where req_id='$Req'");
$getDataRow = mysqli_fetch_array($getData);
$EmpID = $getDataRow['employee_id'];
$LeaveType = $getDataRow['leave_type'];
$leaveFrom = $getDataRow['leave_from'];
$leaveTo = $getDataRow['leave_to'];
$NumDays = $getDataRow['number_of_days'];
$isCombined = $getDataRow['is_combined_leave'];
$FirstComb = $getDataRow['combination_1'];
$SecComb = $getDataRow['combination_2'];
$leaveFor = $getDataRow['leave_for'];
$trackerid = $getDataRow['tracker_id'];
$compofftracker = $getDataRow['comp_off_tracker'];
$reason = $getDataRow['reason'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$EmpID'");
$getNameRow = mysqli_fetch_array($getName);
$Name = $getNameRow['name'];
if($LeaveType=='Casual')
{
	$LeaveTypeAb='CL';
}
if($LeaveType=='Privilege')
{
	$LeaveTypeAb='PL';
}
if($LeaveType=='Sick')
{
	$LeaveTypeAb='SL';
}
if($LeaveType=='Compensatory-Off')
{
	$LeaveTypeAb='Comp_Off';
}
if($LeaveType=='On-Duty')
{
	$LeaveTypeAb='OD';
}
if($LeaveType=='Maternity')
{
	$LeaveTypeAb='ML';
}
if($LeaveType=='Work from Home')
{
	$LeaveTypeAb='WFH';
}
if($leaveFor=='')
{
	$leaveFor='--';
}
if($LeaveType=='Casual & Sick')
{
	$LeaveTypeAb='CL & SL';
}

if($LeaveType=='Privilege & Sick')
{
	$LeaveTypeAb='PL & SL';
}
if($LeaveType=='WeekOff')
{
	$LeaveTypeAb='WO';
}
if($LeaveType=='FlexiShift')
{
	$LeaveTypeAb='Flexible Time';
}
if($LeaveType=='FlexiShift')
{
mysqli_query($att,"delete from flexi_in_out_time where id in ($trackerid)");
}

if($LeaveType=='WeekOff')
{
mysqli_query($att,"delete from flexi_weekoff where id in ($trackerid)");
}
if($isCombined=='N')
{
		if($LeaveTypeAb!='Comp_Off')
				{
					mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_taken=".$LeaveTypeAb."_taken - $NumDays , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing + $NumDays where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");
				}
				else
				{
					
					if($NumDays=='0.5')
					{
						$CheckActive  = mysqli_query($att,"select * from comp_off_tracker where is_availed='Y' and id in ($compofftracker)");
						if(mysqli_num_rows($CheckActive)>0)
						{
							mysqli_query($att,"update comp_off_tracker set is_availed='N' where id in  ($compofftracker)");
						}
						else
						{
							mysqli_query($att,"update comp_off_tracker set unit=unit+0.5 where id in ($compofftracker)");	
						}
					}
					else
					{
						mysqli_query($att,"update comp_off_tracker set is_availed='N' where id in ($compofftracker)");
					}
					mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_availed=".$LeaveTypeAb."_availed - $NumDays , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing + $NumDays where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");	
				}
}
else
{
	if($LeaveType=='Casual & Sick')
	{
		mysqli_query($att,"update employee_leave_tracker set cl_taken=cl_taken - $FirstComb , cl_closing=cl_closing + $FirstComb where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");
		mysqli_query($att,"update employee_leave_tracker set sl_taken=sl_taken - $SecComb , sl_closing=sl_closing + $SecComb where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");
	}
	else
	{
		mysqli_query($att,"update employee_leave_tracker set pl_taken=pl_taken - $FirstComb , pl_closing=pl_closing + $FirstComb where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");
		mysqli_query($att,"update employee_leave_tracker set sl_taken=sl_taken - $SecComb , sl_closing=sl_closing + $SecComb where year=year(curdate()) and month=month(curdate()) and employee_id='$EmpID'");
	}
}
if($LeaveType=='Permission')
{
$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where employee_id='$EmpID'");
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
            $mail->Subject = 'AHRMS : Attendance Management : '.'Permission Request Canceled';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$Name.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$EmpName.' : '.$name." has Canceled Your Permission Request.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Leave Type : <strong>". $LeaveType." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Number of Hours: <strong>". 
$NumberHours." Hour(s)</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Permission Type: <strong>". 
$leaveFor."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Permission Required Date  : <strong>". 
$leaveFrom." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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

}
else
{
	$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where employee_id='$EmpID'");
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
            $mail->Subject = 'AHRMS : Attendance Management : '.$LeaveTypeAb.' Canceled ';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$Name.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$EmpName.' : '.$name." has canceled your leave.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Request Type : <strong>". $LeaveType." (".$LeaveTypeAb.") </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Number of Days: <strong>". 
$NumDays." Day(s)</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Request For: <strong>". 
$leaveFor."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required From  : <strong>". 
$leaveFrom." </strong></td>
					</tr>
					
						<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required To  : <strong>". 
$leaveTo." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
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

}


mysqli_query($att,"update leave_status set cancled='Y',cancled_by='$EmpName',cancled_date_time=now() where tracker_id in ($trackerid)");
	header("Location: TeamLeaveRequest.php");
?>