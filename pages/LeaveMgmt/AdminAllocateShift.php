<?php
//require_once("queries.php");
include('config.php');
include("Attendance_Config.php");
session_start();
$no_days=1;
$EmpID = $_POST['AppEmpId'];
$NewShift = $_POST['EmployeeShift'];
$NewFrom = $_POST['dateFrom'];
$NewTo = $_POST['dateTo'];
$usergrp= $_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_details where employee_id='$EmpID'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$GetCurrentEntries = mysqli_query($db,"select employee_shift_id,shift_code,start_date,end_date from employee_shift where
(('$NewFrom' between start_Date and end_date) or
('$NewTo' between start_Date and end_date))
 and employee_id=$EmpID;");
if(mysqli_num_rows($GetCurrentEntries)>0)
 {
		$GetCurrentEntriesRow = mysqli_fetch_array($GetCurrentEntries);
		$CurrentStartDate = $GetCurrentEntriesRow['start_date'];
		$CurrentShift = $GetCurrentEntriesRow['shift_code'];
		$CurrentEndDate = $GetCurrentEntriesRow['end_date'];
		$CurrentID = $GetCurrentEntriesRow['employee_shift_id'];
		if($CurrentStartDate==$NewFrom && $CurrentEndDate==$NewTo)
		{
			mysqli_query($att,"update employee_shift set shift_code='$NewShift',modified_date_and_time=now(),modified_by='$name' where end_date = '$NewTo' and start_date='$NewFrom' and employee_id='$EmpID'");	
			echo 'Same Date Update';
		}
		else
		{
			if($NewFrom==$NewTo)
			{
				 if($NewFrom>$CurrentStartDate && $NewTo<$CurrentEndDate)
				 {
					 $UpdatedCurrentEnd = strtotime("-".$no_days." days", strtotime($NewFrom));
					 $UpdatedCurrentEnd =date("Y-m-d", $UpdatedCurrentEnd);
					 mysqli_query($att,"update employee_shift set end_date = '$UpdatedCurrentEnd' ,modified_date_and_time=now(),modified_by='$name' where employee_shift_id='$CurrentID'");
					 mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
						values
						('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
					$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
					$FutureStartDate =date("Y-m-d", $FutureStartDate);
					mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$CurrentShift','$FutureStartDate','$CurrentEndDate','N','$name',' ','0001-01-01 00:00:00',now())");
							
					echo "Single Date : Between two Dates";
					 
				 }
				 if($NewFrom==$CurrentStartDate)
				 {
					 mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
					('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
					$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
				    $FutureStartDate =date("Y-m-d", $FutureStartDate);
			  mysqli_query($att,"update employee_shift set start_date ='$FutureStartDate',shift_code=
					'$CurrentShift',modified_date_and_time=now(),modified_by='$name'	where employee_shift_id='$CurrentID'");  
					echo "Single Date : Same From Date";
				 }
				 if($NewTo==$CurrentEndDate)
				 {
					 $UpdatedCurrentEnd = strtotime("-".$no_days." days", strtotime($NewTo));
					 $UpdatedCurrentEnd =date("Y-m-d", $UpdatedCurrentEnd);
					 mysqli_query($att,"update employee_shift set end_date = '$UpdatedCurrentEnd',modified_date_and_time=now(),modified_by='$name' where employee_shift_id='$CurrentID'");
					 mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
								echo "Single Date : Same To Date";
				 }
			}
			else
			{
				if($NewFrom>$CurrentStartDate && $NewTo<$CurrentEndDate)
				 {
					 $UpdatedCurrentEnd = strtotime("-".$no_days." days", strtotime($NewFrom));
					 $UpdatedCurrentEnd =date("Y-m-d", $UpdatedCurrentEnd);
					 mysqli_query($att,"update employee_shift set end_date = '$UpdatedCurrentEnd',modified_date_and_time=now(),modified_by='$name' where employee_shift_id='$CurrentID'");
					 mysqli_query($att,"insert into employee_shift 			(employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
						values
						('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
					$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
					$FutureStartDate =date("Y-m-d", $FutureStartDate);
					mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$CurrentShift','$FutureStartDate','$CurrentEndDate','N','$name',' ','0001-01-01 00:00:00',now())");
							
					echo "Multiple Date : Between two Dates";
					 
				 }
				 if($NewFrom==$CurrentStartDate && $NewTo<$CurrentEndDate)
				 {
					 mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
					('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
					$FutureStartDate = strtotime("+".$no_days." days", strtotime($NewTo));
				    $FutureStartDate =date("Y-m-d", $FutureStartDate);
					mysqli_query($att,"update employee_shift set start_date ='$FutureStartDate',shift_code=
					'$CurrentShift',modified_date_and_time=now(),modified_by='$name'	where employee_shift_id='$CurrentID'");  
					echo "Multiple Date : Same From Date";
				 }
				 
				  if($NewFrom>$CurrentStartDate && $NewTo==$CurrentEndDate)
				 {
					 $UpdatedCurrentEnd = strtotime("-".$no_days." days", strtotime($NewFrom));
					 $UpdatedCurrentEnd =date("Y-m-d", $UpdatedCurrentEnd);
					 mysqli_query($att,"update employee_shift set end_date = '$UpdatedCurrentEnd',modified_date_and_time=now(),modified_by='$name' where employee_shift_id='$CurrentID'");
					 mysqli_query($att,"insert into employee_shift (employee_id,Shift_code,start_Date,end_date,is_current,created_by,modified_by,modified_date_and_time,created_date_and_time)
								values
								('$EmpID','$NewShift','$NewFrom','$NewTo','N','$name',' ','0001-01-01 00:00:00',now())");
								echo "Multiple Date : Same To Date";
				 }
			}
		}
 }
?>