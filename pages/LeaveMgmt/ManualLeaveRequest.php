<?php
include("config.php");
$employeeid=$_GET['id'];
$name=$_SESSION['login_user'];
$reidrole=$_SESSION['login_user_group'];
$checkREporting = mysqli_query($db,"select * from employee_details where employee_id='$employeeid' and reporting_manager_id='$name'");
if(mysqli_num_rows($checkREporting)>0)
{
?>
<?php
include("config.php");
$employeeid=$_GET['id'];
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

$CLSLRequested = mysqli_query($db,"select sum(combination_1) as combination_1,sum(combination_2) as combination_2 from leave_request where employee_id='$employeeid' and leave_type='Casual & Sick' and is_active='Y'");
$PLSLRequested = mysqli_query($db,"select sum(combination_1) as combination_1,sum(combination_2) as combination_2 from leave_request where employee_id='$employeeid' and leave_type='Privilege & Sick' and is_active='Y'");


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
$AllODReasons = mysqli_query($att,"select value from master_lookup where field_name='REASON FOR OD'");
$Checkedislogged = mysqli_query($att,"select * from attendance where employee_id=$employeeid and check_in !='0001-01-01 00:00:00' and shift_date=curdate()");
if(mysqli_num_rows($Checkedislogged)>0)
{
	$Checkedin='Y';
}
$getSuprtLoginAccess = mysqli_query($att,"select * from user_access_control where main_menu='Super Login' and accessed_to='$reidrole';");
        if(mysqli_num_rows($getSuprtLoginAccess))
        {
        	$SuperAccessed ='Y';
        }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Leave Request</title>
  <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=0.35, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

   <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <script src="../../dist/js/loader.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
th {
  background-color: #31607c;
  color:white;
}
.form_error span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: #D83D5A;
}
.form_error input {
  border: 1px solid #D83D5A;
}

