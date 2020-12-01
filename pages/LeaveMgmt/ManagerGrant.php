<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
include("Attendance_Config.php");
$EmpID = $_POST['AppEmpId'];
$getRepMgmr = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$EmpID'");

$getRepMgmrRow = mysqli_fetch_array($getRepMgmr);
$RepMgmr = $getRepMgmrRow['reporting_manager_id'];
$EmpName = $getRepMgmrRow['name'];
$getRepMgmrName = mysqli_query($db,"select concat(first_name,' ',last_name) as name,reporting_manager_id from employee_Details where employee_id='$name'");
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
('$EmpID','$name','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo','Y','$CLCombinationSLCL','$SLCombinationSLCL');");
}
if($LeaveType=='Privilege & Sick')
{
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to,is_combined_leave,combination_1,combination_2)
values
('$EmpID','$name','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo','Y','$PLCombinationSLPL','$SLCombinationSLPL');");
}
if($LeaveType=='Permission')
{
	mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to)
values
('$EmpID','$name','$LeaveType','$NumberHours','Request Sent : Awaits Manager Action','$PermissionType',now(),'$name','$reason','$dateFrom','0001-01-01');");
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
('$EmpID','$RepMgmr','On-Duty','1','Request Sent : Awaits Manager Action','Full Day',now(),'$name','$ODReason','$ODDATE','$ODDATE');");
}
if($LeaveType=='FlexiShift')
{
		$InTime = $_POST['InTime'];
		$OutTime = $_POST['OutTime'];
		$CheckinTime=date_create($InTime);
		$CheckoutTime=date_create($OutTime);
		$CheckinFormatted =  date_format($CheckinTime,"H:i:s");
		$CheckoutFormatted =  date_format($CheckoutTime,"H:i:s");
		$Checkin = $dateFrom.' '.$CheckinFormatted;
		$Checkout = $dateFrom.' '.$CheckoutFormatted;
		mysqli_query($db,"insert into flexi_in_out_time select 0,$EmpID,'$Checkin','$Checkout','$dateFrom','Y',now(),'$name','0001-01-01 00:00:00','';");
		$arr[]= mysqli_insert_id($db);
		mysqli_query($db,"insert into leave_request
						(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
						leave_from,leave_to)
						values
						('$EmpID','$name','FlexiShift','$NumDays','Leave Granted','',now(),'$name','$reason','$dateFrom','$dateFrom');");
}
if($LeaveType=='WeekOff')
{
		if($NumDays==1)
        {
        	mysqli_query($att,"insert into flexi_weekoff (employee_id,date,description) values ($EmpID,'$dateFrom','$reason')");
        	$arr[]= mysqli_insert_id($att);
        }
		else
        {
        		$date_from = $dateFrom;
				$date_from = strtotime($date_from); 
				$date_to = $dateTo;  
				$date_to = strtotime($date_to);
				for ($i=$date_from; $i<=$date_to; $i+=86400) 
				{  
					mysqli_query($att,"insert into flexi_weekoff (employee_id,date,description) values($EmpID,'".date("Y-m-d", $i)."','$reason')");		
					$arr[]= mysqli_insert_id($att);
				}
        }
}
if ($LeaveType!='Permission' && $LeaveType!='Privilege & Sick' && $LeaveType!='Casual & Sick' && $LeaveType!='On-Duty')
{
		mysqli_query($db,"insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason,
leave_from,leave_to)
values
('$EmpID','$name','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason','$dateFrom','$dateTo');");
}
	
$reqId = mysqli_insert_id($db);	



echo "insert into leave_request
(employee_id,allocated_to,leave_type,number_of_days,status,leave_for,created_date_and_time,created_by,reason)
values
('$EmpID','$name','$LeaveType','$NumDays','Request Sent : Awaits Manager Action','$leavefor',now(),'$name','$reason')";

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
if($LeaveType=='WeekOff')
{
	$LeaveTypeAb='WO';
}
if($LeaveType=='FlexiShift')
{
	$LeaveTypeAb='Flexible Time';
}
if($LeaveType=='Permission')
{
	$LeaveTypeAb ='Permission';
}
if($dateFrom == $dateTo)
{
	$dateFromDate = date_create($dateFrom); 
	$Leavedate = date_format($dateFromDate,'d M Y');
	$LeavedateText= $LeaveTypeAb.' Granted ( Date : '.$Leavedate.' )';
}
else
{
	$dateFromDate = date_create($dateFrom); 
	$dateToDate = date_create($dateTo);
	$dateFromDate = date_format($dateFromDate,'d M Y');
	$dateToDate = date_format($dateToDate,'d M Y');
	$LeavedateText = $LeaveTypeAb.' Granted (Date : '.$dateFromDate.' - '.$dateToDate.' )';
}

mysqli_query($db,"insert into fyi_transaction (employee_id,employee_name,transaction,module_id,module_name,message,date_of_message,created_date_and_time,created_by)
values
('$EmpID','$EmpName','Attendance Management','$reqId','Attendance Management','$LeavedateText',curdate(),now(),'$name');");



$getData = mysqli_query($db,"select employee_id,leave_type,leave_from,leave_to,number_of_days,is_combined_leave,combination_1,combination_2,leave_for,reason from leave_request where req_id='$reqId'");
$getDataRow = mysqli_fetch_array($getData);
$LeaveType = $getDataRow['leave_type'];
$leaveFrom = $getDataRow['leave_from'];
$leaveTo = $getDataRow['leave_to'];
$NumDays = $getDataRow['number_of_days'];
$isCombined = $getDataRow['is_combined_leave'];
$FirstComb = $getDataRow['combination_1'];
$SecComb = $getDataRow['combination_2'];
$leavefor = $getDataRow['leave_for'];
$reason = $getDataRow['reason'];
if($leavefor=='Half-Day (Second Half)' || $leavefor=='Half-Day (First Half)')
	{
			if($leavefor=='Half-Day (Second Half)')
			{
				$Remarks = "SH";
			}
			else
			{
				$Remarks = "FH";
			}
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveTypeAb','$leaveFrom','$RepMgmrName',now(),'0','Half Day','$Remarks','N','','0001-01-01 00:00:00');
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

if($leavefor=='Full Day' && $NumDays==1 && $LeaveType!='WeekOff' && $LeaveType!='FlexiShift')
	{
		if($LeaveType=='Casual & Sick')
		{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','CL','$leaveFrom','$RepMgmrName',now(),'0','Half Day','FH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','$leaveFrom','$RepMgmrName',now(),'0','Half Day','SH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				mysqli_query($att,"update employee_leave_tracker set cl_taken=cl_taken + 0.5 , cl_closing=cl_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
				mysqli_query($att,"update employee_leave_tracker set Sl_taken=Sl_taken + 0.5 , sl_closing=sl_closing - 0.5 where year=year(curdate()) and month=month(curdate()) and employee_id=$EmpID");
			
		}
		else
		{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','$LeaveTypeAb','$leaveFrom','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
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
				('$EmpID','$LeaveType','$leaveFrom','$RepMgmrName',now(),'$Duration','$leavefor','','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
				
				
	}
if($isCombined=='N' && $NumDays>1 && $LeaveType!='WeekOff' && $LeaveType!='FlexiShift')
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
				('$EmpID','$LeaveTypeAb','".date("Y-m-d", $i)."','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
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
				('$EmpID','PL','".date("Y-m-d", $i)."','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			for ($i=$SlStartDate; $i<=$date_to; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','".date("Y-m-d", $i)."','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
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
				('$EmpID','CL','".date("Y-m-d", $i)."','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			for ($i=$SlStartDate; $i<=$date_to; $i+=86400) 
			{  
				 mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','".date("Y-m-d", $i)."','$RepMgmrName',now(),'0','','','N','','0001-01-01 00:00:00');
				");	
					$arr[]= mysqli_insert_id($att);				
			}
			if($isHalf == 1)
			{
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','CL','$leaveTo','$RepMgmrName',now(),'0','Half Day','FH','N','','0001-01-01 00:00:00');
				");
				$arr[]= mysqli_insert_id($att);
			mysqli_query($att,"insert into leave_status(employee_id,leave_type,date_availed,approved_by,Approved_date_time,duration,permission_type,remarks,cancled,cancled_by,cancled_date_time)
			values
				('$EmpID','SL','$leaveTo','$RepMgmrName',now(),'0','Half Day','SH','N','','0001-01-01 00:00:00');
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
mysqli_query($db,"update leave_request set status='Leave Granted',is_approved='Y',is_active='N',tracker_id=".$sID.",comp_off_tracker=".$aID." where req_id='$reqId'");
if($leavefor=='')
{
	$leavefor='--';
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
            $mail->Subject = 'AHRMS : Attendance Management : '.'Permission Granted';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
			<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$EmpName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Permission has been Granted.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Permission Type : <strong>". $LeaveType." </strong></td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Number of Hours: <strong>". 
$NumDays." Hour(s)</strong></td>
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
            $mail->Subject = 'AHRMS : Attendance Management : '.$LeaveTypeAb.' Granted';
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML("<html><body>
	<div>
		<img src='AHRMS_Logo3.png' align='right' alt='logo' style='width:220px;height:100px; margin:0 auto; align:right; display:block;'>
			</div>
	<table style=' width:100%; margin:0 auto; font-family:Open Sans, sans-serif; border-collapse:collapse;' >
	<tbody>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Dear ".$EmpName.",</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Request has been Granted.</td>
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

//send Mail
header("Location: ManualLeaveRequest.php");
?>