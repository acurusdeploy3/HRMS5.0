<?php
include("config.php");
$employeeid=$_GET['id'];
session_start();
$MyID = $_SESSION['login_user'];
$checkifMngr = mysqli_query($db,"select * from employee_details where employee_id='$employeeid' and reporting_manager_id='$MyID'");
$isAdminAcnt = mysqli_query($db,"select * from user_access_control where main_menu='Attendance Management' and sub_menu='Admin_Control' and accessed_to='$MyID';");
if(mysqli_num_rows($isAdminAcnt)>0)
{
?>
<?php
include("config.php");
$employeeid=$_GET['id'];
$getName= mysqli_query($db,"select concat(first_name,' ',last_name) as name from employee_Details where employee_id='$employeeid'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['name'];
include("Attendance_Config.php");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Shift Change</title>
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
       Leave Management

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="TeamLeaveRequest.php">Team Leave Request</a></li>
        <li class="active">Shift Change</li>
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
			  <button onclick="window.location='SearchEmployeeShift.php'" type="button" style="width:30%" class="btn btn-block btn-primary btn-flat">Back</button>
			  <br>
			  Modify Shift for <strong><?php echo ' '.$EmpName.'' ?></strong>
			 <br>
			  </h3>

              <div class="box-tools">
              </div>
            </div>
			 <?php
			  $getShiftTypes = mysqli_query($att,"SELECT shift_code,start_time,end_time FROM `shift`;");
			  $getShiftTypes1 = mysqli_query($att,"SELECT shift_code,start_time,end_time FROM `shift`;");
			  ?>
			<a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#myModal" class="btn btn-danger pull-right">Skip Upload</a>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
             <form id="ShiftForm" method="post" class="form-horizontal" action="">
			  <div class="col-md-4">
              <div class="form-group" style="width:90%">
			  <input type="hidden" id="EmpName" value="<?php echo $EmpName; ?>"/>
			 
			  <input type="hidden" id="AppEmpId" name="AppEmpId" value="<?php echo $employeeid; ?>"/>
			  <label>Shift Type <span class="astrick">*</span><a href="#" title="View All Shift Types" id="additionalLevel" data-toggle="modal" data-target="#modal-default"> <i class="fa fa-fw fa-info-circle"></i></a></label>
                 <select class="form-control select2" id="EmployeeShift" name="EmployeeShift" required="required" style="width: 100%;" required>
                 <option value=""  selected disabled>Please Select from Below</option>
				 <?php
				 while($getShiftTypesRow = mysqli_fetch_assoc($getShiftTypes))
				 {
				 ?>
                 <option value="<?php echo $getShiftTypesRow['shift_code']; ?>"><?php echo $getShiftTypesRow['shift_code'].' ('.$getShiftTypesRow['start_time'].' - '.$getShiftTypesRow['end_time'].')'; ?>  </option>
				 
				 <?php
				 }
				 ?>	
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
				  
                  <input type="text" name="dateFrom" autocomplete="off" class="form-control pull-right"  required="required" id="datepicker1" placeholder="Pick a date" required>
				
                </div>
				</div> 
				
				 
			  </div>
			    <div class="col-md-4">
              <div class="form-group" style="width:90%">
			    <label>To<span class="astrick">*</span> </label>
                <div class="input-group date">
                   <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="dateTo" autocomplete="off" class="form-control pull-right"  required="required" id="datepicker" placeholder="Pick a date" required>
				
                </div>
				</div>

			  <br>
			  <div class="form-group" style="width:90%">
			  <input type="submit" style="background-color: #00a65a;" style="width:90%" id="submitData" class="btn btn-info pull-right" value="Allocate Shift" />
			   </div>
			  </div>
			 </form>
<br>
<br>  
<h4 class="box-title"><strong>Current & Future Employee Shifts</strong>
			 <?php
			 include("config.php");
			 $getEmpShifts = mysqli_query($att,"select shift_code,start_date,end_date from employee_shift where employee_id=$employeeid and
				(
				(curdate() between start_date and end_Date)
          or (month(start_date)=month(curdate()) and year(start_date)=year(curdate()))
          or (month(end_date)=month(curdate()) and year(end_date)=year(curdate()))
				) order by start_date;");
			 ?>
			  </h4>  
<table id="ShiftTable" style="padding: 0px;" class="table table-bordered table-striped dataTable">
   <thead>
                  <th>Shift Code</th>
                  <th>From</th>
                  <th>To </th>
                  
                </thead>
				<?php
				while($getEmpShiftsRow = mysqli_fetch_assoc($getEmpShifts))
				{
				?>
				<tr>
					<td><?php echo $getEmpShiftsRow['shift_code']; ?></td>
					 <td><?php echo $getEmpShiftsRow['start_date']; ?></td>
					 <td><?php if ($getEmpShiftsRow['end_date']!='2050-12-31') { echo $getEmpShiftsRow['end_date']; } else { echo '<span class="badge bg-green">Indefinite</span>'; } ?></td>
				</tr>
				
				<?php
				}
				?>
				
  </table>	
  <br>
     <h5 class="box-title">
			<strong>Note : </strong> To Modify a allocated Shift, Select the same dates to be Modified & Allocate it Again.
			  </h5>  <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">All Shift Types</h4>
              </div>
			 
              <div class="modal-body">
                 <table id="leaveTable" style="padding: 0px;" class="table table-bordered">
                <tr>
                  <th>Shift Code</th>

                  <th>Start Time</th>
                  <th>End Time</th>
                </tr>
				<?php
				while($getShiftTypesRow = mysqli_fetch_assoc($getShiftTypes1))
				{
				?>
				<tr>
                  <td><?php echo $getShiftTypesRow['shift_code'];  ?></td>
                  <td><span class="badge bg-blue"><?php echo $getShiftTypesRow['start_time'];  ?></span></td>
                  <td><span class="badge bg-green"><?php echo $getShiftTypesRow['end_time'];  ?></span></td>
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
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>	<script>
	 $("#ShiftForm").submit(function(e) {

	ajaxindicatorstart("Hold On..Allocating Shift..");
	e.preventDefault();
	returnvalue = true;
	var from = document.getElementById("datepicker1").value;
	var to = document.getElementById("datepicker").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		returnvalue = false;
	}
	if(returnvalue==true)
	{
		var data =  $("#ShiftForm").serialize();
		$.ajax({
         data: data,
         type: "post",
         url: "ShiftCheck.php",
         success: function(data)
		 {
				if(data=='pos')
					{
					SubmitRequest();
					}
				else
				{
					alert("Requested Dates Involve two different Shifts. Modify them Individually!");
					ajaxindicatorstop();
					return false;
				}
				
		 }
	 });
}
	else
	{
		ajaxindicatorstop();
		return false;
	}
});
	</script>
<script>
	function SubmitRequest()
	{
	ajaxindicatorstart("Hold On..Allocating Shift..");
	var data = $("#ShiftForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "AdminAllocateShift.php",
         success: function(data)
		 {
			 location.reload();
			ajaxindicatorstop();
         }
});
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
    var today = new Date();
	var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
	var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    $('#datepicker').datepicker({
      autoclose: true,
	  startDate: firstDay
	  
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: firstDay
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
