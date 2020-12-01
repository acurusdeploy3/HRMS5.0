<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$getRepMgmr = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$name'");

$getRepMgmrRow = mysqli_fetch_array($getRepMgmr);
$RepMgmr = $getRepMgmrRow['reporting_manager_id'];
$EmpName = $getRepMgmrRow['name'];
$getRepMgmrName = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$RepMgmr'");
$getRepMgmrNameRow = mysqli_fetch_array($getRepMgmrName);
$RepMgmrName = $getRepMgmrNameRow['name'];



$LeaveType = $_POST['EmployeeLeave'];
$NumDays = $_POST['NumberDays'];
$dateFrom =$_POST['dateFrom'];
if($NumDays>1)
{
	$leavefor ='';
}
else
{
	$leavefor = $_POST['LeaveFor'];
}

$dateTo =$_POST['dateTo'];
$reason = mysqli_real_escape_string($db,$_POST['reason']);
$PermissionType =$_POST['PermissionType'];
$NumberHours =$_POST['NumberHours'];
$SLCombinationSLPL =$_POST['SLPLCntSL'];
$PLCombinationSLPL =$_POST['SLPLCntPL'];
$CLCombinationSLCL =$_POST['SLCLCntCL'];
$SLCombinationSLCL =$_POST['SLCLCntSL'];

		
if($LeaveType=='Casual & Sick')
{
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to,is_combined_leave,combination_1,combination_2)
values
('$name','$RepMgmr','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo','Y','$CLCombinationSLCL','$SLCombinationSLCL');");
}
if($LeaveType=='Privilege & Sick')
{
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to,is_combined_leave,combination_1,combination_2)
values
('$name','$RepMgmr','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo','Y','$PLCombinationSLPL','$SLCombinationSLPL');");
}
if($LeaveType=='Permission')
{
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to)
values
('$name','$RepMgmr','$LeaveType','$NumberHours','Request Sent : Awaits Manager Action','$PermissionType',now(),'$name','$reason','$dateFrom','0001-01-01');");
}
if($LeaveType=='On-Duty')
{
	if($reason!='')
	{
		$ODReason = $_POST['OnDutyReason'].' - '.$reason;
	}
	else
	{
		$ODReason = $_POST['OnDutyReason'];
	}
	$ODDATE = date('Y-m-d');
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to)
values
('$name','$RepMgmr','On-Duty','1','Request Sent : Awaits Manager Action','Full Day',now(),'$name','$ODReason','$ODDATE','$ODDATE');");
}
if ($LeaveType!='Permission' && $LeaveType!='Privilege & Sick' && $LeaveType!='Casual & Sick' && $LeaveType!='On-Duty')
{
		mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to)
values
('$name','$RepMgmr','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo');");
}
	



echo "insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason)
values
('$name','$RepMgmr','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason')";

if($leavefor=='')
{
	$leavefor='--';
}
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
if($LeaveType=='Casual & Sick')
{
	$LeaveTypeAb='CL & SL';
}

if($LeaveType=='Privilege & Sick')
{
	$LeaveTypeAb='PL & SL';
}
if($LeaveType=='Permission')
{
	$LeaveTypeAb ='Permission';
}
//send Mail

$reqId = mysqli_insert_id($db);	

if($dateFrom == $dateTo)
{
	$dateFromDate = date_create($dateFrom); 
	$Leavedate = date_format($dateFromDate,'d M Y');
	$LeavedateText= $LeaveTypeAb.' Requested ( Date : '.$Leavedate.' )';
}
else
{
	$dateFromDate = date_create($dateFrom); 
	$dateToDate = date_create($dateTo);
	$dateFromDate = date_format($dateFromDate,'d M Y');
	$dateToDate = date_format($dateToDate,'d M Y');
	$LeavedateText = $LeaveTypeAb.' Requested ( Date : '.$dateFromDate.' - '.$dateToDate.' )';
}

