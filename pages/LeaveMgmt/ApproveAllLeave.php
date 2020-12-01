<?php
//require_once("queries.php");
include('config.php');
include("Attendance_Config.php");
session_start();
require 'PHPMailerAutoload.php';
$mail = new PHPMailer();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$_SESSION['appall'] ='appall';
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$name'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];


$emailrec  = mysqli_query ($db,"SELECT official_email FROM `employee_details` where employee_id='$EmpID'");
$emRow = mysqli_fetch_array($emailrec);
$emailval = $emRow['official_email'];
 $content = $_POST['NotVal'];
$getAllemails = mysqli_query($db,"select official_email, employee_id from employee_details where employee_id in ( select distinct employee_id from leave_request where allocated_to='$name' and is_active='Y')");
while($emailrows = mysqli_fetch_assoc($getAllemails))
{
$emailval = $emailrows['official_email'];
$getleaveDates = mysqli_query($db,"select leave_from,leave_to,leave_type from leave_request where employee_id='".$emailrows['employee_id']."' and is_active='Y'");
$finalleaves='';
$getNameap = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='".$emailrows['employee_id']."'");
$getNameapRow = mysqli_fetch_array($getNameap);
$EmpNameap = $getNameapRow['name'];
$it=1;

while($leavedates = mysqli_fetch_assoc($getleaveDates))
{
	if($leavedates['leave_from']==$leavedates['leave_to'] || $leavedates['leave_to']=='0001-01-01')
	{
 		$newDate = date("d M Y", strtotime($leavedates['leave_from']));
		$dat=" ".$newDate." ";
	}
	else
	{
 		$newDate = date("d M Y", strtotime($leavedates['leave_from']));
 		$newDate1 = date("d M Y", strtotime($leavedates['leave_to']));
			 $dat = " ".$newDate." - ".$newDate1." ";
	}
	$finalleaves .=$it.'. '.$dat.'.<br>';
$it++;
}
$senderName = 'notifications@acurussolutions.com'; //Enter the sender name
            $username = 'notifications@acurussolutions.com'; //Enter your Email
            $password = 'ukkupzzernjykeap';// Enter the Password
$mail->ClearAllRecipients(); 
 $recipients = 
			array(
                 $emailval => $emailval,
     
            );
			


            // Set up SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.bizmail.yahoo.com";
            $mail->Port       = 465; // we changed this from 486
            $mail->Username   = $username;
            $mail->Password   = $password;
			$mail->Timeout    = 80;

            // Build the message
            $mail->Subject = 'AHRMS : Attendance Management : '.'Request Approved';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$EmpNameap.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your request(s) for the following dates has been Approved.</td>
					</tr>
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>".$finalleaves."</td>
					</tr>
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Regards,</td>
					</tr>
                    <tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>AHRMS Support.</td>
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
unset($recipients);

}


