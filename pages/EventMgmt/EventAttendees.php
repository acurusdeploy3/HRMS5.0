<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$_SESSION['eventid']=$EventID;
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getAllBusinessUnits = mysqli_query($db,"SELECT business_unit FROM `all_business_units` ");
$getAllDepartment = mysqli_query($db,"SELECT department FROM `all_departments` ");
$getAllEmployees = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI,' : ',employee_id) as Name FROM `employee_Details` where is_active='Y'");
$getEventAttendees = mysqli_query($db,"select a.employee_id,concat(b.first_name,' ',b.last_name,' ',mi) as Name,business_unit,department from
									event_invitation_acceptors a inner join employee_details b on a.employee_id=b.employee_id
									where event_id='$EventID' and a.is_active='Y'");


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Event Management</title>
 <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
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

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
.astrick
{
	color:red;
}
 
th {
	  background-color: lightgray;
	}
       
</style>
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Event Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.html"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="EventsInfo.php">Active Events</a></li>
        <li class="active">New Event</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><strong><?php echo $getEventDetailsRow['event_title'].' : Event Attendee(s)'; ?> </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
				 <div class="row">
		 <div class="col-xs-12">
		  <div class="box-header">
  
		    <br>
              <div class="box-tools">
              </div>
			  <br>
            </div>
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th style="display:none;"> Emp ID</th>
                  <th>Employee ID</th>
				  <th>Name</th>
				  <th>Department</th>
				  <th>Business Unit</th>
				  <th>Guest Count Excluding Employee</th> 
                </tr>
                </thead>
				
                <tbody>
				<?php
				if(mysqli_num_rows($getEventAttendees) > 0){
				while ($getEventAttendeesRow = mysqli_fetch_assoc($getEventAttendees))
					{
				?>
                <tr>
                  <td style="display:none;"><input type="hidden" id="EmpID" class="EmpID" value="<?php echo $getEventAttendeesRow['employee_id']; ?>" /></td>
                  <td><?php echo $getEventAttendeesRow['employee_id'];  ?></td>
                  <td><?php echo $getEventAttendeesRow['Name'];  ?></td>
                  <td><?php echo $getEventAttendeesRow['department'];  ?></td>
                  <td><?php echo $getEventAttendeesRow['business_unit'];  ?></td>
                  <td><a href="#" data-toggle="modal" data-target="#myModal" class="ViewFamily"><?php  
					$getFamCount = mysqli_query($db,"select * from event_invitation_Acceptors_family where employee_in_relation='".$getEventAttendeesRow['employee_id']."' and event_id='$EventID'"); 
					echo mysqli_num_rows($getFamCount).' (Click to View)';
					?></a></td>
                </tr>
                <?php
					}
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
                </tbody>
              </table>
			    </div>
			    </div>
				
				<div class="box-footer">
			      
			  
               <button type="button" onclick="window.location='EventInfo.php?id=<?php echo $EventID  ?>'" name="Submit" class="btn btn-primary pull-right" id="savefields">Back Home</button>     
              </div>
				
			    </div>
			  
			  
			  
			  
			  
			   <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:aliceblue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Event Attendee(s)</strong></h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
          <form id="NewInviteesForm" method="post" enctype="multipart/form-data" action="InsertInvitees.php" onsubmit="return CheckforBal();">
         <div class="row">
		 <div class="col-xs-12">
		  <div class="box-header">
          
        <div class="box-body">
          <div class="row" id="FamTab">
          </div>
		  <br>	
</div>
           <div class="col-md-6">   
			</div>  
		   <div class="col-md-6">   
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
              </div>
			  </form>
           
            <!-- /.modal-content -->
          </div>
          </div>
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
         
		  <br>
	
 
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
     
      <!-- /.box --
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

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
<script>

	$(function() {
  $('.ViewFamily').click(function() {
	  var row = $(this).closest("tr");   
		var EmpId = row.find(".EmpID").val(); 
	 
		$('.modal-body').load('FamMembers.php?EmpID='+EmpId,function(){
        $('#myModal').modal({show:true});
    });
  });
});
</script>
<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("AllEmp").value === "Yes") {
		document.getElementById("SelByDepartment").disabled=true;
		document.getElementById("SelByBU").disabled=true;
		document.getElementById("AdditionalEmp").disabled=true;
	}
	else
	{
		document.getElementById("SelByDepartment").disabled=false;
		document.getElementById("SelByBU").disabled=false;
		document.getElementById("AdditionalEmp").disabled=false;
	}
	
}
</script>
	<script>
function CheckforBal()
{
	var returnvalue = true;

			if(returnvalue == true)
			{
				ajaxindicatorstart("Saving Audience Data..");
			}
			return true;
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
      autoclose: true,
	  startDate: '+d'
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: '+d'
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
</body>
</html>
\