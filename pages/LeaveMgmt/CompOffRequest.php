<?php
include("config.php");
include("Attendance_Config.php");
$employeeid=$_GET['id'];
$getName= mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_Details where employee_id='$employeeid'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
$name=$_SESSION['login_user'];
$checkREporting = mysqli_query($db,"select * from employee_details where employee_id='$employeeid' and reporting_manager_id='$name'");
if(mysqli_num_rows($checkREporting)>0)
{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Compensatory Off</title>
  <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=0.25, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
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
       Compensatory Off

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="TeamLeaveRequest.php">Team Leave Request</a></li>
        <li class="active">Compensatory Off</li>
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
		<div class="box">
		<div class="box-header">
              <h3 class="box-title">
			  <button onclick="window.location='TeamLeaveRequest.php'" type="button" style="width:30%" class="btn btn-block btn-primary btn-flat">Back</button>
			  <br>
			Compensatory Off For : <strong><?php echo ' '.$EmpName.' : '.$employeeid ?></strong>
			 <br>
			  </h3>

              <div class="box-tools">
              </div>
            </div>
            <div class="box-body table-responsive no-padding">
            

  <br>
<h4 class="box-title"><strong>Additional Hours List</strong>
			 <?php
			 include("config.php");
			 include("Attendance_Config.php");
			 $getReportingTeam = mysqli_query($db,"select employee_id from employee_details where reporting_manager_id='$employeeid'");
			 $getReportingTeamRow = mysqli_fetch_array($getReportingTeam);
			 $ReportingTeam = $getReportingTeamRow['employee_id'];
			 $getExtraHours = mysqli_query($att,"select extra_hours_tracker_id,employee_id,date,from_time,to_time,SUBSTRING(extra_hours, 1, 5) as Extra_hours,
((SUBSTRING(extra_hours, 1, 2)*60)+SUBSTRING(extra_hours, 4, 2)) as extrahours from extra_hours_tracker where employee_id = '$employeeid' and is_approved='N' and is_applied='N';");
			 ?>
			  </h4>
  <table id="example1" style="padding: 0px;" class="table table-bordered table-striped dataTable">
   <thead>
				  <th style="display:none;">Employee ID</th>
				  <th style="display:none;">Extra Hours</th>
                  <th>Date</th>
                  <th>Extra Hours</th>
                  <th>Approved Hours</th>
                  <th>Comments</th>
                  <th>Approve</th>
                 
                </thead>
				<?php
				if(mysqli_num_rows($getExtraHours)>0)
				{
					while($getExtraHoursRow=mysqli_fetch_assoc($getExtraHours))
					{
				?>
				<form id="update" name='update' method='POST'>
				<tr>
				<td style="display:none;"><input type='hidden' name='idtxt' class="idtxt" value='<?php echo $getExtraHoursRow['extra_hours_tracker_id']; ?>'/></td>
				<td style="display:none;"><input type='hidden' name='extraHoursinMins' id="extraHoursinMins" class="extraHoursinMins" value='<?php echo $getExtraHoursRow['extrahours']; ?>'/></td>
					 <td><?php echo $getExtraHoursRow['date']; ?></td>
					 <td><span class="badge bg-green"><?php echo $getExtraHoursRow['Extra_hours']; ?></span></td>	
					 <td>
					 <select id="Hours" name="Hours" class="Hours"> 
					 <option value="00">00</option>
					 <option value="01"> 01</option>
					 <option value="02"> 02</option>
					 <option value="03"> 03</option>
					 <option value="04"> 04</option>
					 <option value="05"> 05</option>
					 <option value="07"> 07</option>
					 <option value="09"> 09</option>
					 <option value="11">11</option>
					 <option value="12"> 12</option>
					 </select> : &nbsp;&nbsp;
					 <select id="Minutes" name="Minutes" class="Minutes"> 
					 <option value="00">00</option>
					 <option value="05"> 05</option>
					 <option value="10"> 10</option>
					 <option value="15"> 15</option>
					 <option value="20"> 20</option>
					 <option value="25"> 25</option>
					 <option value="30"> 30</option>
					 <option value="35"> 35</option>
					 <option value="40">40</option>
					 <option value="45"> 45</option>
					 <option value="50"> 50</option>
					 <option value="55"> 55</option>
					 </select>
					 </td>	
					   <td>   <textarea name="reason" id="reason" rows="2" cols="17" maxlength="200" class="reason" required="required" required style="
    width: 100%;"></textarea></td>
					 <td><input type='submit' style="background-color:#1f9e40; color:white;" name='save_btn' class="Approve" value='Approve' style='font-size:1em;'/></td>
				  </form>
				</tr>
				<?php
					   }
				  ?>
				<?php
					}
				else
				{
				?>
				<tr>
				<td>No Data Found!</td>
				</tr>
				<?php
				}
				?>
  </table>
		</div>
		</div>
  <br>
  <br>
  <div class="box">
  <div class="box-body table-responsive no-padding">
  <h4 class="box-title"><strong>Approved Extra Hours</strong>
			 <?php
			 include("config.php");
			 include("Attendance_Config.php");
			 $getReportingTeam = mysqli_query($db,"select employee_id from employee_details where reporting_manager_id='$employeeid'");
			 $getReportingTeamRow = mysqli_fetch_array($getReportingTeam);
			 $ReportingTeam = $getReportingTeamRow['employee_id'];
			 $getApprovedHours = mysqli_query($att,"select extra_hours_tracker_id,employee_id,date,from_time,to_time,SUBSTRING(extra_hours, 1, 5) as Extra_hours,SUBSTRING(approved_hours, 1, 5) as approved_hours,
((SUBSTRING(extra_hours, 1, 2)*60)+SUBSTRING(extra_hours, 4, 2)) as extrahours from extra_hours_tracker where employee_id= '$employeeid' and is_approved='Y' and is_applied='N';");
			 ?>
			  </h4>
  <table id="example2" style="padding: 0px;" class="table table-bordered table-striped dataTable">
   <thead>
				  <th style="display:none;">Employee ID</th>
				  <th style="display:none;">Extra Hours</th>
                  <th>Date</th>
                  <th>Approved Hours</th>
                  <th>Actual Hours</th>
                  <th>Add</th>
                 
                </thead>
				<?php
				if(mysqli_num_rows($getApprovedHours)>0)
				{
					while($getApprovedHoursRow=mysqli_fetch_assoc($getApprovedHours))
					{
				?>
				
				<tr>
				<td style="display:none;"><input type='hidden' name='ApprovedID' class="ApprovedID" value='<?php echo $getApprovedHoursRow['extra_hours_tracker_id']; ?>'/></td>
				<td style="display:none;"><input type='hidden' name='ApprovedHoursinMins' id="ApprovedHoursinMins" class="ApprovedHoursinMins" value='<?php echo $getApprovedHoursRow['approved_hours']; ?>'/></td>
					 <td><?php echo $getApprovedHoursRow['date']; ?></td>
					 <td><span class="badge bg-green"><?php echo $getApprovedHoursRow['approved_hours']; ?></span></td>		
					 <td><span class="badge bg-blue"><?php echo $getApprovedHoursRow['Extra_hours']; ?></span></td>		
					 <td><input type='checkbox' name='save_btn' class="Apply" value='Apply' style='font-size:1em;'/></td>
				</tr>
				<?php
					   }
				  ?>
				<?php
					}
				else
				{
				?>
				<tr>
				<td>No Data Found!</td>
				</tr>
				<?php
				}
				?>
  </table>
  
   <br>
  <br>
  </div>
		</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
<br>
<br>
<form id="Applyform" method="post">
 <div class="col-md-3">
		   <div class="form-group" style="width:90%">
			  <label>Employee ID</label>
                  <input type="text" style=" background-color:#1e82af; color:white;" name="EmployeeID" id="EmployeeID" value="<?php echo $employeeid ?>"  class="form-control pull-right"  required="required" placeholder="# Hours" required readonly>
				<span></span>
			  </div>
		 </div>
		 <div class="col-md-3">
		   <div class="form-group" style="width:90%">
			  <label>Employee Name</label>
                  <input type="text" style=" background-color:#1e82af; color:white;" name="EmployeeName" id="EmployeeName" value="<?php echo $EmpName ?>"  class="form-control pull-right"  required="required" placeholder="# Hours" required readonly>
				<span></span>
			  </div>
		 </div>
		  <div class="col-md-3">
		   <div class="form-group" style="width:90%">
			  <label>No of Hours*</label>
                  <input type="hidden" name="input_hidden_field" id="input_hidden_field"  class="form-control pull-right"  required="required" placeholder="# Hours" required readonly>
                  <input type="text" style=" background-color:#1e82af; color:white;" style="" name="NumberHours" id="NumberHours" value="00:00"  class="form-control pull-right"  required="required" placeholder="# Hours" required readonly>
				<span></span>
			  </div>
		 </div>
		  <div class="col-md-3">
		    <div class="form-group" style="width:90%">
			  <label>Unit<span class="astrick">*</span></label>
                 <select class="form-control select2" id="UnitType" name="UnitType" required="required"  style="width: 100%;" required>
                 <option value=""  selected disabled>Choose from Below</option>
                 <option value="Half Day">Half Day</option>
                 <option value="Full Day">Full Day</option>
				</select>
              </div>
			    <div class="form-group" style="width:90%">
			 
                <input type="submit" style="background-color: #00a65a;" style="width:90%" id="submitData" class="btn btn-info pull-right" value="Apply Comp-Off" />
               <br>
		 <br>
			  </div>
			   </div>
			   <div class="col-md-3">
			
              </div>
		
		   <div class="col-md-3">
		     
		 </div>
		 </form>
		 <br>
		 <br>
		 <br>
		 <br>
		  <div class="col-xs-12">
	 <div class="box">
   <!-- /.col -->
		 <br>
	<h4 class="box-title"><strong>Applied Compensatory Off's</strong>
			 <?php
			 include("config.php");
			 include("Attendance_Config.php");
			 $getAppliedHours = mysqli_query($att,"select id,comp_off_date,duration,unit,expiry_date,if(DATEDIFF(expiry_date,curdate())<=15,'E','NE') as is_Eligible,extended_by,date(extended_date) as extended_date from comp_off_tracker where employee_id='$employeeid' and is_availed='N'");
			 ?>
			  </h4>
			   <div class="box-body table-responsive no-padding">	 
  <table id="example3" style="padding: 0px;" class="table table-bordered table-striped dataTable">
   <thead>
				  <th style="display:none;">Employee ID</th>
                  <th>Date</th>
                  <th>Unit</th>
                  <th>Expiry Date</th>
                  <th>Days of Extension</th>
                  <th>Extend</th>
                 
                </thead>
				<?php
				if(mysqli_num_rows($getAppliedHours)>0)
				{
					while($getAppliedHoursRow=mysqli_fetch_assoc($getAppliedHours))
					{
				?>
				<form id="ExtenForm" name='ExtenForm' method='POST'>
				<tr>
				<td style="display:none;" class="CompOffID1"><input type='hidden' name='CompOffID' class="CompOffID" value='<?php echo $getAppliedHoursRow['id']; ?>'/></td>
			    
				<td><?php echo $getAppliedHoursRow['comp_off_date']; ?></td>
				<?php
				if($getAppliedHoursRow['unit']=='0.5')
				{
				?>
			    <td><span class="badge bg-green">Half Day</span></td>		
				<?php
				}
				else
				{
				?>
				   <td><span class="badge bg-blue">Full Day</span></td>	
				<?php
				}
				?>
				<?php
					if($getAppliedHoursRow['extended_by']=='')
					{
					?>
				<td class="ExpDate"><?php echo $getAppliedHoursRow['expiry_date']; ?></td>
				<?php
					}
					else
					{
				?>
				<td><?php echo $getAppliedHoursRow['extended_date']; ?></td>
				<?php
					}
				?>
				<?php
					if($getAppliedHoursRow['is_Eligible']=='E' && $getAppliedHoursRow['extended_by']=='')
					{
					?>				
				<td class="ExtensionDays">
				 <select id="ExtDays" name="ExtDays" class="ExtDays"> 
					 <option value="00">00</option>
					 <option value="05">05</option>
					 <option value="10"> 10</option>
					 <option value="15"> 15</option>
					 <option value="20"> 20</option>
					 <option value="25"> 25</option>
					 <option value="30"> 30</option>
					 <option value="35"> 35</option>
					 <option value="40"> 40</option>
					 <option value="45">45</option>
					 <option value="50"> 50</option>
					 <option value="55"> 55</option>
					 <option value="60"> 60</option>
					 </select>
				</td>		
				<td><input type='submit' style="background-color:#1f9e40; color:white;" name='save_btn' class="Extend" value='Extend' style='font-size:1em;'/></td>
				<?php
					}
					else
					{
				?>
					<td><span class="badge bg-yellow">NA</span></td>
					<td><span class="badge bg-yellow">NA</span></td>
					<?php
					}
					?>
				</tr>
				</form>
				<?php
					   }
				  ?>
				<?php
					}
				else
				{
				?>
				<tr>
				<td>No Data Found!</td>
				</tr>
				<?php
				}
				?>
  </table>	
		
		</div>
		</div>
		</div>
		</div>		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	 <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Compensatory Off Management</h4>
              </div>
              <div id="leaveBalTable" class="modal-body">
                   <h5 class="modal-title">Updated Successfully!</h5>
		   </div>
              </div>
			    <div class="modal-footer">
                <button type="button" id="closeBal" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>  
		  
		  
		<button type="button" style="display:none;" id="DIsableScreen" data-toggle="modal" data-target="#myModal">Launch modal</button>	  
		  	
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
</script>
<!-- Page script -->
<script>

	$(function() {
  $('.Approve').click(function() {
	  ajaxindicatorstart("Approving.. Please Wait");
	 var returnvalue = true;
	  var row = $(this).closest("tr");   
    var Hours = row.find(".Hours").val(); 
    var mins = row.find(".Minutes").val();
	    mins = parseFloat(mins);	
    var reason = row.find(".reason").val(); 
    var ExtraHoursinMins = row.find(".extraHoursinMins").val();
    var tracker_id = row.find(".idtxt").val(); 
	var hoursinMin = Hours*60;
		hoursinMin = parseFloat(hoursinMin);
	var totalMins = hoursinMin + mins;
	 var Comments = row.find(".reason").val();
	if(Hours=='00' && mins=='00')
	{
		alert("Approved Hours Cannot be left as Zero!");
			returnvalue=false;
	}
	if(Comments=='')
	{
		alert("Comments Cannot be left Blank!");
		returnvalue=false;
	}
	if(totalMins>ExtraHoursinMins)
	{
		alert("Selected Hours Exceeds Actual Hours!");
		returnvalue=false;
	}
	if(mins.toString().length==1)
	{
		mins= '0'+mins.toString();
	}
	 if(returnvalue==true)
	 {
			$.ajax({
          data: {
      	'tracker_id' : tracker_id,
      	'Hours' : Hours,
      	'mins' : mins,
      	'reason' : reason,
      },
         type: "post",
         url: "ApproveCompOff.php",
         success: function(data)
		 {
			 $("#DIsableScreen").click();
			//location.reload();
			ajaxindicatorstop();
         }
});
		
	 }	
	else
	{
		ajaxindicatorstop();
		return false;
		
	}	
  });
});
</script>
<script>
$(function() {
	var IDS = [];
  $('.Apply').click(function() {
	  
	  var currentVal = $("#NumberHours").val(); 	 
	  var row1 = $(this).closest("tr"); 
	  var Approved = row1.find(".ApprovedHoursinMins").val();
	  var ApprovedID = row1.find(".ApprovedID").val();
	  
	  var currentValString = currentVal.split(":");
	  var CurrentHours = currentValString[0];
	  var CurrentMins = currentValString[1];
	  var ApprovedString = Approved.split(":");
	  var ApprovedHours = ApprovedString[0];
	  var ApprovedMins = ApprovedString[1];
		  ApprovedMins = parseFloat(ApprovedMins);
		  
	  var ApprovedHoursinMins = ApprovedHours*60;
	  var ApprovedinMins = ApprovedHoursinMins+ApprovedMins;
	  
      if($(this).is(':checked'))
		{
			
			IDS.push(ApprovedID);
			var theFutureTime = moment().hour(CurrentHours).minute(CurrentMins).add(ApprovedinMins,'minutes').format("HH:mm");
			$("#NumberHours").val(theFutureTime); 
		}
		else
		{
			var index = IDS.indexOf(ApprovedID);
			if (index >= 0)
				{
					IDS.splice( index, 1 );
				}
			var theFutureTime = moment().hour(CurrentHours).minute(CurrentMins).subtract(ApprovedinMins,'minutes').format("HH:mm");
			
			$("#NumberHours").val(theFutureTime); 
		}
		$('#input_hidden_field').val(JSON.stringify(IDS));
	  });
});
</script>
<script>

	$(function() {
  $('#Applyform').submit(function(e) {
		e.preventDefault();
	    var returnvalueApp = true;
	  var IDValue = $('#input_hidden_field').val(); //retrieve array
		  IDValue = JSON.parse(IDValue);
	  var jsonString = JSON.stringify(IDValue);
	    var currentVal = $("#NumberHours").val();  
	    var UnitType = $("#UnitType").val(); 
	    var EmployeeID = $("#EmployeeID").val(); 
		
		var currentValString = currentVal.split(":");
		var CurrentHours = currentValString[0];
		var CurrentMins = currentValString[1];	
	  ajaxindicatorstart("Applying.. Please Wait");
	  
	if(CurrentHours=='00' && CurrentMins=='00')
	{
		alert("Please Choose a Valid Approval Hours!");
		returnvalueApp=false;
	}
	if(UnitType=='Half Day' && CurrentHours<'04')
	{
		alert("Minimum Value for Half Day : 4 Hours");
		returnvalueApp=false;
	}
	if(UnitType=='Full Day' && CurrentHours<'08')
	{
		alert("Minimum Value for Full Day : 8 Hours");
		returnvalueApp=false;
	}
	if(UnitType=='Half Day' && CurrentHours>='06')
	{
		 var r = confirm("Chosen Hours has more than Required for an Half Day Unit. Click OK to Confirm");
			 if (r == true)
					{
							returnvalueApp = true;
					}
					else 
					{
							returnvalueApp = false;
					}
	}
	if(UnitType=='Full Day' && CurrentHours>='11')
	{
		 var r = confirm("Chosen Hours has more than Required for an Full Day Unit. Click OK to Confirm");
			 if (r == true)
					{
							returnvalueApp = true;
					}
					else 
					{
							returnvalueApp = false;
					}
	}
	 if(returnvalueApp==true)
	 {
			$.ajax({
          data: {
      	'ApprovalID' : jsonString,
      	'CurrentHours' : CurrentHours,
      	'CurrentMins' : CurrentMins,
      	'UnitType' : UnitType,
      	'EmployeeID' : EmployeeID,
      },
         type: "post",
         url: "ApplyCompOff.php",
         success: function(data)
		 {
			 $("#DIsableScreen").click();
			ajaxindicatorstop();
         }
});
		
	 }	
	else
	{
		ajaxindicatorstop();
		return false;
		
	}	
  });
});
</script>
	<script>
	$(function() {
  $('.Extend').click(function() {
	  ajaxindicatorstart("Extending.. Please Wait");
			 var ERow = $(this).closest("tr");   
			var CoffId = ERow.find(".CompOffID").val(); 
			var ExtDays = ERow.find(".ExtDays").val(); 
			var returnCoffVal = true;
			if(ExtDays=='00')
			{
				alert("Please Select a Valid Extension Days");
				returnCoffVal=false;
			}
			if(returnCoffVal)
			{
				$.ajax({
						data: {
							'CoffId' : CoffId,
							'ExtDays' : ExtDays,
							},
						type: "post",
						url: "ExtendCompOff.php",
						success: function(data)
						{
								$("#DIsableScreen").click();
								//location.reload();
								ajaxindicatorstop();
						}
					});
			}
			else
			{
				ajaxindicatorstop();
				return false;
			}
	    });
});
	</script>
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
	var ext = $('#ExtLabel').text();
    $('#datepicker').datepicker('setStartDate',ext);
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
$('#closeBal').on('click', function() {
	location.reload();
	});
</script>







<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable()
    $('#example3').DataTable()
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