$name = $_SESSION['login_user'];
$getAallReqIDs = mysqli_query($db,"select req_id from leave_request where allocated_to='$name' and is_active='Y'");
while($rowIDS = mysqli_fetch_assoc($getAallReqIDs))
{
$Req = $rowIDS['req_id'];
$History = mysqli_query($db,"insert into history_leave_request SELECT 0,now(),e.*  FROM leave_request e where req_id = '$Req'");
$getData = mysqli_query($db,"select employee_id,leave_type,leave_from,leave_to,number_of_days,is_combined_leave,combination_1,combination_2,leave_for,reason from leave_request where req_id='$Req'");
$getDataRow = mysqli_fetch_array($getData);
$EmpID = $getDataRow['employee_id'];
$LeaveType = $getDataRow['leave_type'];
$_SESSION['leaveType'] = $LeaveType;
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$EmpID'");
$getNameRow = mysqli_fetch_array($getName);
$Name = $getNameRow['name'];
$LeaveType = $getDataRow['leave_type'];
$leaveFrom = $getDataRow['leave_from'];
$leaveTo = $getDataRow['leave_to'];
$NumDays = $getDataRow['number_of_days'];
$isCombined = $getDataRow['is_combined_leave'];
$FirstComb = $getDataRow['combination_1'];
$SecComb = $getDataRow['combination_2'];
$leaveFor = $getDataRow['leave_for'];
$reason = $getDataRow['reason'];
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
if($leaveFor=='Half-Day (Second Half)' || $leaveFor=='Half-Day (First Half)')
	{
			if($leaveFor=='Half-Day (Second Half)')
			{
				$Remarks = "SH";
			}
			else
			{
				$Remarks = "FH";
			}
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveTypeAb','$leaveFrom','$EmpName',now(),'0','Half Day','$Remarks','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				if($LeaveTypeAb!='Comp_Off')
				{
					mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_taken=".$LeaveTypeAb."_taken + 0.5 , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				}
				else
				{
					
					$getCompOffEntry=mysqli_query($att,"SELECT id FROM `comp_off_tracker` where employee_id='$EmpID' and unit='0.5' and is_availed='N';");
				
					if(mysqli_num_rows($getCompOffEntry)==1)
					{
						$getCompOffEntryRow = mysqli_fetch_array($getCompOffEntry);
						$UpdateID = $getCompOffEntryRow['id'];
						$carr[]=$UpdateID;
					   mysqli_query($att,"update comp_off_tracker set is_availed='Y' where employee_id='$EmpID' and unit='0.5' and is_availed='N'");	
					}
					
					elseif(mysqli_num_rows($getCompOffEntry)>1)
					{
						$getquicktoExpire = mysqli_query($att,"SELECT id FROM `comp_off_tracker` where employee_id='$EmpID' and unit='0.5' and is_availed='N' order by expiry_date asc limit 1");
						$getquicktoExpireRow = mysqli_fetch_array($getquicktoExpire);
						$QuicktoExpireID = $getquicktoExpireRow['id'];
						$carr[]=$QuicktoExpireID;
						mysqli_query($att,"update comp_off_tracker set is_availed='Y' where id='$QuicktoExpireID'");	
					}
					else
					{
						$getquicktoExpire = mysqli_query($att,"SELECT id FROM `comp_off_tracker` where employee_id='$EmpID' and is_availed='N' order by expiry_date asc limit 1");
						$getquicktoExpireRow = mysqli_fetch_array($getquicktoExpire);
						$QuicktoExpireID = $getquicktoExpireRow['id'];
						$carr[]=$QuicktoExpireID;
						mysqli_query($att,"update comp_off_tracker set unit=unit-0.5 where id='$QuicktoExpireID'");	
					}
					mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_availed=".$LeaveTypeAb."_availed + 0.5 , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");	
				}
	}

if($leaveFor=='Full Day' && $NumDays==1)
	{
		if($LeaveType=='Casual & Sick')
		{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','CL','$leaveFrom','$EmpName',now(),'0','Half Day','FH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','$leaveFrom','$EmpName',now(),'0','Half Day','SH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				mysqli_query($att,"update employee_leave_tracker set cl_taken=cl_taken + 0.5 , cl_closing=cl_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				mysqli_query($att,"update employee_leave_tracker set Sl_taken=Sl_taken + 0.5 , sl_closing=sl_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
			
		}
		else
		{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveTypeAb','$leaveFrom','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				if($LeaveTypeAb!='Comp_Off')
				{
					mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_taken=".$LeaveTypeAb."_taken + 1 , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - 1 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				}
				else
				{
					$getCompOffEntry=mysqli_query($att,"SELECT * FROM `comp_off_tracker` where employee_id='$EmpID' and unit='1' and is_availed='N';");
					$getCompOffEntryHalf=mysqli_query($att,"SELECT * FROM `comp_off_tracker` where employee_id='$EmpID' and unit='0.5' and is_availed='N';");
					if(mysqli_num_rows($getCompOffEntry)==1)
					{
						$getCompOffEntryRow = mysqli_fetch_array($getCompOffEntry);
						$UpdateID = $getCompOffEntryRow['id'];
						$carr[]=$UpdateID;
					  mysqli_query($att,"update comp_off_tracker set is_availed='Y' where employee_id='$EmpID' and unit='1' and is_availed='N'");	
					}
					elseif(mysqli_num_rows($getCompOffEntryHalf)>=2)
					{
						$getquicktoExpireArr = mysqli_query($att,"SELECT id as id FROM `comp_off_tracker` where employee_id='$EmpID' and unit='0.5' and is_availed='N' order by expiry_date asc limit 2");
						while($getquicktoExpireArrRow = mysqli_fetch_assoc($getquicktoExpireArr))
						{
							$carr[]=$getquicktoExpireArrRow['id'];
							mysqli_query($att,"update comp_off_tracker set is_availed='Y' where id ='".$getquicktoExpireArrRow['id']."'");	
						}
						
					}
					else
					{
						$getquicktoExpire = mysqli_query($att,"SELECT id FROM `comp_off_tracker` where employee_id='$EmpID' and unit='1' and is_availed='N' order by expiry_date asc limit 1");
						$getquicktoExpireRow = mysqli_fetch_array($getquicktoExpire);
						$QuicktoExpireID = $getquicktoExpireRow['id'];
						$carr[]=$QuicktoExpireID;
						mysqli_query($att,"update comp_off_tracker set is_availed='Y' where id='$QuicktoExpireID'");	
					}					
				mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_availed=".$LeaveTypeAb."_availed + 1 , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - 1 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");	
				}
		}
	}
if($LeaveType=='Permission')
	{
		if($NumDays==1)
		{
			$Duration = "60";
		}
		else
		{
			$Duration ="120";
		}
		mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveType','$leaveFrom','$EmpName',now(),'$Duration','$leaveFor','','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				
				
	}
if($isCombined=='N' && $NumDays>1)
{
	$date_from = $leaveFrom;
	$date_from = strtotime($date_from); 
	$date_to = $leaveTo;  
	$date_to = strtotime($date_to);
	for ($i=$date_from; $i<=$date_to; $i+=86400) 
	{  
			//echo date("Y-m-d", $i).'<br />';
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveTypeAb','".date("Y-m-d", $i)."','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");		
				$arr[]= mysqli_insert_id($att);
	}
	if($LeaveTypeAb!='Comp_Off')
			{
				mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_taken=".$LeaveTypeAb."_taken + $NumDays , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - $NumDays where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
			}
       else
			{
				$i=0;
				$getCompOff = mysqli_query($att,"select id,unit from comp_off_tracker where is_availed='N' and employee_id='$EmpID' order by unit desc, expiry_date asc ");
				while($getCompOffRows=mysqli_fetch_assoc($getCompOff))
				{
					$i=$i+$getCompOffRows['unit'];
					if($i<=$NumDays)
					{
						$carr[]=$getCompOffRows['id'];
						mysqli_query($att,"update comp_off_tracker set is_availed='Y' where id='".$getCompOffRows['id']."'");
					}
				}
				mysqli_query($att,"update employee_leave_tracker set ".$LeaveTypeAb."_availed=".$LeaveTypeAb."_availed + $NumDays , ".$LeaveTypeAb."_closing=".$LeaveTypeAb."_closing - $NumDays where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");	
			}
}
if($isCombined=='Y' && $NumDays>1)
{
	if($LeaveType=='Privilege & Sick')
	{
		$PLCount = ($FirstComb-1)*86400;
		$SLCount = $SecComb*86400;
		$date_from = $leaveFrom;
		$date_from = strtotime($date_from);
		$PlEndDate = strtotime("+".$PLCount." seconds", $date_from);
		$SlStartDate = strtotime("+86400 seconds", $PlEndDate);
		$date_to = $leaveTo;  
		$date_to = strtotime($date_to);
			for ($i=$date_from; $i<=$PlEndDate; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','PL','".date("Y-m-d", $i)."','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			for ($i=$SlStartDate; $i<=$date_to; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','".date("Y-m-d", $i)."','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	 
					$arr[]= mysqli_insert_id($att);				
			}
			
		mysqli_query($att,"update employee_leave_tracker set Pl_taken=Pl_taken + $FirstComb , Pl_closing=Pl_closing - $FirstComb where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
		mysqli_query($att,"update employee_leave_tracker set Sl_taken=Sl_taken + $SecComb , Sl_closing=Sl_closing - $SecComb where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
	
	}
	
	if($LeaveType=='Casual & Sick')
	{
		if(fmod($FirstComb,1) !=0 || fmod($SecComb,1) !=0)
		{
			$isHalf= 1;
			$FirstComb=$FirstComb-0.5;
			$SecComb=$SecComb-0.5;
			$date_to = date("Y-m-d", strtotime("$leaveTo -1 day"));
		}
		else
		{
			$isHalf= 0;
			$date_to = $leaveTo;
		}
		$CLCount = ($FirstComb-1)*86400;
		$SLCount = $SecComb*86400;
		$date_from = $leaveFrom;
		$date_from = strtotime($date_from);
		$ClEndDate = strtotime("+".$CLCount." seconds", $date_from);
		$SlStartDate = strtotime("+86400 seconds", $ClEndDate); 
		$date_to = strtotime($date_to);
			for ($i=$date_from; $i<=$ClEndDate; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','CL','".date("Y-m-d", $i)."','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			for ($i=$SlStartDate; $i<=$date_to; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','".date("Y-m-d", $i)."','$EmpName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			if($isHalf == 1)
			{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','CL','$leaveTo','$EmpName',now(),'0','Half Day','FH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','$leaveTo','$EmpName',now(),'0','Half Day','SH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
					mysqli_query($att,"update employee_leave_tracker set cl_taken=cl_taken + $FirstComb +0.5, cl_closing=cl_closing - $FirstComb - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				mysqli_query($att,"update employee_leave_tracker set Sl_taken=Sl_taken + $SecComb + 0.5, Sl_closing=Sl_closing - $SecComb - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");	
				}
				else
				{
					mysqli_query($att,"update employee_leave_tracker set cl_taken=cl_taken + $FirstComb , cl_closing=cl_closing - $FirstComb where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				mysqli_query($att,"update employee_leave_tracker set Sl_taken=Sl_taken + $SecComb , Sl_closing=Sl_closing - $SecComb where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
			
				}
		
	}
	
}
$ID = mysqli_insert_id($att);
$sID     = "'" . implode ( "', '", $arr ) . "'";
$sID= '"' . $sID . '"';
$aID = "'" . implode ( "', '", $carr ) . "'";
$aID= '"' . $aID . '"';
mysqli_query($db,"update leave_request set status='Leave Granted',is_approved='Y',is_active='N',tracker_id=".$sID.",comp_off_tracker=".$aID." where req_id='$Req'");
mysqli_query($db,"update fyi_transaction set message = replace(message,'Requested',' Granted') where module_id='$Req' and module_name in ('Attendance Management','Leave Management')");
unset($aID);
unset($sID);
unset($arr);
unset($carr);
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


}
//echo "update leave_request set status='Leave Granted',is_approved='Y',is_active='N',tracker_id=".$sID." where req_id='$Req'";

header("Location: TeamLeaveRequest.php");
?>