mysqli_query($db,"insert into fyi_transaction (employee_id,employee_name,transaction,module_id,module_name,message,date_of_message,created_date_and_time,created_by)
values
('$name','$EmpName','Attendance Management','$reqId','Attendance Management','$LeavedateText',curdate(),now(),'$name');");



if($LeaveType=='Permission')
{
$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where employee_id='$RepMgmr'");
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
            $mail->Subject = 'AHRMS : Attendance Management : Permission Request : '.$EmpName.' : '.$name;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$RepMgmrName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$EmpName.' : '.$name." has Requested for Permission.</td>
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
$PermissionType."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Permission Required Date  : <strong>". 
$dateFrom." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
					</tr>
                    
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Link to Update : &nbsp;<a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>https://ahrms.acurussolutions.com</a></td>
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

	$name = $_SESSION['login_user'];
	$OtherStakeArr = mysqli_query($db,"select a.employee_id,receipent,official_email from additional_Receipents a join employee_details e on a.receipent=e.employee_id where a.employee_id=$name union select employee_id,name,email from additional_stakeholders where employee_id=$name;");	
	if(mysqli_num_rows($OtherStakeArr)>0)
	{
		$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
			$name = $_SESSION['login_user'];
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
            $mail->Subject = 'AHRMS : Attendance Management : Permission Request : '.$EmpName.' : '.$name;
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>The following Employee has raised a Permission request. The details of the request are as follows. This is just for your kind information.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee Name & ID : <strong>".$EmpName.' : '.$name."</strong>.</td>
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
$PermissionType."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Permission Required Date  : <strong>". 
$dateFrom." </strong></td>
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
			foreach ($OtherStakeArr as $row)
					{
							$mail->addAddress($row['official_email']);
							$totStake .=$row['official_email'].'-'; 
					}
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
		
	}



}
else
{
	if($LeaveTypeAb=='OD')
    	{
        	$reason = $ODReason;
    		$dateFrom  = $ODDATE;
   			$dateTo = $ODDATE;
        }
	$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where employee_id='$RepMgmr'");
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
            $mail->Subject = 'AHRMS : Attendance Management : '.$LeaveTypeAb.' Request : '.$EmpName.' : '.$name;
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$RepMgmrName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$EmpName.' : '.$name." has requested the following.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Request Type : <strong>". $LeaveType." (".$LeaveTypeAb.") </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Number of Days: <strong>". 
$NumDays." Day(s)</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested For: <strong>". 
$leavefor."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required From  : <strong>". 
$dateFrom." </strong></td>
					</tr>
					
						<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required To  : <strong>". 
$dateTo." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Reason  : <strong>". $reason." </strong></td>
					</tr>
					<tr style='background-color:#fff;'>
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Link to Update : &nbsp;<a href='https://ahrms.acurussolutions.com:2047/AHRMS/pages/forms/Mainlogin.php'>https://ahrms.acurussolutions.com</a></td>
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

	$name = $_SESSION['login_user'];
	$OtherStakeArr = mysqli_query($db,"select a.employee_id,receipent,official_email from additional_Receipents a join employee_details e on a.receipent=e.employee_id where a.employee_id=$name union select employee_id,name,email from additional_stakeholders where employee_id=$name;");	
	if(mysqli_num_rows($OtherStakeArr)>0)
    {
		if($LeaveTypeAb=='OD')
        {
        	$Context = 'On-Duty';
        	$reason = $ODReason;
        }
    	else
        {
        	$Context = 'Leave';
        }
		$senderName = 'Acurus HRMS'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
			$name = $_SESSION['login_user'];
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
            $mail->Subject = 'AHRMS : Attendance Management : '.$LeaveTypeAb.' Request : '.$EmpName.' : '.$name;
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>The following Employee has raised a ".$Context." request. The details of the request are as follows. This is just for your kind information.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Employee Name & ID : <strong>".$EmpName.' : '.$name."</strong>.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Request Type : <strong>". $LeaveType." (".$LeaveTypeAb.") </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Number of Days: <strong>". 
$NumDays." Day(s)</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Requested For: <strong>". 
$leavefor."</strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required From  : <strong>". 
$dateFrom." </strong></td>
					</tr>
					
						<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Required To  : <strong>". 
$dateTo." </strong></td>
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
			foreach ($OtherStakeArr as $row)
					{
							$mail->addAddress($row['official_email']);
							$totStake .=$row['official_email'].'-'; 
					}
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
		
	}

}
mysqli_query($db,"update leave_request set additional_informers='".$totStake."' where req_id='$reqId'");
header("Location: LeaveRequest.php");
?>