/*Styling in case no errors on form*/
.form_success span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: green;
}
.form_success input {
  border: 1px solid green;
}
#error_msg {
  color: red;
  text-align: center;
  margin: 10px auto;
}
</style>
<style>
.main-header
{
    height:50px;
}
.content-wrapper
{
	max-height: 500px;
	overflow-y:scroll;   
}
</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php
 require_once('Layouts/main-header.php');
 ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
 require_once('Layouts/main-sidebar.php');
 ?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Attendance Management

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Employee Leave</li>
      </ol>
    </section>
	
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
         <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title">
			  <button onclick="window.location='TeamLeaveRequest.php'" type="button" style="width:30%" class="btn btn-block btn-primary btn-flat">Back</button>
			  <br>
			  Grant Leave for <strong><?php echo ' '.$EmpName.'' ?></strong>
			 <br>
			  </h3>

              <div class="box-tools">
              </div>
            </div>
			
			<a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#myModal" class="btn btn-danger pull-right">Skip Upload</a>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
             <form id="LeaveForm" method="post" class="form-horizontal" action="">
			  <div class="col-md-4">
              <div class="form-group" style="width:90%">
			  <input type="hidden" id="EmpName" value="<?php echo $EmpName; ?>"/>
			  <input type="hidden" id="AppEmpId" name="AppEmpId" value="<?php echo $employeeid; ?>"/>
			  <input type="hidden" id="plbal" value="<?php echo $plbalance; ?>"/>
			  <input type="hidden" id="slbal" value="<?php echo $slbalance ?>"/>
			  <input type="hidden" id="clbal" value="<?php echo $clbalance ?>"/>
			  <input type="hidden" id="scbal" value="<?php echo $clbalance+$slbalance ?>"/>
			  <input type="hidden" id="spbal" value="<?php echo $plbalance+$slbalance ?>"/>
			  <input type="hidden" id="clbal" value="<?php echo $clbalance ?>"/>
			  <input type="hidden" id="cobal" value="<?php echo $compoffclosing ?>"/>
			  <input type="hidden" id="pmbal" value="<?php echo $PermissionBalance ?>"/>
			  <input type="hidden" id="Vabal" value="<?php echo $plbalance+$slbalance+$clbalance+$compoffclosing ?>"/>
			  <label>Leave Type <span class="astrick">*</span><a href="#" title="View Leave Balance" id="additionalLevel" data-toggle="modal" data-target="#modal-default"> <i class="fa fa-fw fa-info-circle"></i></a></label>
                 <select class="form-control select2" id="EmployeeLeave" name="EmployeeLeave" required="required" style="width: 100%;" required>
                 <option value=""  selected disabled>Please Select from Below</option>
                 <option value="Sick"  >Sick (SL)</option>
                 <option value="Casual"  >Casual (CL)</option>
				  <option value="Privilege">Privilege (PL)</option>
				  <option value="Permission">Permission</option>
                 <option value="Privilege & Sick">Privilege & Sick (PL & SL)</option>
                 <option value="Casual & Sick">Casual & Sick (CL & SL)</option>
                  <option value="On-Duty">On-Duty (OD)</option>
				 <?php
				 if($PCount=='Y')
				 {
				 ?>
                 <option value="Compensatory-Off">Compensatory-Off</option> 
				 <?php
				 }
				 ?>
                 <?php
				 if($Checkedin=='Y')
				 {
				 ?>
                
                   <?php
				 }
				   ?>
                  <?php
				 if($SuperAccessed=='Y')
				 {
				 ?>
                 <option value="WeekOff">Week-Off</option>
                 <option value="FlexiShift">Flexi Time</option>
                   <?php
				 }
				   ?>
                   
				</select>
              </div>
              <div class="form-group" style="width:90%;display:none;" id="checkInDiv">
                <label id="DayOneIN">In -Time <span class="astrick">*</span></label>
              <div class="input-group">
                    <input type="text" id="InTime" name="InTime" value="09:00 AM" class="form-control timepicker" required="required" >
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  </div>
			  <div class="form-group" style="width:90%">
			  <label>No of Days* </label>
                  <input type="text" name="NumberDays" value="0" class="form-control pull-right"  required="required" id="NumberDays" placeholder="# Days Requested" required readonly>
				<span></span>
			  </div>
              
			  <div class="form-group" style="width:90%">
			  <label>Permission Type <span class="astrick">*</span></label>
                 <select class="form-control select2" id="PermissionType" name="PermissionType" required="required"  style="width: 100%;" required disabled>
                 <option value=""  selected disabled>Please Select from Below</option>
                 <option value="Early Out">Early Out</option>
                 <option value="Late In">Late In</option>
                
 
				</select>
              </div>
			  
			  </div>
			    <div class="col-md-4">
               <div class="form-group" style="width:90%">
			    <label>From<span class="astrick">*</span> </label>
                <div class="input-group date">
                   <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
				  
                  <input type="text" name="dateFrom" autocomplete="off" style="background-color: white;" class="form-control pull-right"  required="required" id="datepicker1" placeholder="Pick a date" required readonly>
				
                </div>
				</div> 
                <div class="form-group" style="width:90%;display:none;" id="checkoUTDiv">
                <label id="DayOneIN">Out -Time <span class="astrick">*</span></label>
              <div class="input-group">
                    <input type="text" id="OutTime" name="OutTime" value="06:00 PM" class="form-control timepicker" required="required" >
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  </div>
				<div class="form-group" style="width:90%" id="LeaveForDiv">
			  <label>Leave For <span class="astrick">*</span></label>
                 <select class="form-control select2" id="LeaveFor" name="LeaveFor" required="required" onChange="ChangeHalfDay();" style="width: 100%;" required disabled>
                 <option value=""  selected disabled>Please Select from Below</option>
                 <option value="Full Day">Full Day</option>
                 <option value="Half-Day (First Half)">Half-Day (First Half)</option>
                 <option value="Half-Day (Second Half)">Half-Day (Second Half)</option>
                
				</select>
              </div>
			  
			  <div class="form-group" style="width:90%;display:none;" id="OnDutyLeaveForDiv">
			    <label>Leave For<span class="astrick">*</span> </label>
               
                  <input type="text" name="LeaveForOD" autocomplete="off" value="Full Day" class="form-control pull-right"  required="required" id="LeaveForOD" placeholder="Choose from Below" required disabled>
               
				</div>
				 <div class="form-group" style="width:90%">
			  <label>No of Hours* </label>
                  <input type="number" name="NumberHours" min="0" max="2" class="form-control pull-right"  required="required" id="NumberHours" placeholder="# Hours" onKeyPress="return isNumberKey(event)" required disabled>
				<span></span>
			  </div>
			  </div>
			    <div class="col-md-4">
              <div class="form-group" style="width:90%">
			    <label>To<span class="astrick">*</span> </label>
                <div class="input-group date">
                   <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="dateTo" autocomplete="off" style="background-color: white;" class="form-control pull-right"  required="required" id="datepicker" placeholder="Pick a date" required readonly>
				
                </div>
				</div>
				<div class="form-group" style="width:90%;display:none;" id="OnDutyDiv">
			    <label>Reason for OD<span class="astrick">*</span></label>
            
               <select class="form-control select2" id="OnDutyReason" name="OnDutyReason" required="required" style="width: 100%;" required disabled>
                 <option value=""  selected disabled>Please Select from Below</option>
				 <?php
				 while($AllODReasonsRow = mysqli_fetch_assoc($AllODReasons))
				 {
				 ?>
                 <option value="<?php echo $AllODReasonsRow['value'] ?>"><?php echo $AllODReasonsRow['value'] ?></option>
                <?php
				 }
				?>
				</select>
				</div>
				 <div class="form-group" style="width:90%; display:none;" id="plsl">
			  <label>Choose Combination (PL & SL Count) </label>
			 
			    <div class="col-md-6">
				
                  <input type="number" name="SLPLCntPL"  min="0" max="<?php echo ($plbalance<0)?0:$plbalance  ?>" class="form-control pull-right"   required="required" step="1" value="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="SLPLCntPL" placeholder="PL Count" required >
				  </div>
				   <div class="col-md-6">
				    <input type="number" name="SLPLCntSL" min="0" max="<?php echo ($slbalance<0)?0:$slbalance ?>" class="form-control pull-right"  required="required" onKeyPress="return isNumberKey(event)" step="0.5" id="SLPLCntSL"  value="0" placeholder="SL Count" required>
					 </div>
			  </div>
			  <div class="form-group" style="width:90%; display:none;" id="clsl">
			  <label>Choose Combination (CL & SL Count) </label>
			    <div class="col-md-6">
                  <input type="number" name="SLCLCntCL"  min="0" max="<?php echo ($clbalance<0)?0:$clbalance ?>" class="form-control pull-right"   required="required" onKeyPress="return isNumberKey(event)" step="0.5" id="SLCLCntCL" value="0" placeholder="CL Count" required >
				  </div>
				  <div class="col-md-6">
				    <input type="number" name="SLCLCntSL" min="0" max="<?php echo ($slbalance<0)?0:$slbalance ?>" class="form-control pull-right"   required="required" onKeyPress="return isNumberKey(event)" step="0.5" id="SLCLCntSL" value="0" placeholder="SL Count" required>
					 </div>
			  </div>
				 <div class="form-group" style="width:90%">
			  <label>Reason</label>
                 <textarea name="reason" id="reason" rows="5" cols="20" maxlength="200" class="is-maxlength" style="
    width: 100%;"></textarea>
              </div>
			  <br>
			  <div class="form-group" style="width:90%">
			  <input type="submit" style="background-color: #00a65a;" style="width:90%" id="submitData" class="btn btn-info pull-right" value="Grant Leave" />
			   </div>
			  </div>
			 </form>
