<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config.php');
$EventID = $_POST['EventID'];
$AllEmp = $_POST['AllEmp'];
$CanAccomp = $_POST['CanAccomp'];
if($AllEmp=='Yes')
	{
		
		mysqli_query($db,"delete from event_invitees where event_id='$EventID' and is_active='Y'");
		mysqli_query($db,"insert into event_invitees
						  select 0,'$EventID',employee_id,'Y','N','$CanAccomp','','0001-01-01 00:00:00','0001-01-01 00:00:00','Y','$name',now() 
						  from employee_details where is_active='Y' and employee_id not in ('3')");
	}
else
	{
		$sWhere = '';
		$sBU = '';
		$sDept = '';
		$sEmp = '';
        $where = array();
		$SelbyBU = count($_POST["SelByBU"]);
		if($SelbyBU > 0)
			{
				for($m=0; $m<$SelbyBU; $m++)  
				{  	
					$BU[] = $_POST["SelByBU"][$m];	
				}
				$sBU     = "'" . implode ( "', '", $BU ) . "'";
				$where[] = "business_unit in ($sBU)";
			}
			
		$SelbyDept = count($_POST["SelByDepartment"]);
		if($SelbyDept > 0)
			{
				for($n=0; $n<$SelbyDept; $n++)  
				{  	
				$Dept[] = $_POST["SelByDepartment"][$n];	
				}
				$sDept     = "'" . implode ( "', '", $Dept ) . "'";
				$where[] = "department in ($sDept)";
			}
		$sWhere     = implode(' AND ', $where);
        if($sWhere) {
            $sWhere = 'WHERE '.$sWhere;
        } 
		mysqli_query($db,"insert into event_invitees
											select 0,'$EventID',employee_id,'Y','N','$CanAccomp','','0001-01-01 00:00:00','0001-01-01 00:00:00','Y','$name',now() 
											from employee_details $sWhere and is_active='Y' and employee_id not in 
											(select employee_id from event_invitees where event_id='$EventID' and is_active='Y') and employee_id not in ('3')"
									   );	
									   
		$AdditionalEmp = count($_POST["AdditionalEmp"]);
		if($AdditionalEmp > 0)
			{
				for($i=0; $i<$AdditionalEmp; $i++)  
				{  	
					$Emp[] = $_POST["AdditionalEmp"][$i];	
				}
				$sEmp     = "'" . implode ( "', '", $Emp ) . "'";
			
				mysqli_query($db,"insert into event_invitees
											select 0,'$EventID',employee_id,'Y','N','$CanAccomp','','0001-01-01 00:00:00','0001-01-01 00:00:00','Y','$name',now() 
											from employee_details where employee_id in ($sEmp) and is_active='Y' and employee_id not in 
											(select employee_id from event_invitees where event_id='$EventID' and is_active='Y') and employee_id not in ('3')"
									   );	
		   }
	}
	
header("Location: NewEventInvitees.php?id=$EventID");
?>