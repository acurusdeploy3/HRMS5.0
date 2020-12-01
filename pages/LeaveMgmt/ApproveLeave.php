<?php
//require_once("queries.php");
include('config.php');
include("Attendance_Config.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$name'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];

$Req = $_GET['id'];
$History = mysqli_query($db,"insert into history_leave_request SELECT 0,now(),e.*  FROM leave_request e where req_id = '$Req'");
$getData = mysqli_query($db,"select employee_id,leave_type,leave_from,leave_to,number_of_days,is_combined_leave,combination_1,combination_2,leave_for,reason from leave_request where req_id='$Req'");
$getDataRow = mysqli_fetch_array($getData);
$EmpID = $getDataRow['employee_id'];
$LeaveType = $getDataRow['leave_type'];
$_SESSION['leaveType'] = $LeaveType;
$leavebalance = mysqli_query($att,"SELECT cl_opening,sl_opening,pl_opening,cl_taken,sl_taken,pl_taken,cl_closing,sl_closing,pl_closing,comp_off_opening,comp_off_availed,comp_off_closing FROM `employee_leave_tracker` where employee_id=$EmpID and year=year(curdate()) and month=month(curdate())");
$leavestaken = mysqli_query($att,"SELECT sum(cl_taken) as cl_taken,sum(sl_taken) as sl_taken, sum(pl_taken) as pl_taken FROM `employee_leave_tracker` where employee_id=$EmpID and year=year(curdate());");

$CLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days  from leave_request where employee_id='$EmpID' and leave_type='Casual' and is_active='Y'");
$SLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$EmpID' and leave_type='Sick'  and is_active='Y'");
$PLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$EmpID' and leave_type='Privilege'  and is_active='Y'");
$CompOFFRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$EmpID' and leave_type='Compensatory-Off'  and is_active='Y'");
$PermissionRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$EmpID' and leave_type='Permission' and is_active='Y'");

$CLSLRequested = mysqli_query($db,"select combination_1,combination_2 from leave_request where employee_id='$EmpID' and leave_type='Casual & Sick' and is_active='Y'");
$PLSLRequested = mysqli_query($db,"select combination_1,combination_2 from leave_request where employee_id='$EmpID' and leave_type='Privilege & Sick' and is_active='Y'");

$ClRequestRow = mysqli_fetch_array($CLRequested);
$SlRequestRow = mysqli_fetch_array($SLRequested);
$PlRequestRow = mysqli_fetch_array($PLRequested);
$CompOFFRequestRow = mysqli_fetch_array($CompOFFRequested);
$PermissionRequestRow = mysqli_fetch_array($PermissionRequested);
$CLSLRequestRow = mysqli_fetch_array($CLSLRequested);
$PLSLRequestRow = mysqli_fetch_array($PLSLRequested);

$CLRequest = $ClRequestRow['number_of_days'];
$SLRequest = $SlRequestRow['number_of_days'];
$PLRequest = $PlRequestRow['number_of_days'];
$CompOFFRequest = $CompOFFRequestRow['number_of_days'];
$PermissionRequest = $PermissionRequestRow['number_of_days'];
$CLSLReqCL = $CLSLRequestRow['combination_1'];
$CLSLReqSL = $CLSLRequestRow['combination_2'];
$PLSLReqPL = $PLSLRequestRow['combination_1'];
$PLSLReqSL = $PLSLRequestRow['combination_2'];

$CLRequest=($CLRequest!= '')?$CLRequest:0;
$SLRequest=($SLRequest!= '')?$SLRequest:0;
$PLRequest=($PLRequest!= '')?$PLRequest:0;
$PLRequest=($PLRequest!= '')?$PLRequest:0;
$CompOFFRequest=($CompOFFRequest!= '')?$CompOFFRequest:0;
$PermissionRequest=($PermissionRequest!= '')?$PermissionRequest:0;


$CLReqAll = $CLRequest+$CLSLReqCL;
$SLReqAll = $SLRequest+$CLSLReqSL+$PLSLReqSL;
$PLReqAll = $PLRequest+$PLSLReqPL;



$leavebalanceRow = mysqli_fetch_array($leavebalance);
$clOpening = $leavebalanceRow['cl_opening'];
$clavailed = $leavebalanceRow['cl_taken'];
$clbalance = $leavebalanceRow['cl_closing']-$CLReqAll;
$slOpening = $leavebalanceRow['sl_opening'];
$slavailed = $leavebalanceRow['sl_taken'];
$slbalance = $leavebalanceRow['sl_closing']-$SLReqAll;
$plOpening = $leavebalanceRow['pl_opening'];
$pltaken = $leavebalanceRow['pl_taken'];
$plbalance = $leavebalanceRow['pl_closing']-$PLReqAll;
$compoffopening = $leavebalanceRow['comp_off_opening'];
$compoffclosing = $leavebalanceRow['comp_off_closing']-$CompOFFRequest;
$compofftaken = $leavebalanceRow['comp_off_availed'];
$GetPCount = mysqli_query($db,"select is_comp_off_eligible from employee where employee_id=$EmpID");
$getPCountRow = mysqli_fetch_array($GetPCount);
$PCount = $getPCountRow['is_comp_off_eligible'];
if($PCount=='Y' || $PCount=='T')
{
	$TotalCount = 2;
}
else
{
	$TotalCount = 10;
}
$getAvailedCountSixty = mysqli_query($att,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$EmpID and leave_type='Permission' and duration='60' and cancled='N';");
$getAvailedCountOT = mysqli_query($att,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$EmpID and leave_type='Permission' and duration='120' and cancled='N';");
$AvailedCntSixty = mysqli_num_rows($getAvailedCountSixty);
$AvailedCntSixtyOT = mysqli_num_rows($getAvailedCountOT);
$AvailedCnt=$AvailedCntSixty+($AvailedCntSixtyOT*2);
$PermissionBalance= $TotalCount-($AvailedCnt+$PermissionRequest);

$data = " 
    <table id='leaveBefTable' style='padding: 0px;' class='table table-bordered'>
                <tr>
                  <th>Leave</th>

                  <th>Total</th>
                  <th>Taken</th>
                  <th>Waiting for Approval</th>
				  <th>Balance</th>
                  
                </tr>
           <tr>
                  <td>CL</td>
                  <td><span class='badge bg-blue'>".$clOpening."</span></td>
                  <td><span class='badge bg-red'>".$clavailed."</span></td>
                  <td><span class='badge bg-yellow'>".$CLReqAll."</span></td>
                  <td><span class='badge bg-green'>".$clbalance."</span></td>
                </tr>
                 <tr>
                  <td>PL</td>
                  <td><span class='badge bg-blue'>".$plOpening."</span></td>
                  <td><span class='badge bg-red'>".$pltaken."</span></td>
                  <td><span class='badge bg-yellow'>".$PLReqAll."</span></td>
                  <td><span class='badge bg-green'>".$plbalance."</span></td>
                </tr>
                <tr>
                  <td>SL</td>
                  <td><span class='badge bg-blue'>".$slOpening."</span></td>
                  <td><span class='badge bg-red'>".$slavailed."</span></td>
                  <td><span class='badge bg-yellow'>".$SLReqAll."</span></td>
                  <td><span class='badge bg-green'>".$slbalance."</span></td>
                </tr>
				<tr>
                  <td>Permission</td>
                  <td><span class='badge bg-blue'>".$TotalCount."</span></td>
                  <td><span class='badge bg-red'>".$AvailedCnt."</span></td>
                  <td><span class='badge bg-yellow'>".$PermissionRequest."</span></td>
                  <td><span class='badge bg-green'>".$PermissionBalance."</span></td>
                </tr>
				<?php
				if($PCount=='Y')
				{
				?>
				 <tr>
                  <td>Comp-Off</td>
                  <td><span class='badge bg-blue'>0</span></td>
                  <td><span class='badge bg-red'>".$compofftaken."</span></td>
                  <td><span class='badge bg-yellow'>".$CompOFFRequest."</span></td>
                  <td><span class='badge bg-green'>".$compoffclosing."</span></td>
                </tr>
				<?php
				}
				?>
              </table> ";

$_SESSION['BalEmpID']= $EmpID;
$_SESSION['LeaveBefApp'] = $data;
$_SESSION['BalEmpID']= $EmpID;
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
            $mail->Subject = 'AHRMS : Attendance Management : '.'Permission Request Approved';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Permission has been Approved.</td>
					</tr>
					
					<tr style='background-color:#fff;'>
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Leave Type : <strong>". $LeaveType." </strong></td>
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
            $mail->Subject = 'AHRMS : Attendance Management : '.$LeaveTypeAb.' Request Approved';
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
						
						<td style='padding-left: 30px; padding-right: 30px; padding-bottom:15px; padding-top:15px;'>Your Request has been Approved.</td>
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

//echo "update leave_request set status='Leave Granted',is_approved='Y',is_active='N',tracker_id=".$sID." where req_id='$Req'";

header("Location: TeamLeaveRequest.php");

?>