<br>
<br>            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Leave Balance</h4>
              </div>
              <div class="modal-body">
                 <table id="leaveTable" style="padding: 0px;" class="table table-bordered">
                <tr>
                  <th>Leave</th>

                  <th>Total</th>
                  <th>Taken</th>
                  <th>Waiting for Approval</th>
				  <th>Balance</th>
                  
                </tr>
           <tr>
                  <td>CL</td>
                  <td><span class="badge bg-blue"><?php  echo  $clOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $clavailed ?></span></td>
                  <td><span class="badge bg-yellow"><?php  echo  $CLReqAll ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $clbalance ?></span></td>
                </tr>
                 <tr>
                  <td>PL</td>
                  <td><span class="badge bg-blue"><?php  echo  $plOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $pltaken ?></span></td>
                  <td><span class="badge bg-yellow"><?php  echo  $PLReqAll ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $plbalance ?></span></td>
                </tr>
                <tr>
                  <td>SL</td>
                  <td><span class="badge bg-blue"><?php  echo  $slOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $slavailed ?></span></td>
                  <td><span class="badge bg-yellow"><?php  echo  $SLReqAll ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $slbalance ?></span></td>
                </tr>
				<tr>
                  <td>Permission</td>
                  <td><span class="badge bg-blue"><?php  echo $TotalCount ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $AvailedCnt ?></span></td>
                  <td><span class="badge bg-yellow"><?php  echo  $PermissionRequest ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $PermissionBalance ?></span></td>
                </tr>
				<?php
				if($PCount=='Y')
				{
				?>
				 <tr>
                  <td>Comp-Off</td>
                  <td><span class="badge bg-blue">0</span></td>
                  <td><span class="badge bg-red"><?php  echo  $compofftaken ?></span></td>
                  <td><span class="badge bg-yellow"><?php  echo  $CompOFFRequest ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $compoffclosing ?></span></td>
                </tr>
				<?php
				}
				?>
              </table> 
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		<div class="modal fade" id="modal-default-grant">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Employee Management</h4>
              </div>
              <div class="modal-body">
				 <div class="box box-info">
           <?php

		   ?>
            <form id="DashForm" action="ModifyJoinee.php" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-12">
		   
			
		</div>
            </div>
            <?php
			 include("config.php");
			 $getTeam = mysqli_query($db,"select employee_id,concat(first_name,' ',last_name) as name from employee_Details where reporting_manager_id='$employeeid'");
			 ?>
			  </h4>
 <table id="example3" style="padding: 0px;" class="table table-bordered table-striped dataTable" style="margin:1em auto;">
   <thead>
   
				  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Apply Leave</th>
                  <th>Change Shift</th>
                  <th>Comp Off</th>
              
                </thead>
				<?php
				if(mysqli_num_rows($getTeam)>0)
				{
					while($getTeamRow=mysqli_fetch_assoc($getTeam))
					{
				?>
				<tr>
					<td style="display:none;" class="EmployeeID"><?php echo $getTeamRow['req_id']; ?></td>
					 <td><?php echo $getTeamRow['employee_id']; ?></td>
					 <td class="name"><?php echo $getTeamRow['name']; ?></td>
					<td><a href="ManualLeaveRequest.php?id=<?php echo $getTeamRow['employee_id']; ?>" id="NoReported" ><img alt='User' src='../../dist/img/Tick_Mark_Dark-512.png' title="Apply Leave" width='18px' height='20px' /></a></td>
					<td><a href="ManualShiftChange.php?id=<?php echo $getTeamRow['employee_id']; ?>" id="ShiftChange" ><img alt='User' src='../../dist/img/32-512.png' title="Change Shift" width='18px' height='20px' /></a></td>
					<?php
					$getApproved = mysqli_query($db,"select * from employee where employee_id='".$getTeamRow['employee_id']."' and is_comp_off_eligible='Y'");
					if(mysqli_num_rows($getApproved)>0)
					{
					?>
					<td><a href="CompOffRequest.php?id=<?php echo $getTeamRow['employee_id']; ?>" id="CompOff" ><img alt='User' src='../../dist/img/Gym_2-512.png' title="Approve Comp Off" width='18px' height='18px' /></a></td>
					
					<?php
					}
					else
					{
					?>
					<td>NA</td>
					<?php
					}
					?>
				</tr>
				
				
				<?php
					}
				}
				else
				{
				?>
				<tr>
				<td>No Employees Found!</td>
				</tr>
				<?php
				}
				?>
  </table>

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>	
            </div>
            
            <div class="modal-footer">
                <button type="button" id="closeRole" onclick="clearFields1();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>  
		   <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:lightcoral">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Leave Management</h4>
              </div>
              <div class="modal-body">
                 <h5>Insufficient Leave Balance. Please try a different Leave Type.</h5>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.col -->
      </div>

      <!-- /.row -->


      <!-- /.row -->

      <!-- this row will not appear when printing -->

    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../bower_components/moment/min/moment.min.js"></script>
