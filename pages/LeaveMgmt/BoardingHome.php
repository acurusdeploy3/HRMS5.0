<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp == 'HR' || $usergrp == 'HR Manager') 
{
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Employee Boarding</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
       Leave Request

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Leave Request</li>
      </ol>
    </section>
	<?php
	include("config2.php");
	$getAppIds= mysqli_query($db,"select group_concat(applicant_id) as applicant_id from employee_details");
	$getAppIdRow = mysqli_fetch_array($getAppIds);
	$AppIds = $getAppIdRow['applicant_id'];
	?>
	 <?php
			  include("config.php");
			  include("ModificationFunc.php");

			  $getJoinees = mysqli_query($db1,"select a.applicant_id,b.first_name,b.last_name,b.mi,a.position_applied,date_format(a.date_of_joining,'%d - %b - %Y') as  date_of_joining,a.position_id from applicant_tracker a
							left join applicant_Details b on a.applicant_id=b.applicant_id
							where status='Selected' and date_of_joining<=curdate() and date_of_joining!='0001-01-01 00:00:00' and a.applicant_id not in ($AppIds) order by date_of_joining desc");
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
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Expected Joinee(s) Till Today : <?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y'); ?></strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;">
                  <th>Applicant ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>MI</th>
                  <th>Expected DOJ</th>
				   <th>Position MRF ID</th>
                  <th>Position Applied</th>
                 
                  <th align="right">Create Employee</th>
                  <th align="right">Did Not Report</th>
                </tr>
				<?php
				while($getAffectedRows = mysql_fetch_assoc($getJoinees))
				{
				?>
                <tr>
                  <td class="AppId"><?php echo $getAffectedRows['applicant_id']; ?></td>
                  <td class="AppFirstName"><?php echo $getAffectedRows['first_name']; ?></td>
                  <td  class="AppLastName"><?php  if($getAffectedRows['last_name']!='') { echo $getAffectedRows['last_name']; } else { echo '--'; } ?></td>
                  <td class="AppMIName"><?php if($getAffectedRows['mi']!='') { echo $getAffectedRows['mi']; } else { echo '--'; } ?></td>
                  <td class="DateofJoining"><?php echo $getAffectedRows['date_of_joining']; ?></td>
                  <td class="PositionMRF"><?php echo $getAffectedRows['position_id']; ?></td>
                  <td class="PosApplied"> <?php echo $getAffectedRows['position_applied']; ?></td>
                  <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-create"><img alt='User' src='Images/newuser.png' title="Create AHRMS User" width='18px' height='18px' /></a></td>
                   <td><a href="#" id="NoReported" data-toggle="modal" data-target="#modal-default-Remove"><img alt='User' src='Images/notrep.png' title="Modify DOJ / Remove" width='18px' height='18px' /></a></td>
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
<script>
function ClearData() {
  document.getElementById("EmployeeForm").reset();
}
</script>
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
       Id = $(this).find('.AppId').text();
       FirstName = $(this).find(".AppFirstName").text();
	   LastName = $(this).find(".AppLastName").text();
	   MiddleName = $(this).find(".AppMIName").text();
	   PosAPplied = $(this).find(".PosApplied").text();
	   PosMRF = $(this).find(".PositionMRF").text();
		$('#AppID').val(Id);
		$('#AppIDRemove').val(Id);
		$('#FirsName').val(FirstName);
		$('#LastName').val(LastName);
		$('#MI').val(MiddleName);
		$('#MRF').val(PosMRF);
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
<script>

</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#EmployeeForm").submit(function(e) {

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
	<script type="text/javascript">
   $(document).on('click','#closeRole',function(e) {
		 location.reload();	
});
    </script>
	<script type="text/javascript">
   $(document).on('click','#closeRole1',function(e) {
		 location.reload();	
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
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>