<?php
//require_once("queries.php");
include('config.php');
include("Attendance_Config.php");
session_start();
$EmpID = $_POST['AppEmpId'];
$NewShift = $_POST['EmployeeShift'];
$NewFrom = $_POST['dateFrom'];
$NewTo = $_POST['dateTo'];
$usergrp= $_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$EmpID'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$getInBetween = mysqli_query($att,"select * from employee_shift where employee_id='$EmpID' and end_date>=curdate()");
if(mysqli_num_rows($getInBetween)<=1)
{
	$getCurrentShift = mysqli_query($att,"SELECT employee_shift_id,shift_code,start_date,end_date FROM `employee_shift` where employee_id='$EmpID' and curdate() between start_date and end_date;");
	$getCurrentShiftRow = mysqli_fetch_array($getCurrentShift);
	$CurrentShiftID = $getCurrentShiftRow['employee_shift_id'];
	$CurrentShift= $getCurrentShiftRow['shift_code'];
	$CurrStartDate= $getCurrentShiftRow['start_date'];
	$CurrEndDate = $getCurrentShiftRow['end_date'];
	$no_days=1;
	if($CurrStartDate==$NewFrom)
    {
    	$CurrentEndDate = $NewTo;
    }
	else
	{
		$CurrentEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
		$CurrentEndDate =date("Y-m-d", $CurrentEndDate);
	}
	
	mysqli_query($att,"update employee_shift set end_date = '$CurrentEndDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$CurrentShiftID'");
	if($CurrStartDate!=$NewFrom)
    {
	mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
				values
					('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
    }
else
{
mysqli_query($att,"update employee_shift set end_date = '$CurrentEndDate',Shift_code='$NewShift',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$CurrentShiftID'");
}
	$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
	$FutureStartDate =date("Y-m-d", $FutureStartDate);
	mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
						values
						('$EmpID','$CurrentShift','$FutureStartDate','2050-12-31','N','$name',' ','0001-01-01 00:00:00',now())");
						echo 'YEs';
//header("Location: ManualShiftChange.php");
}
else
{
	$IfInbetween = mysqli_query($att,"select * from employee_shift where employee_id='$EmpID'
									and start_date < '$NewFrom' and end_date> '$NewTo'");
	$IfStartbetween = mysqli_query($att,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where employee_id='$EmpID'
									and start_date = '$NewFrom' and end_date> '$NewTo'");
									
	$IfEndbetween = mysqli_query($att,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where employee_id='$EmpID'
									and start_date < '$NewFrom' and end_date = '$NewTo'");
									
									
	$IfInbetweenSame = mysqli_query($att,"select * from employee_shift where employee_id='$EmpID'
									and start_date = '$NewFrom' and end_date = '$NewTo'");
	if(mysqli_num_rows($IfInbetween)>0)
		{
			$getCurrentShift = mysqli_query($att,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where employee_id='$EmpID'
									and start_date <'$NewFrom' and end_date>'$NewTo'");
			$getCurrentShiftRow = mysqli_fetch_array($getCurrentShift);
			$CurrentShiftID = $getCurrentShiftRow['employee_shift_id'];
			$CurrentShift= $getCurrentShiftRow['shift_code'];
			$CurrStartDate= $getCurrentShiftRow['start_date'];
			$CurrEndDate = $getCurrentShiftRow['end_date'];
			$no_days=1;
			$CurrentEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
			$CurrentEndDate =date("Y-m-d", $CurrentEndDate);
			mysqli_query($att,"update employee_shift set end_date = '$CurrentEndDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$CurrentShiftID'");
			mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
			$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
			$FutureStartDate =date("Y-m-d", $FutureStartDate);
			mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$CurrentShift','$FutureStartDate','$CurrEndDate','N','$name',' ','0001-01-01 00:00:00',now())");
								echo 'No';
		}
		if(mysqli_num_rows($IfStartbetween)>0)
		{
			$getCurrentShiftRow = mysqli_fetch_array($IfStartbetween);
			$CurrentShiftID = $getCurrentShiftRow['employee_shift_id'];
			$CurrentShift= $getCurrentShiftRow['shift_code'];
			$CurrStartDate= $getCurrentShiftRow['start_date'];
			$CurrEndDate = $getCurrentShiftRow['end_date'];
			$no_days=1;
			$CurrentEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
			$CurrentEndDate =date("Y-m-d", $CurrentEndDate);
			mysqli_query($att,"update employee_shift set end_date = '$NewTo',shift_code='$NewShift',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$CurrentShiftID'");
			$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
			$FutureStartDate =date("Y-m-d", $FutureStartDate);
			mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$CurrentShift','$FutureStartDate','$CurrEndDate','N','$name',' ','0001-01-01 00:00:00',now())");
								echo 'StartBetween';
		}
		if(mysqli_num_rows($IfEndbetween)>0)
		{
			$getCurrentShiftRow = mysqli_fetch_array($IfEndbetween);
			$CurrentShiftID = $getCurrentShiftRow['employee_shift_id'];
			$CurrentShift= $getCurrentShiftRow['shift_code'];
			$CurrStartDate= $getCurrentShiftRow['start_date'];
			$CurrEndDate = $getCurrentShiftRow['end_date'];
			$no_days=1;
			$CurrentEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
			$CurrentEndDate =date("Y-m-d", $CurrentEndDate);
			mysqli_query($att,"update employee_shift set end_date = '$CurrentEndDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$CurrentShiftID'");
				mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
			$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
			$FutureStartDate =date("Y-m-d", $FutureStartDate);
								echo 'NoEndBetween';
		}
	if(mysqli_num_rows($IfInbetweenSame)>0)
		{
			mysqli_query($att,"update employee_shift set shift_code='$NewShift',Modified_Date_And_Time=now(),modified_by='$name' where end_date = '$NewTo' and start_date='$NewFrom' and employee_id='$EmpID'");	
			echo 'Same';
		}
	if($NewFrom == $NewTo && mysqli_num_rows($IfInbetweenSame)==0 && mysqli_num_rows($IfStartbetween)==0 && mysqli_num_rows($IfEndbetween)==0 && mysqli_num_rows($IfInbetween)==0)
	{
			$IfStartSame = mysqli_query($att,"select employee_shift_id,shift_code,start_Date,end_Date from employee_shift where employee_id='$EmpID'
											and start_date = '$NewFrom'");
			$IfEndSame = mysqli_query($att,"select employee_shift_id,shift_code,start_Date,end_Date from employee_shift where employee_id='$EmpID'
											and end_date = '$NewTo'");
			
				if(mysqli_num_rows($IfStartSame)>0)
				{
					$IfStartSameRow = mysqli_fetch_array($IfStartSame);
					$IfStartShiftID = $IfStartSameRow['employee_shift_id'];
					$IfStartShift= $IfStartSameRow['shift_code'];
					$IfStartDate= $IfStartSameRow['start_Date'];
					$IfStartEndDate = $IfStartSameRow['end_Date'];
					$no_days=1;
					mysqli_query($att,"update employee_shift set end_date ='$NewTo',shift_code='$NewShift',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$IfStartShiftID'");
					$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
					$FutureStartDate =date("Y-m-d", $FutureStartDate);
					mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
									('$EmpID','$IfStartShift','$FutureStartDate','$IfStartEndDate','N','$name',' ','0001-01-01 00:00:00',now())");
									
									echo 'Hi';
				}							
				elseif(mysqli_num_rows($IfEndSame)>0)
				{
					$IfStartSameRow = mysqli_fetch_array($IfEndSame);
					$IfStartShiftID = $IfStartSameRow['employee_shift_id'];
					$IfStartShift= $IfStartSameRow['shift_code'];
					$IfStartDate= $IfStartSameRow['start_Date'];
					$IfStartEndDate = $IfStartSameRow['end_Date'];
					$no_days=1;
					$IfStartSameEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
					$IfStartSameEndDate =date("Y-m-d", $IfStartSameEndDate);
					mysqli_query($att,"update employee_shift set end_date ='$IfStartSameEndDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$IfStartShiftID'");
					mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
									echo 'Vanakkam';
									
								
				}	
				else
				{
					
				}
				
	}
		if(mysqli_num_rows($IfInbetweenSame)==0 && mysqli_num_rows($IfInbetween)==0 && $NewFrom != $NewTo && mysqli_num_rows($IfEndbetween)==0 && mysqli_num_rows($IfStartbetween)==0)
			{
					$getFirstSet = mysqli_query($att,"select employee_shift_id,start_date,end_date,shift_code from employee_shift where '$NewFrom' between start_Date and end_date and employee_id=$EmpID");
					$getFirstSetRow = mysqli_fetch_array($getFirstSet);
					$FirstSetFrom = $getFirstSetRow['start_date'];
					$FirstSetTo = $getFirstSetRow['end_date'];
					$FirstSetID = $getFirstSetRow['employee_shift_id'];
					
					$getSecondSet = mysqli_query($att,"select employee_shift_id,start_date,end_date,shift_code from employee_shift where '$NewTo' between start_Date and end_date and employee_id=$EmpID");
					$getSecondSetRow = mysqli_fetch_array($getSecondSet);
					$SecondSetFrom = $getSecondSetRow['start_date'];
					$SecondSetTo = $getSecondSetRow['end_date'];
					$SecondSetID = $getSecondSetRow['employee_shift_id'];
					$FutShiftCode = $getSecondSetRow['shift_code'];
					$no_days=1;
					
					$FirstEndDate = strtotime("-".$no_days." days", strtotime($NewFrom));
					$FirstEndDate =date("Y-m-d", $FirstEndDate);
					mysqli_query($att,"update employee_shift set end_date ='$FirstEndDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$FirstSetID'");
					mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
					$SecStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
					$SecStartDate =date("Y-m-d", $SecStartDate);
					mysqli_query($att,"update employee_shift set start_date ='$SecStartDate',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id='$SecondSetID'");
					echo $NewFrom.' '.$NewTo;
					$CheckifInbetweenShifts = mysqli_query($att,"select employee_shift_id from employee_shift where employee_shift_id not in ('$FirstSetID','$SecondSetID') start_date between '$NewFrom' and '$NewTo'
																	and end_date between '$NewFrom' and '$NewTo'
																	and employee_id=$EmpID");
					while($CheckifInbetweenShiftsRow = mysqli_fetch_assoc($CheckifInbetweenShifts))
					{
						mysqli_query($att,"update employee_shift set shift_code='$NewShift',Modified_Date_And_Time=now(),modified_by='$name' where employee_shift_id ='".$CheckifInbetweenShiftsRow['employee_shift_id']."' start_date between '$NewFrom' and '$NewTo");
					}
																	
			}
}
?>