<script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script language="Javascript">
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </script>
<script>
	var number = document.getElementById('NumberHours');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
	</script>
	<script>
	var number = document.getElementById('SLPLCntPL');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
	</script>
<script>
function ChangeHalfDay()
{
	var LeaveLD = document.getElementById("LeaveFor").value;
	if(LeaveLD!='Full Day')
	{
		document.getElementById("NumberDays").value='0.5';
	}
	else
	{
		document.getElementById("NumberDays").value='1';
	}
}
</script>
<script>
function CheckforBal()
{
	var returnvalue = true;
	var from = document.getElementById("datepicker1").value;
	var to = document.getElementById("datepicker").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		returnvalue = false;
	}
	var leave = document.getElementById("EmployeeLeave").value;
	var Numberdays = document.getElementById("NumberDays").value;
	var NumberHours = document.getElementById("NumberHours").value;
	var sl = document.getElementById("slbal").value;
	var integersl = parseFloat(sl);
	var cl = document.getElementById("clbal").value;
	var integercl = parseFloat(cl);
	var pl = document.getElementById("plbal").value;
	var integerpl = parseFloat(pl);
	var sc = document.getElementById("scbal").value;
	var integersc = parseFloat(sc);
	var sp = document.getElementById("spbal").value;
	var integersp = parseFloat(sp);
	var co = document.getElementById("cobal").value;
	var integerco = parseFloat(co);
	var pm = document.getElementById("pmbal").value;
	var integerpm = parseFloat(pm);
	var va = document.getElementById("Vabal").value;
	var integerva = parseFloat(va);
	
	var SLPLCntPL = document.getElementById("SLPLCntPL").value;
	var integerSLPLCntPL = parseFloat(SLPLCntPL);
	
	var SLPLCntSL = document.getElementById("SLPLCntSL").value;
	var integerSLPLCntSL = parseFloat(SLPLCntSL);
	
	var SLCLCntCL = document.getElementById("SLCLCntCL").value;
	var integerSLCLCntCL = parseFloat(SLCLCntCL);
	
	var SLCLCntSL = document.getElementById("SLCLCntSL").value;
	var integerSLCLCntSL = parseFloat(SLCLCntSL);
	
	var SLPL =  integerSLPLCntPL+integerSLPLCntSL;
	
	var SLCL =  integerSLCLCntCL+integerSLCLCntSL;
	
	var FutAlert='';
	if (integersl==0 && integercl==0 && integerpl==0)
        {
        	 FutAlert = 'Kindly contact HR to avail advance leaves.';
        }

	if(leave=='Sick')
	{
		if(Numberdays>=3)
		{
			 var r = confirm("SL for 3 or More Days Requires Medical Documentation. Click OK to Confirm");
			 if (r == true)
					{
							returnvalue = true;
					}
					else 
					{
							returnvalue = false;
					}
		}
		if(Numberdays>integersl)
		{
			alert("Insufficient Sick Leave Balance."+" "+FutAlert);
			returnvalue = false;
		}
    	var from = document.getElementById("datepicker1").value;
		var dateSelString = from.split('-');
		var dateSelYear = dateSelString[0];
    	
    	var to = document.getElementById("datepicker").value;
		var dateSelStringto = to.split('-');
		var dateSelYearto = dateSelString[0];
    
            var today = new Date();
			var year = today.getFullYear();
			if(dateSelYear!=year || dateSelYearto!=year)
			{
				 alert("Sick Leave Can be requested for Current year Only!");
				 returnvalue = false;
			}
	}
	if(leave=='Casual')
	{
		if(Numberdays>integercl)
		{
			alert("Insufficient Casual Leave Balance."+" "+FutAlert);
			  returnvalue = false;
		}
    	var from = document.getElementById("datepicker1").value;
		var dateSelString = from.split('-');
		var dateSelYear = dateSelString[0];
    	
    	var to = document.getElementById("datepicker").value;
		var dateSelStringto = to.split('-');
		var dateSelYearto = dateSelString[0];
    
            var today = new Date();
			var year = today.getFullYear();
			if(dateSelYear!=year || dateSelYearto!=year)
			{
				 alert("Casual Leave Can be requested for Current year Only!");
				 returnvalue = false;
			}
    	if(Numberdays>2)
		{
			alert("Casual Leave Cannot be Applied for More than 2 Days!");
			  returnvalue = false;
		}
	}
	if(leave == 'Privilege')
	{
    	if(Numberdays % 1 != 0)
		{
			alert("Privilege Leave Type Cannot be applied for Half-Day");
			 returnvalue = false;
		}
		if(Numberdays > integerpl)
		{
			alert("Insufficient Privilege Leave Balance."+" "+FutAlert);
			 returnvalue = false;
		}
	}
	if(leave=='Compensatory-Off')
	{
		if(Numberdays>integerco)
		{
			alert("Insufficient Compensatory-Off Balance");
			 returnvalue = false;
		}
	}
	if(leave=='Privilege & Sick')
	{
		if(integerSLPLCntSL>=3)
		{
			var r = confirm("SL for 3 or More Days Requires Medical Documentation. Click OK to Confirm");
			 if (r == true)
					{
							returnvalue = true;
					}
					else 
					{
							returnvalue = false;
					}
		}
		if(integerSLPLCntPL==0 || integerSLPLCntSL==0)
		{
			alert("Please Choose a value more than Zero!");
			 returnvalue = false;
		}
		if(Numberdays>integersp)
		{
			alert("Insufficient Leave Balance");
			 returnvalue = false;
		}
   		 if(Numberdays<1)
		{
			alert("PL Combination Cannot be applied for Half-Days!");
			 returnvalue = false;
		}
		if (SLPL!=Numberdays)
		{
			alert("Combination Does not match with the Requested Days!");
			 returnvalue = false;
		}
		
	}
	if(leave=='Casual & Sick')
	{
		if(integerSLCLCntSL>=3)
		{
			var r = confirm("SL for 3 or More Days Requires Medical Documentation. Click OK to Confirm");
			 if (r == true)
					{
							returnvalue = true;
					}
					else 
					{
							returnvalue = false;
					}
		}
		if(integerSLCLCntCL==0 || integerSLCLCntSL==0)
		{
			alert("Please Choose a value more than Zero!");
			 returnvalue = false;
		}
		if(Numberdays>integersc)
		{
			alert("Insufficient Leave Balance");
			 returnvalue = false;
		}
		if (SLCL!=Numberdays)
		{
			alert("Combination Does not match with the Requested Days!");
			 returnvalue = false;
		}
	}
	if(leave=='Permission')
	{
		var from = document.getElementById("datepicker1").value;
		var dateSelString = from.split('-');
		var dateSelMon = dateSelString[1];
			var today = new Date();
			var month = today.getMonth()+1;
			if(month<10)
			{
				month= '0'+ month;
			}
			if(dateSelMon!=month)
			{
				 alert("Permission Can be requested for Current Month Only!");
				 returnvalue = false;
			}
		if(NumberHours==0)
		{
			alert("Please Choose a value more than Zero!");
			 returnvalue = false;
		}
		if(NumberHours>2)
		{
			alert("You can't Apply for more than 2 Hours a Day!");
			 returnvalue = false;
		}
		if(NumberHours>integerpm)
		{
			alert("Insufficient Permission Balance");
			 returnvalue = false;
		}
	}
	if(leave=='Vacation')
	{
		if(Numberdays>integerva)
		{
			alert("Insufficient Leave Balance");
			 returnvalue = false;
		}
	}
	if(returnvalue == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	
}
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#LeaveForm").submit(function(e) {
var EmployeeNameVal = document.getElementById("EmpName").value;
	ajaxindicatorstart("Hold On..Checking for Date Availability");
	e.preventDefault();
  var data = $("#LeaveForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "ManualDateCheck.php",
         success: function(data)
		 {
			 if(data=='pos')
			 {
				var x = CheckforBal();
				if(x==true)
				{
					SubmitRequest();
				}
				else
				{
					ajaxindicatorstop();
				}
				
			 }
			 else
			 {
				alert("Leave Requested / Availed for the Given Dates!"); 
				ajaxindicatorstop();
			 }
         }
});

});
});
    </script>
	<script>
	function SubmitRequest()
	{
	ajaxindicatorstart("Hold On..Granting Leave");
	var data = $("#LeaveForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "ManagerGrant.php",
         success: function(data)
		 {
			window.location.href = "TeamLeaveRequest.php";
			ajaxindicatorstop();
         }
});
	}
	</script>
