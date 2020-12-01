<?php
include("config.php");
session_start();
$BefBal = $_SESSION['LeaveBefApp'];
include("Attendance_Config.php");
$employeeid=$_POST['EmpID'];
$getName= mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_Details where employee_id='$employeeid'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
include("Attendance_Config.php");
$leavebalance = mysqli_query($att,"SELECT cl_opening,sl_opening,pl_opening,cl_taken,sl_taken,pl_taken,cl_closing,sl_closing,pl_closing,comp_off_opening,comp_off_availed,comp_off_closing FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate()) and month=month(curdate())");
$leavestaken = mysqli_query($att,"SELECT sum(cl_taken) as cl_taken,sum(sl_taken) as sl_taken, sum(pl_taken) as pl_taken FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate());");

$CLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days  from leave_request where employee_id='$employeeid' and leave_type='Casual' and is_active='Y'");
$SLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$employeeid' and leave_type='Sick'  and is_active='Y'");
$PLRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$employeeid' and leave_type='Privilege'  and is_active='Y'");
$CompOFFRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$employeeid' and leave_type='Compensatory-Off'  and is_active='Y'");
$PermissionRequested = mysqli_query($db,"select sum(number_of_days) as number_of_days from leave_request where employee_id='$employeeid' and leave_type='Permission' and is_active='Y'");

$CLSLRequested = mysqli_query($db,"select combination_1,combination_2 from leave_request where employee_id='$employeeid' and leave_type='Casual & Sick' and is_active='Y'");
$PLSLRequested = mysqli_query($db,"select combination_1,combination_2 from leave_request where employee_id='$employeeid' and leave_type='Privilege & Sick' and is_active='Y'");

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
$GetPCount = mysqli_query($db,"select is_comp_off_eligible from employee where employee_id=$employeeid");
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
$getAvailedCountSixty = mysqli_query($att,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$employeeid and leave_type='Permission' and duration='60' and cancled='N';");
$getAvailedCountOT = mysqli_query($att,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$employeeid and leave_type='Permission' and duration='120' and cancled='N';");
$AvailedCntSixty = mysqli_num_rows($getAvailedCountSixty);
$AvailedCntSixtyOT = mysqli_num_rows($getAvailedCountOT);
$AvailedCnt=$AvailedCntSixty+($AvailedCntSixtyOT*2);
$PermissionBalance= $TotalCount-($AvailedCnt+$PermissionRequest);
$AppliedLeave = $_SESSION['leaveType'];
if($AppliedLeave!='On-Duty')
{
$data = " 

		<h4 class='modal-title'>Leave Balance Before Approval for <strong>".$EmpName."</strong></h4>
		<br>
		".$BefBal."
			<br>
	
	<h4 class='modal-title'>Leave Balance After Approval for <strong>".$EmpName."</strong></h4>
<br>
    <table id='leaveTable' style='padding: 0px;' class='table table-bordered'>
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
              </table>		  ";
}
else
{

$data= 'NIL';

}
			  echo $data;
?>