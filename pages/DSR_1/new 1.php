<?php
include("ServerCheck.php");
if($attcheck)
{
?>
<?php
include("config.php");
$employeeid=$_SESSION['login_user'];
$BalEmpID = $_SESSION['BalEmpID'];
$leavebalance = mysqli_query($db,"SELECT cl_opening,sl_opening,pl_opening,cl_taken,sl_taken,pl_taken,cl_closing,sl_closing,pl_closing,comp_off_opening,comp_off_availed,comp_off_closing FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate()) and month=month(curdate())");
$leavestaken = mysqli_query($db,"SELECT sum(cl_taken) as cl_taken,sum(sl_taken) as sl_taken, sum(pl_taken) as pl_taken FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate());");
 $leavebalanceRow = mysqli_fetch_array($leavebalance);
$clOpening = $leavebalanceRow['cl_opening'];
$clavailed = $leavebalanceRow['cl_taken'];
$clbalance = $leavebalanceRow['cl_closing'];
$slOpening = $leavebalanceRow['sl_opening'];
$slavailed = $leavebalanceRow['sl_taken'];
$slbalance = $leavebalanceRow['sl_closing'];
$plOpening = $leavebalanceRow['pl_opening'];
$pltaken = $leavebalanceRow['pl_taken'];
$plbalance = $leavebalanceRow['pl_closing'];
$compoffopening = $leavebalanceRow['comp_off_opening'];
$compoffclosing = $leavebalanceRow['comp_off_closing'];
$compofftaken = $leavebalanceRow['comp_off_availed'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Leave Request</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=0.30, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
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
 <link href="toastr/toastr.css" rel="stylesheet" type="text/css" />
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
      Team Leave Request

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Team Leave Request</li>
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
              <div class="box-tools"  style="position:relative;">
			  <a href="#" class="btn btn-primary pull-left" style="margin-left:5px;" id="NoReported" data-toggle="modal" data-target="#modal-default-grant">Employee Management</a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
<h4 class="box-title" style="margin-left:10px;"><strong>Active Request(s)</strong>
			 <?php
			 include("config.php");
			 $getLEaves = mysqli_query($db,"select req_id,a.employee_id,concat(b.first_name,' ',b.last_name) as name,leave_type,number_of_days,status as action,if(leavE_for='','--',leavE_for) as leavE_for,reason,
date(a.created_date_and_time) as  date_of_request,
date(leave_from) as  leave_from,
if(leave_to='0001-01-01','--',date(leave_to)) as  leave_to,
is_approved,combination_1,combination_2,is_combined_leave from leave_request a
left join employee_details b on a.employee_id=b.employee_id
 where allocated_to='$employeeid' and  is_approved not in ('Y','N')  and a.is_Active='Y' order by a.created_date_and_time desc ;");

if(mysqli_num_rows($getLEaves)==0)
{ 
	$disabled = 'disabled'; 
}
			 ?>
			  </h4>
        
            <br>
            <br>
			  <input type="hidden" id="BalEmpID" value="<?php echo $BalEmpID; ?>" />
            <form id="SelectForm" action="#" method="post">
  <table id="ReqLeaves" style="padding: 0px;" class="table table-bordered table-striped dataTable">
    
    <thead>
   
				  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Request Type</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Date of Request</th>
                  <th># Days / Hours</th>
                  <th>Requested For</th>
                  <th>Reason</th>
                  <th>Approve</th>
                  <th>Deny</th>
   				  <th>Select &nbsp;<input type='checkbox' class="selectcheckBoxAll" name='selectLeavesAll' value=''></th>
              
                </thead>
  <tbody id="activeReq">
				<?php
				if(mysqli_num_rows($getLEaves)>0)
				{
					while($getLEavesRow=mysqli_fetch_assoc($getLEaves))
					{
				?>
				<tr>
					<td style="display:none;" class="ReqID"><?php echo $getLEavesRow['req_id']; ?></td>
					<td style="display:none;" class="EmployeeIDHidden"><?php echo $getLEavesRow['employee_id']; ?></td>
					 <td ><?php echo $getLEavesRow['employee_id']; ?></td>
					 <td class="name"><?php echo $getLEavesRow['name']; ?></td>
					 <td><?php echo $getLEavesRow['leave_type']; ?></td>
					 <td class="fromDate"><?php echo $getLEavesRow['leave_from']; ?></td>
					 <td class="toDate"><?php echo $getLEavesRow['leave_to']; ?></td>
					 <td><?php echo $getLEavesRow['date_of_request']; ?></td>
					 <?php
					 if($getLEavesRow['leave_type']=='Permission')
					 {
					 ?>
							<td><?php echo $getLEavesRow['number_of_days'].' Hour(s)'; ?></td>
					 <?php
					 }
					 else
					 {
						 if($getLEavesRow['leave_type']=='Privilege & Sick')
						 {
					 ?>
							<td><?php echo $getLEavesRow['number_of_days'].' Day(s) (PL : '.$getLEavesRow['combination_1'].' & SL : '.$getLEavesRow['combination_2'].')'; ?></td>
						 <?php
						 }
						 if($getLEavesRow['leave_type']=='Casual & Sick')
						 {
						 ?>
						 <td><?php echo $getLEavesRow['number_of_days'].' Day(s) (CL : '.$getLEavesRow['combination_1'].' & SL : '.$getLEavesRow['combination_2'].')'; ?></td>
						 <?php
						 }
						 if($getLEavesRow['is_combined_leave']=='N')
						 {
						 ?>
						 <td><?php echo $getLEavesRow['number_of_days'].' Day(s)'; ?></td>
						 <?php
						 }
						 ?>
					 <?php
					 }
							?>
					 <td><?php echo $getLEavesRow['leavE_for']; ?></td>
					 <td><?php echo $getLEavesRow['reason']; ?></td>
					<td><a href="ApproveLeave.php?id=<?php echo $getLEavesRow['req_id'];?>" class="ApproveClass"><img alt='User' src='../../dist/img/Tick_Mark_Dark-512.png' title="Approve Leave" width='18px' height='23px' /></a></td>
					<td><a href="#" id="NoReported" data-toggle="modal" data-target="#modal-default"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Deny Leave" width='18px' height='18px' /></a></td>
					<td><input type='checkbox' class="selectcheckBox" name='selectLeaves[]' value='<?php echo $getLEavesRow['req_id'];?>'></td>			
  </tr>
				
				
				<?php
					}
				}
				else
				{
				?>
				<tr>
				<td>No Request(s) Pending!</td>
				</tr>
				<?php
				}
				?>
  </tbody>
  </table>
  
  <a href="#"  class="btn btn-success pull-right" title="Click to approve all the leaves under your queue!" style="margin-right:6px;" id="ApproveSelected" disabled>Approve Selected</a>
</form>
  
  
  
  <br>
  <br>
<h4 class="box-title"><strong>History of Request(s)</strong>
			 <?php
			 include("config.php");
			include("config.php");
			 $getLEaves = mysqli_query($db,"select req_id,a.employee_id,concat(b.first_name,' ',b.last_name) as name,leave_type,number_of_days,status as action,if(leavE_for='','--',leavE_for) as leavE_for,reason,
date(a.created_date_and_time) as  date_of_request,
date(leave_from) as  leave_from,
if(leave_to='0001-01-01','--',date(leave_to)) as  leave_to,
is_approved,reason_for_denial,combination_1,combination_2,is_combined_leave,is_canceled,is_expired from leave_request a
left join employee_details b on a.employee_id=b.employee_id
 where allocated_to='$employeeid' and a.is_Active='N' and year(leave_from)=year(curdate()) order by a.created_date_and_time desc ;");
			 ?>
			  </h4>
  <table id="example1" style="padding: 0px;" class="table table-bordered table-striped dataTable">
   <thead>
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Request Type</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Date of Request</th>
                  <th># Day(s) /  Hour(s)</th>
                  <th>Request For</th>
                  <th>Approval Status</th>
                  <th>Denial Reason</th>
                  <th>Cancel</th>
                 
                </thead>
				<?php
				if(mysqli_num_rows($getLEaves)>0)
				{
					while($getLEavesRow=mysqli_fetch_assoc($getLEaves))
					{
				?>
				<tr>
					 <td><?php echo $getLEavesRow['employee_id']; ?></td>
					 <td><?php echo $getLEavesRow['name']; ?></td>
					 <td><?php echo $getLEavesRow['leave_type']; ?></td>
					 <td><?php echo $getLEavesRow['leave_from']; ?></td>
					 <td><?php echo $getLEavesRow['leave_to']; ?></td>
					 <td><?php echo $getLEavesRow['date_of_request']; ?></td>
					 <?php
					 if($getLEavesRow['leave_type']=='Permission')
					 {
					 ?>
							<td><?php echo $getLEavesRow['number_of_days'].' Hour(s)'; ?></td>
					 <?php
					 }
					 else
					 {
						 if($getLEavesRow['leave_type']=='Privilege & Sick')
						 {
					 ?>
							<td><?php echo $getLEavesRow['number_of_days'].' Day(s) (PL : '.$getLEavesRow['combination_1'].' & SL : '.$getLEavesRow['combination_2'].')'; ?></td>
						 <?php
						 }
						 if($getLEavesRow['leave_type']=='Casual & Sick')
						 {
						 ?>
						 <td><?php echo $getLEavesRow['number_of_days'].' Day(s) (CL : '.$getLEavesRow['combination_1'].' & SL : '.$getLEavesRow['combination_2'].')'; ?></td>
						 <?php
						 }
						 if($getLEavesRow['is_combined_leave']=='N')
						 {
						 ?>
						 <td><?php echo $getLEavesRow['number_of_days'].' Day(s)'; ?></td>
						 <?php
						 }
						 ?>
					 <?php
					 }
							?>
					 <td><?php echo $getLEavesRow['leavE_for']; ?></td>
					  <?php
                       if($getLEavesRow['is_expired']=='Y')
					   {
					 ?>
					  <td><span class="badge bg-yellow">Expired</span></td>
					 <?php
					   }
					   else
					   {
					 ?>
					 <?php
					 if($getLEavesRow['is_approved']=='Y')
					 {
					 ?>
                  <td><span class="badge bg-green">Granted</span></td>
				  
				  <?php
					 }
					 elseif($getLEavesRow['is_approved']=='N')
					 {
				  ?>
				  <td><span class="badge bg-red">Denied</span></td>
				  <?php
					 }
					 else
					 {
						 if($getLEavesRow['is_canceled']=='N')
						 {
				  ?>
				  <td><span class="badge bg-blue">Request Canceled</span></td>
				  <?php
						 }
						 else
						 {
							 ?>
							   <td><span class="badge bg-blue">Manager Canceled</span></td>
							 <?php
						 }
					 }
				  ?>
				   <?php
					   }
				  ?>
				   <?php
					 if($getLEavesRow['is_approved']=='N')
					 {
					 ?>
                  <td><?php echo $getLEavesRow['reason_for_denial']?></td>
				  
				  <?php
					 }
					 else
					 {
				?>
					<td>--</td>
					<?php
					 }
					?>
					 <?php
					 if($getLEavesRow['is_approved']=='Y' && $getLEavesRow['leave_from']>= date('Y-m-d'))
					 {
					 ?>
						<td><a href="CancelLeave.php?id=<?php echo $getLEavesRow['req_id'];?>" class="CancelClass"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Cancel Leave" width='18px' height='18px' /></a></td>
						 <?php
					 }
					 else
					 {
				?>
					<td>--</td>
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
				<td>No Leave Requests!</td>
				</tr>
				<?php
				}
				?>
  </table>

<br>
  <br>
  <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Deny Leave</h4>
              </div>
              <div class="modal-body">
				 <div class="box box-info">
           <?php

		   ?>
            <form id="RemoveForm" action="ModifyJoinee.php" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
		   <div class="form-group">

			  <input type="hidden" name="ReqID" id="ReqID" />
			 <h5>Name : <label id="NameLBl"><strong></strong> </label></h5>
			 <br>
	

              </div>
			<div class="form-group">
			 <h5>From : <label id="fromLBl"><strong></strong> </label></h5>
			 <br>
	

              </div>

            </div>
			 <div class="col-md-6">
			 <div class="form-group">
			 <h5>To : <label id="ToLBl"><strong></strong> </label></h5>
			 <br>
	

              </div>
              <div class="form-group">
			 <label>Reason for Denial</label>
			 <br>
	
                 <textarea name="reason" id="reason" rows="5" cols="40" maxlength="70" class="is-maxlength" required="required" style="width: 100%;" required></textarea>
              </div>
			
		</div>
            </div>
           

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>	
            </div>
            
            <div class="modal-footer">
                <button type="button" id="closeRole" onclick="clearFields1();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SubmitData" value="Deny Request" class="btn btn-primary" />
              </div>
			  </form>
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
			 $getTeam = mysqli_query($db,"select employee_id,concat(first_name,' ',last_name) as name from employee_Details where reporting_manager_id='$employeeid' and is_active='Y'");
			 ?>
			  </h4>
 <table id="example3" style="padding: 0px;" class="table table-bordered table-striped dataTable" style="margin:1em auto;">
   <thead>
   
				  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Apply Leave</th>
                  <th>Shift Schedule</th>
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
					<td><a href="ManualLeaveRequest.php?id=<?php echo $getTeamRow['employee_id']; ?>" class="EmpMgmt" id="NoReported" ><img alt='User' src='../../dist/img/Tick_Mark_Dark-512.png' title="Apply Leave" width='18px' height='20px' /></a></td>
					<td><a href="ManualShiftChange.php?id=<?php echo $getTeamRow['employee_id']; ?>" id="ShiftChange" class="EmpMgmt" ><img alt='User' src='../../dist/img/32-512.png' title="Change Shift" width='18px' height='20px' /></a></td>
					<?php
					$getApproved = mysqli_query($db,"select * from employee where employee_id='".$getTeamRow['employee_id']."' and is_comp_off_eligible='Y'");
					if(mysqli_num_rows($getApproved)>0)
					{
					?>
					<td><a href="CompOffRequest.php?id=<?php echo $getTeamRow['employee_id']; ?>" id="CompOff" class="EmpMgmt" ><img alt='User' src='../../dist/img/Gym_2-512.png' title="Approve Comp Off" width='18px' height='18px' /></a></td>
					
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
              <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Leave Updated Successfully!</h4>
              </div>
              <div id="leaveBalTable" class="modal-body">
                  
		   </div>
              </div>
			    <div class="modal-footer">
                <button type="button" id="closeBal" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>  
		  
		  
		<button type="button" style="display:none;" id="DIsableScreen" data-toggle="modal" data-target="#myModal">Launch modal</button>	  
		  
		  
        <!-- /.col -->
      </div>
	  <br>
	  <br>
	
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
<script src="toastr/toastr.js"></script>
<script>
$('.selectcheckBoxAll').click(function() {
debugger;
    if ($(this).is(':checked')) {
        $('.selectcheckBox').prop('checked', true);
    	$('#ApproveSelected').removeAttr("disabled");
    } else {
        $('.selectcheckBox').prop('checked', false);
    	$('#ApproveSelected').attr('disabled',true);
    }
});
</script>
<script>
$('#activeReq').click(function () {
debugger;
        $('#activeReq tr').each(function () {
        debugger;
            if ($(this).find('.selectcheckBox').prop('checked')) {
                doEnableButton = true;
            		return false;
            }
        else
        {
        doEnableButton = false;
        }
            
        });
if (doEnableButton == false) {
 debugger;
                $('#ApproveSelected').attr('disabled',true);
            }
            else {
             debugger;
                $('#ApproveSelected').removeAttr("disabled");
            }
    });
</script>
<script>
$('#ApproveAll').on('click', function() {
	ajaxindicatorstart("Approving all the leaves. This might take a while..");
	location.reload();
	});

</script>
<script>
$('#ApproveSelected').on('click', function() {
debugger;
	ajaxindicatorstart("Approving selected leaves. This might take a while..");
	var data = $("#SelectForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "ApproveSelectedLeave.php",
         success: function(data)
		 {
			debugger;         
			 location.reload();
			ajaxindicatorstop();
         }
});
	});

</script>
<script>
$(document).ready(function(){
	
			<?php
		   if($_SESSION['appall']!='')
		   { 
		   ?>
        	toastMsg();
		   <?php
		   $_SESSION['appall']='';
		   }
			?>
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	
			<?php
		   if($_SESSION['BalEmpID']!='')
		   { 
		   ?>
		var BalEmpID = $('#BalEmpID').val();
		$.ajax({
		url: 'LeaveBalanceCheck.php',
      type: 'post',
		data: {
      	'EmpID' : BalEmpID,
		},
      success: function(response){
      debugger;
      	if (response != '' ) {
        
        	if(response!='NIL')
            {
          		 $("#leaveBalTable").html(response);
            $("#DIsableScreen").click(); 
            }
        else
        {
        	toastMsg();
        }
		   
		   <?php
		   $_SESSION['BalEmpID']='';
		   ?>
		  
      }
 	}
	});
    <?php
		   }
	?>
});
</script>
<script type="text/javascript">
function toastMsg(){
debugger;
             var i = -1;
             var toastCount = 0;
             var $toastlast;

             var getMessage = function () {
                 var msgs = ['My name is Inigo Montoya. You killed my father. Prepare to die!',
                     '<div><input class="input-small" value="textbox"/>&nbsp;<a href="http://johnpapa.net" target="_blank">This is a hyperlink</a></div><div><button type="button" id="okBtn" class="btn btn-primary">Close me</button><button type="button" id="surpriseBtn" class="btn" style="margin: 0 8px 0 8px">Surprise me</button></div>',
                     'Are you the six fingered man?',
                     'Inconceivable!',
                     'I do not think that means what you think it means.',
                     'Have fun storming the castle!'
                 ];
                 i++;
                 if (i === msgs.length) {
                     i = 0;
                 }

                 return msgs[i];
             };

             var getMessageWithClearButton = function (msg) {
                 msg = msg ? msg : 'Clear itself?';
                 msg += '<br /><br /><button type="button" class="btn clear">Yes</button>';
                 return msg;
             };

             $('#closeButton').click(function() {
                 if($(this).is(':checked')) {
                     $('#addBehaviorOnToastCloseClick').prop('disabled', false);
                 } else {
                     $('#addBehaviorOnToastCloseClick').prop('disabled', true);
                     $('#addBehaviorOnToastCloseClick').prop('checked', false);
                 }
             });

     				var msg = 'Updated Successfully!';

     					if(msg!=''){


     		debugger;
                 var shortCutFunction = "success";
                 var msg = $('#message').val();
                 var title = $('#title').val() || '';
                 var $showDuration = $('#showDuration');
                 var $hideDuration = $('#hideDuration');
                 var $timeOut = $('#timeOut');
                 var $extendedTimeOut = $('#extendedTimeOut');
                 var $showEasing = $('#showEasing');
                 var $hideEasing = $('#hideEasing');
                 var $showMethod = $('#showMethod');
                 var $hideMethod = $('#hideMethod');
                 var toastIndex = toastCount++;
                 var addClear = $('#addClear').prop('checked');

                 toastr.options = {
                     closeButton: false,
                     debug: false,
                     newestOnTop: false,
                     progressBar: false,
                     rtl: false,
                     positionClass: $('#positionGroup input:radio:checked').val() || 'toast-top-right',
                     preventDuplicates: false,
                     onclick: null
                 };

                 if ($('#addBehaviorOnToastClick').prop('checked')) {
                     toastr.options.onclick = function () {
                         alert('You can perform some custom action after a toast goes away');
                     };
                 }

                 if ($('#addBehaviorOnToastCloseClick').prop('checked')) {
                     toastr.options.onCloseClick = function () {
                         alert('You can perform some custom action when the close button is clicked');
                     };
                 }


                     toastr.options.showDuration = 1000;



                     toastr.options.hideDuration = 1000;



                     toastr.options.timeOut = 5000;



                     toastr.options.extendedTimeOut = 1000;



                     toastr.options.showEasing = 'swing';



                     toastr.options.hideEasing = 'linear';



                     toastr.options.showMethod = 'fadeIn';



                     toastr.options.hideMethod = 'fadeOut';



                 if (!msg) {
                     msg = getMessage();
                 }
     						msg='Updated Successfully!'
                 $('#toastrOptions').text('Command: toastr["'
                         + shortCutFunction
                         + '"]("'
                         + msg
                         + (title ? '", "' + title : '')
                         + '")\n\ntoastr.options = '
                         + JSON.stringify(toastr.options, null, 2)
                 );

                 var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                 $toastlast = $toast;

                 if(typeof $toast === 'undefined'){
                     return;
                 }

                 if ($toast.find('#okBtn').length) {
                     $toast.delegate('#okBtn', 'click', function () {
                         alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
                         $toast.remove();
                     });
                 }
                 if ($toast.find('#surpriseBtn').length) {
                     $toast.delegate('#surpriseBtn', 'click', function () {
                         alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
                     });
                 }
                 if ($toast.find('.clear').length) {
                     $toast.delegate('.clear', 'click', function () {
                         toastr.clear($toast, { force: true });
                     });
                 }
             }

             function getLastToast(){
                 return $toastlast;
             }
             $('#clearlasttoast').click(function () {
                 toastr.clear(getLastToast());
             });
             $('#cleartoasts').click(function () {
                 toastr.clear();
             });
         }
</script>
<script>
$('.ApproveClass').on('click', function() {
	ajaxindicatorstart("Please Wait..");
	location.reload();
	});
</script>
<script>
$('.CancelClass').on('click', function() {
	ajaxindicatorstart("Please Wait..");
	location.reload();
	});
</script>
<script>
$('.EmpMgmt').on('click', function() {
	ajaxindicatorstart("Please Wait..");
	});
</script>
<script>
$('.DenyClass').on('click', function() {
	ajaxindicatorstart("Please Wait..");
	location.reload();
	});
</script>
<script>

</script>
<script type="text/javascript">
   $(document).on('click','#closeRole',function(e) {
		 location.reload();	
});
    </script>
<script>

	$(function() {
  var bid, trid;
  $('#ReqLeaves tr').click(function() {
       Id = $(this).find('.ReqID').text();
       name = $(this).find('.name').text();
       from = $(this).find('.fromDate').text();
       to = $(this).find('.toDate').text();
		$('#ReqID').val(Id);
		$('#NameLBl').text(name);
		$('#fromLBl').text(from);
		$('#ToLBl').text(to);
  });
});
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
		if(to==fromval)
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
$('#datepicker').on('change', function() {
		var to = $('#datepicker').val();
		var fromval = $('#datepicker1').val();
		if(to=='' || fromval=='')
		{
			alert("Please Fill in From and to Dates");
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
        
          $('#NumberDays').val(response);
      }
 	}
	});
   }
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
<script type="text/javascript">
$(document).ready(function() {
    $("#RemoveForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#RemoveForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "DenyLeave.php",
         success: function(data){

		location.reload();
		   ajaxindicatorstop();

         }
});

});
});
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
require_once('layouts/documentModals.php');
?>
</body>
</html>
<?php
}
else
{
?>
<!DOCTYPE html>
<html>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.bgimg {
  background-image: url('h-under-maintenance-background.jpg');
  height: 100%;
  background-position: center;
  background-size: cover;
  position: relative;
  color: black;
  font-family: "Calibri", Courier, monospace;
  font-size: 20px;
}

.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
}

.middle {
  position: absolute;
  top: 20%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

hr {
  margin: auto;
  width: 40%;
}
</style>
<body>

<div class="bgimg">
  <div class="middle">
    <h1>Server Down. Please try back in a while.</h1>
    <hr>
    <p>Thank you for your Patience.</p>
  </div>
  <div class="bottomleft">
    <p>Acurus Solutions</p>
  </div>
</div>

</body>
</html>




<?php
}
?>