<script>
function ClearData() {
  document.getElementById("EmployeeForm").reset();
}
</script>
<script>
$('#datepicker').on('change', function() {
		var to = $('#datepicker').val();
		var fromval = $('#datepicker1').val();
		var leavetype = $('#EmployeeLeave').val();
		if(to==fromval && leavetype!='WeekOff' && leavetype!='FlexiShift')
		{
			$("#LeaveFor").attr('disabled', false);
			
		}
		else
		{
				$("#LeaveFor").attr('disabled', true);
		}
	});
</script>
<script>
$('#datepicker1').on('change', function() {
		var to = $('#datepicker').val();
		var fromval = $('#datepicker1').val();
		var leavetype = $('#EmployeeLeave').val();
		if(to==fromval && leavetype!='WeekOff' && leavetype!='FlexiShift')
		{
			$("#LeaveFor").attr('disabled', false);
		}
		else
		{
				$("#LeaveFor").attr('disabled', true);
		}
	});
</script>
<script>
$('#EmployeeLeave').on('change', function() {
debugger;
		var Empl = $('#EmployeeLeave').val();
		if(Empl=='Permission')
		{
			$("#PermissionType").attr('disabled', false);
			$("#NumberHours").attr('disabled', false);
			$("#datepicker").attr('disabled', true);
			
			$('#datepicker').val("");
			$("#LeaveFor").attr('disabled', true);
			$("#NumberDays").val("0");
			
		}
		else
		{
				$("#PermissionType").attr('disabled',true);
				$("#NumberHours").attr('disabled', true);
				$("#datepicker").attr('disabled', false);
				$("#datepicker").attr('readonly', true);  
				//$('#PermissionType option[value='']').prop('selected', true);
				//$("#NumberHours").val('0');
		}
	});
