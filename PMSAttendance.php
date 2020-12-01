<?php
	session_start();
	
	$employeeid = $_GET['id'];
	
	$employeeidsession = $_SESSION['login_user'];
	$employeegrp = $_SESSION['login_user_group'];
	if($employeegrp === 'Employee')
	{
		if($employeeid!=$employeeidsession)
		{
			echo 'HRllo';
			header("Location: pages/forms/Logout.php");
		}
	}
	if($employeegrp === 'System Admin')
	{
		if($employeeid!=$employeeidsession)
		{
			echo 'Hi';
			header("Location: pages/forms/Logout.php");
		}
	}
	if($employeegrp === 'Accountant')
	{
		if($employeeid!=$employeeidsession)
		{
			echo 'Fuck';
			header("Location: pages/forms/Logout.php");
		}
	}
	if($employeegrp==='Manager' || $employeegrp ==='System Admin Manager' || $employeegrp === 'Accounts Manager' || $employeegrp ==='Head of Department (Production)' || $employeegrp ==='Head of Department (Software)' || $employeegrp === 'Chief Executive Officer' || $employeegrp === 'Chief Technical Officer')
	{
		include("config.php");
		if($employeeid!=$employeeidsession)
		{
			
		$getmgmr = mysqli_query($db,"select * from employee_details where reporting_manager_id='$employeeidsession' and employee_id=$employeeid");
		if(mysqli_num_rows($getmgmr)==0)
			{
					echo 'Bye';
				header("Location: pages/forms/Logout.php");
			}
		
		}
	}
	?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Performance Review</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

   <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <script src="dist/js/loader.js"></script>

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
       Performance Management System

      </h1>
      <ol class="breadcrumb">
        <li><a href="DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Performance Review</li>
      </ol>
    </section>
	<?php
	session_start();
	
	$employeeid = $_GET['id'];
	
	$employeeidsession = $_SESSION['login_user'];
	$employeegrp = $_SESSION['login_user_group'];
	if($employeegrp == 'Employee' || $employeegrp=='System Admin' || $Employeegrp =='Accountant');
	{
		
	}
	include("config2.php");
	$getName = mysqli_query($db,"select concat(First_Name,' ',Last_Name,' ',MI) as name from employee_details where employee_id=$employeeid");
	$getNameRow = mysqli_fetch_array($getName);
	$Name = $getNameRow['name'];
	$getAttendance= mysqli_query($db,"SELECT `month`,`from`,`to`,
working_days,absent,present,
late,late_lop_days,absent_per,
present_per,late_per
 FROM `pms_summary`

 where employee_id=$employeeid;");
	?>
	 
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
              <h3 class="box-title">Attendance for the Appraisal Year :  &nbsp; <h3 class="box-title" style="Color:#3c8dbc"><strong><?php echo '  '.$Name  ?></strong></h3></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-striped">
                <tr style="Color:White;">
                  <th>Month</th>
                  <th>From</th>
                  <th>To</th>
                  <th># Working Days</th>
                  <th># Days Absent</th>
                  <th># Days Present</th>
                  <th># Days Late</th>
                  <th>Absent %</th>
                  <th>Present %</th>
                  <th>Late %</th>
                 
                </tr>
			<?php
			if(mysqli_num_rows($getAttendance)>0)
			{				
			?>
				<?php
				while($getAttendanceRow = mysqli_fetch_assoc($getAttendance))
				{
				?>
                <tr>
                  <td ><?php echo $getAttendanceRow['month']; ?></td>
                  <td ><?php echo $getAttendanceRow['from']; ?></td>
                  <td ><?php echo $getAttendanceRow['to']; ?></td>
                  <td ><?php echo $getAttendanceRow['working_days']; ?></td>
                  <td ><?php echo $getAttendanceRow['absent']; ?></td>
                  <td ><?php echo $getAttendanceRow['present']; ?></td>
                  <td ><?php echo $getAttendanceRow['late']; ?></td>
                  <td ><span class="badge bg-yellow"><?php echo $getAttendanceRow['absent_per']; ?></span></td>
                  <td ><span class="badge bg-green"><?php echo $getAttendanceRow['present_per']; ?></span></td>
                  <td ><span class="badge bg-red"><?php echo $getAttendanceRow['late_per']; ?></span></td>
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

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	  <br>
	  <br>
	












      <!-- /.row -->

      <!-- Table row -->
	  <br>
	  <br>
      <div class="row">
        <div class="col-xs-12 table-responsive">


	  <br>
	  <br>

	<div class="modal fade" id="modal-default-create">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Services</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php
				include("config2.php");
				$getHighestEmpId = mysqli_query($db,"select employee_id from employee_details order by employee_id desc limit 1");
				$getHighestEmpIdRow = mysqli_fetch_array($getHighestEmpId);
				$HighestEmpId = $getHighestEmpIdRow['employee_id'];
				$SuggestedEmpId = $HighestEmpId+1;
		   ?>
            <form id="EmployeeForm" method="post" action="UpdateConfirmation.php">
        <div class="box-body">
          <div class="row">
		   <div class="col-md-6">
              <div class="form-group">
				<label>Employee ID</label>
			  <input type="text" class="form-control pull-right" name="EmployeeId" id="EmployeeId" readonly>
              </div>


            </div>
			<div class="col-md-6">
              <div class="form-group">
				<label>Name</label>
			  <input type="text" class="form-control pull-right" name="EmployeeName" id="EmployeeName" readonly>
              
              </div>


            </div>
			<br>
<div class="col-md-6">
              <div class="form-group">
				
				<br>
				<label>Effective From</label>
			  <input type="text" class="form-control pull-right" name="EffectiveFrom" id="datepicker1" required>
              
              </div>


            </div>
            <!-- /.col -
			
            <!-- /.col -->
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="CreateEmployee" value="Confirm Services" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>










		 







































		  <div class="modal fade" id="modal-default-Remove">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Extend Probation Period</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="RemoveForm" action="ExtendProbation.php" method="post">
        <div class="box-body">
          <div class="row">
		 <div class="col-md-6">
              <div class="form-group">
				<label>Employee ID</label>
			  <input type="text" class="form-control pull-right" name="EmployeeIdExtend" id="EmployeeIdExtend" readonly>
              </div>


            </div>
			<div class="col-md-6">
              <div class="form-group">
				<label>Name</label>
			  <input type="text" class="form-control pull-right" name="EmployeeNameExtend" id="EmployeeNameExtend" readonly>
              
              </div>


            </div>
			<br>
			<div class="col-md-6">
			<br>
              <div class="form-group">
				<label>Probation End Date</label>
			  <input type="text" class="form-control pull-right" name="ProbationDateExtend" id="ProbationDateExtend" readonly>
              
              </div>


            </div>
			<div class="col-md-6">
			
              <div class="form-group">
			  <br>
				<label>Additional Probationary Months</label>
			  <input type="number" class="form-control pull-right" name="MonthsExtended" placeholder="Enter Value in Month(s)" id="MonthsExtended" required="required" />
              
              </div>


            </div>
			<br>
		<div class="form-group">
		 <div class="col-sm-10">
		 	<br>
		  <label for="inputEmail3" class="col-sm-6 control-label">Reason for Extension</label>
				<textarea rows="4" cols="88" id="ReasonText" name="ReasonText">
				
				</textarea>
		 </div>
		</div>
            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SubmitData" value="Extend Probation" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>




























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
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
$('#EmployeeID').on('blur', function() {
	
	var EmpId = $('#EmployeeID').val();
    //ajax request
   $.ajax({
      url: 'employeeCheck.php',
      type: 'post',
      data: {
      	'email' : EmpId,
      },
      success: function(response){
      	if (response == 'taken' ) {
          email_state = false;
          $('#EmployeeID').parent().removeClass();
          $('#EmployeeID').parent().addClass("form_error");
          $('#EmployeeID').siblings("span").text('ID Unavailable.');
		  $('#CreateEmployee').prop('disabled', true);
		   return false;
      	}else if (response == 'not_taken') {
      	  email_state = true;
      	  $('#EmployeeID').parent().removeClass();
      	  $('#EmployeeID').parent().addClass("form_success");
      	  $('#EmployeeID').siblings("span").text('ID Available.');
		    $('#CreateEmployee').prop('disabled', false);
		 
      	}
      }
 	});
 });
</script>

<script type="text/javascript">

function changetextbox()
{
    if (document.getElementById("JoineeStatus").value === "Remove")
		{

		document.getElementById("datepicker1").disabled=true;
	}
	else
	{
		document.getElementById("datepicker1").disabled=false;
	}

}
</script>
<script type="text/javascript">

function changeType()
{
    if (document.getElementById("JobType").value === "Permanent")
		{

		document.getElementById("ProbationPeriod").disabled=false;
		document.getElementById("ContractPeriod").disabled=true;
	}
	else
	{
		document.getElementById("ContractPeriod").disabled=false;
		document.getElementById("ProbationPeriod").disabled=true;
	}

}
</script>
<script>

	$(function() {
  var bid, trid;
  $('#JoinEmp tr').click(function() {
       Id = $(this).find('.emplid').text();
       name = $(this).find('.emplName').text();
       date = $(this).find('.dateProb').text();
		$('#EmployeeId').val(Id);
		$('#EmployeeIdExtend').val(Id);
		$('#EmployeeName').val(name);
		$('#EmployeeNameExtend').val(name);
		$('#ProbationDateExtend').val(date);
		
  });
});
	</script>
<script>

	$(function() {
  var bid, trid;
  $('#IncompleteFormalities tr').click(function() {

       DocsCompleted = $(this).find(".DocsUploaded").text();
	   EmpFornalities = $(this).find(".DesnCompleted").text();
	   Provision = $(this).find(".ProvisionsCompleted").text();
	   if(DocsCompleted=='Y')
	   {
			
			$('#DocsCompletion').attr('class',"form-group has-success");
		
			$('#DocCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
		 
			$('#DocsCompletion').attr('class',"form-group has-error");
			$('#DocCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }

	  if(Provision=='Y')
	   {
		
				$('#ProvisionCompletion').attr('class',"form-group has-success");
				$('#ProvisionCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
		  
			$('#ProvisionCompletion').attr('class',"form-group has-error");
			$('#ProvisionCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }


	   if(EmpFornalities=='Y')
	   {

				$('#EmployeementCompletion').attr('class',"form-group has-success");
				$('#EmployeementCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
			$('#EmployeementCompletion').attr('class',"form-group has-error");
			$('#EmployeementCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }

  });
});
	</script>



	<script type="text/javascript">
   $(document).on('click','#CreateEmployee1',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#EmployeeForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "CreateEmployee.php",
         success: function(data){

		location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>

<!-- Page script -->
<script type="text/javascript">
//note also the proper type declaration in script tags, with NO SPACES (IMPORTANT!)

function addVal(){
   document.getElementById("AllocatedPer").stepUp(5);
}
function addValMod(){
   document.getElementById("ModifiedAllocatedPer").stepUp(5);
}
function SubVal(){
    document.getElementById("AllocatedPer").stepDown(5);
}
function SubValMod(){
    document.getElementById("ModifiedAllocatedPer").stepDown(5);
}
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
    $('#datepicker').datepicker({
      autoclose: true
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: 'd'
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
<script type="text/javascript">
   $(document).on('click','#ModifyTraining',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#trainingForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "InsertModifiedTraining.php",
         success: function(data){

		 window.location.href = "TrainingMgmt.php";
		   ajaxindicatorstop();

         }
});

});
    </script>
	<script>

	$(function() {
  var bid, trid;
  $('#ActProj tr').click(function() {
       trid = $(this).attr('id');
       trname = $(this).attr('name');

       rem = $('#remVal1').val();

	   maxval =parseInt(trid, 10) +parseInt(rem, 10);
		$('#ModifiedAllocatedPer').val(trid);
		$('#rowId').val(trname);
		$("#ModifiedAllocatedPer").attr({
		"max" : maxval
    });
		// table row ID
       //alert(trid);
  });
});
	</script>

	<script type="text/javascript">
	$('#ProjSelect').one('change', function() {

			$('#AddProj').prop('disabled', false);
	});

	</script>

	<script type="text/javascript">
   $(document).on('submit','#AddPro1j',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ProForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "InsertNewProj.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>



	<script type="text/javascript">
   $(document).on('click','#ModifyAlloc',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ModForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "ModProAlloc.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>






	<script type="text/javascript">
   $(document).on('click','#TestTraining',function(e) {

	ajaxindicatorstart("Please Wait..");
});
    </script>
	<script type="text/javascript">
    $(document).on('click','#editClick',function(e){
        $("#ResForm :input").prop("disabled", false);
    });
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
<script type="text/javascript">
       $(document).on('click','#UploadDoc',function(e) {
		//var data = $("#ResumeFileDoc").files;
		 e.preventDefault();
		ajaxindicatorstart("Please Wait..");
  $.ajax({
		type: 'POST',
		url: 'UploadLOA.php',
		data: new FormData('#roleForm'),
		contentType: false,
		cache: false,
		processData:false,
		url: "UploadLOA.php",
		 enctype: 'multipart/form-data',
         success: function(data){
			alert(data);
			 window.location.href = "ViewResource.php?id="+id;
			  ajaxindicatorstop();
         }
});
 });
    </script>

<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