</script>
<script>
$('#EmployeeLeave').on('change', function() {
		var Empl = $('#EmployeeLeave').val();
		if(Empl=='Casual & Sick')
		{
			$("#clsl").css("display", "block");
		}
		else
		{
			$("#clsl").css("display", "none");
		}
		if(Empl=='Privilege & Sick')
		{
			$("#plsl").css("display", "block");
		}
		else
		{
			$("#plsl").css("display", "none");
		}
	});
</script>
<script>
$('#EmployeeLeave').on('change', function() {
debugger;
		var Empl = $('#EmployeeLeave').val();
		if(Empl=='FlexiShift')
		{
			$("#checkoUTDiv").css("display", "block");
        	$("#checkInDiv").css("display", "block");
        	$("#datepicker").attr('disabled', true);
        	$("#datepicker1").attr('disabled', false);
        	$("#datepicker").val("");
			$("#datepicker1").val("");
        	$("#OnDutyLeaveForDiv").css("display", "none");
        	$("#LeaveForDiv").css("display", "block");
        	$("#OnDutyDiv").css("display", "none");
        	$("#NumberDays").val("1");
        	$("#reason").attr('disabled', false);
        	$("#LeaveFor").attr('disabled', true);
		}
		else
		{
        	$("#datepicker").val("");
			$("#datepicker1").val("");
			$("#checkoUTDiv").css("display", "none");
        	$("#checkInDiv").css("display", "none");
        
        	if(Empl!='Permission')
			{
			$("#datepicker").attr('disabled', false);
			$("#datepicker").attr('readonly', true);  
			}
			$("#datepicker1").attr('disabled', false); 
			$("#datepicker1").attr('readonly', true);  			
        	$("#LeaveFor").attr('disabled', true);
		}
	});
</script>
<script>
$('#EmployeeLeave').on('change', function() {
	debugger;
	var isOD = $('#EmployeeLeave').val();
	if(isOD=='On-Duty')
		{
			debugger;
			$("#OnDutyDiv").css("display", "block");
			$("#OnDutyLeaveForDiv").css("display", "block");
			$("#LeaveForDiv").css("display", "none");
			$("#NumberDays").val("1");
			ODDATE = "<?php echo date('Y-m-d'); ?>";
			$("#NumberDays").val("1");
			$("#datepicker").val(ODDATE);
			$("#datepicker1").val(ODDATE);
			$("#datepicker").attr('disabled', true);
			$("#datepicker1").attr('disabled', true);
			$("#reason").attr('disabled', true);
			$("#OnDutyReason").attr('disabled', false);
		}
		if(isOD!='On-Duty' && isOD!='FlexiShift')
		{
			$("#OnDutyDiv").css("display", "none");
			$("#NumberDays").val("0");
        	if(isOD!='Permission')
			{
			$("#datepicker").attr('disabled', false);
			}
			$("#datepicker1").attr('readonly', true);  
            
			$("#reason").attr('disabled', false);
			$("#OnDutyLeaveForDiv").css("display", "none");
			$("#LeaveForDiv").css("display", "block");
			$("#datepicker").val("");
			$("#datepicker1").val("");
			$("#OnDutyReason").attr('disabled', true);
		}
		});
</script>
<script>
$('#OnDutyReason').on('change', function() {
	
	var isOther = $('#OnDutyReason').val();
	if(isOther!='Other Visits')
		{
			debugger;
			$("#reason").attr('disabled', true);
		}
		else
		{
			$("#reason").attr('disabled', false);
		}
		});
</script>
<script>
$('#datepicker').on('change', function() {
		var to = $('#datepicker').val();
		var fromval = $('#datepicker1').val();
		if(fromval=='')
		{
			alert("Please Fill in From Date");
			return false;
		}
		else if(to=='')
		{
			alert("Please Fill in To Date");
			return false;
		}
		else
		{
			GetDays();
		}
	});
</script>
<script>
$('#datepicker1').on('change', function() {
		var to = $('#datepicker').val();
		var fromval = $('#datepicker1').val();
		if(fromval=='')
		{
			alert("Please Fill in from Date");
			return false;
		}
		else if(to=='')
		{
			//alert("Please Fill in To Date");
			return false;
		}
		else
		{
			GetDays();
		}
	});
</script>
<script>
function GetDays() {
	
	var EmpId = $('#datepicker1').val();
	var EmpId1 = $('#datepicker').val();
	var LeaveType = $('#datepicker').val();
	var LeaveForVal = $('#LeaveFor').val();
    //ajax request
   $.ajax({
      url: 'employeeCheck.php',
      type: 'post',
      data: {
      	'email' : EmpId,
      	'email1' : EmpId1,
      },
      success: function(response){
      	if (response != '' ) {
			if(response!=1)
			{
				$('#NumberDays').val(response);
			}
			else
			{
				if(LeaveForVal!='Half-Day (First Half)' && LeaveForVal!='Half-Day (Second Half)')
				{
					$('#NumberDays').val(response);
				}
				else
				{
					$('#NumberDays').val('0.5');
				}
			}
      }
 	}
	});
   }
</script>
<script>
function CheckDays() {
	var EmpId = $('#datepicker1').val();
	var EmpId1 = $('#datepicker').val();
	var LeaveType = $('#datepicker').val();
    //ajax request
   $.ajax({
      url: 'DateCheck.php',
      type: 'post',
	  async: false,
      data: {
      	'email' : EmpId,
      	'email1' : EmpId1,
      },
      success: function(response){
      	if (response == 'pos') {
			
				$('form').removeAttr('onsubmit'); // prevent endless loop
                $('form').submit();
      }
 	}
	});
	 return false;
   }
</script>
<script>
function AjaxStart()
{
    if(returnvalue)
	{
		alert(returnvalue);
	}
}
</script>
<script>
$('.CloseClass').on('click', function() {
	ajaxindicatorstart("Closing your Request ..");
	location.reload();
	});
</script>
<script type="text/javascript">
function CheckforZero()
{
var from = document.getElementById("datepicker1").value;
var to = document.getElementById("datepicker").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		return false;
	}
	else
	{
		return true;
	}
}
</script>




</script>

<!-- Page script -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
	   startDate: '+0d'
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: '+0d'
    })
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>









<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
<?php
require_once('Layouts/documentModals.php');
?>
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Mainlogin.php");
}
?>