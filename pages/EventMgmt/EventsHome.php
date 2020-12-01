<?php
include("config.php");
$_SESSION['fromEventsHome']='N';
$userid = $_SESSION['login_user'];
$userRole = $_SESSION['login_user_group'];
$getEventDetails = mysqli_query($db,"SELECT event_id,date_from as date_with_time,date(date_from) as date_from,date(date_to) as date_to,date_format(date_from,'%D %M, %Y') as date_formatted,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo FROM `active_events` order by created_date_and_time desc limit 5");
$Createccesscontrol = mysqli_query($db,"select * from user_access_control where main_menu='Event Management'
						and sub_menu='Editing' and accessed_to='$userRole'");
if(mysqli_num_rows($Createccesscontrol)>0)
{
	$CreateEligible='Y';
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Events & Happenings</title>
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
      Events & Happenings 	
	  <?php
	  if($CreateEligible=='Y')
	  {
	  ?>
<button data-toggle="modal" data-target="#myModal" type="button" class="btn btn-info pull-right"><i class="fa fa-fw fa-plus"></i> &nbsp;Create New Event</button>
	  <?php  
	  }
	  ?>
	  </h1>
     
    </section>
	<section class="content">
		<div class="row">
		 <?php
		 if(mysqli_num_rows($getEventDetails)>0)
		 {
			 while($getEventsRow = mysqli_fetch_assoc($getEventDetails))
			 {
		 ?>
		   <div class="col-md-6">
				<div class="box box-primary">
            <form role="form">
              <div class="box-body" style="background-color: gainsboro;">
			    <div class="col-md-6">
						<div class="form-group">
							<img alt='User' src='<?php if($getEventsRow['event_logo']==' ') { echo '../../uploads/NotAvail.jpg'; } else { echo '../../uploads/'.$getEventsRow['event_logo']; } ?>' style="width: 250px;height: 200px;" />
						</div>
			    </div>
			    <div class="col-md-6">
						<div class="form-group">
							<h3 class="box-title"><strong><?php  echo $getEventsRow['event_title'] ?></strong>&nbsp; <?php if($getEventsRow['date_from']>= date('Y-m-d') ) { ?><span class="badge bg-green">Live</span>  <?php  } else {?><span class="badge bg-blue">Completed</span><?php } ?> </h3>
						</div>
						<div class="form-group">
							<h5 class="box-title"><i class="fa fa-fw fa-calendar"></i>&nbsp;<?php  echo $getEventsRow['date_formatted'] ?></h5>
						</div>
						<div class="form-group">
							<h5 class="box-title"><i class="fa fa-fw fa-map-marker"></i>&nbsp;<?php if($getEventsRow['event_location']!='')  { echo $getEventsRow['event_location']; } else { echo 'Acurus Solutions'; } ?></h5>
						</div>
						<?php
							$isAttending = mysqli_query($db,"select * from event_invitation_acceptors where event_id='".$getEventsRow['event_id']."' and employee_id='$userid'");
							if(mysqli_num_rows($isAttending)>0)
							{
						?>
						<div class="form-group">
							<h5 class="box-title" style="color:green;">Registered Successfully!</h5>
						</div>
						
						<?php
							}
						?>
						<div class="form-group">
							<button type="button"  onClick="document.location.href='EventInfo.php?id=<?php echo $getEventsRow['event_id'] ?>'" class="btn btn-block btn-primary btn-sm pull-right" style="
    width: 50%;
">View Details</button>
						</div>
                </div>
            </form>
          </div>
		   </div>
	  
		</div>
		
		<?php
			 }
		 }
		 else
		 {
		?>
		 <div class="col-md-12" style="background-color: #f7f7f7;">
		 <div class="form-group">
				<img alt='User' src='Images/events-empty-data-set_1x.png' style="/* width:100%; *//* height: 100%; */display: block;margin-left: auto;margin-right: auto;width: 50%;" />
				</div>
		</div>
		<?php
		 }
		?>
		 <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:aliceblue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Create Event</strong></h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="NewEventForm" method="post">
        <div class="box-body">
          <div class="row">
	  <div class="col-md-12">
	   <div class="form-group">
                 <label>Title</label>
                <input type="text" name="TitleText" id="TitleText" class="form-control" placeholder="Enter Event Title" required="required">
              </div>
		</div>
		  <div class="col-md-6">
			  <div class="form-group">
                <label>Start Date</label>

             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="StartDate" class="form-control pull-right" id="datepicker" required>
                </div>
              </div>
			 <div class="form-group">
                <label>End Date</label>

             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="endDate" class="form-control pull-right" id="datepicker1" required>
                </div>
              </div>
            </div>
            <div class="col-md-6">
			 <div class="form-group">
                <label>Start Time</label>

              <div class="input-group">
                    <input type="text" id="startTime" name="startTime" class="form-control timepicker" required="required">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
              </div>
			   <div class="form-group">
                <label>End Time</label>

              <div class="input-group">
                    <input type="text" id="endTime" name="endTime" class="form-control timepicker" required="required">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
              </div>

            </div>
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="CreateEvent" value="Create Event" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		
    </section>
    <!-- Main content -->
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
<script type="text/javascript">
$(document).ready(function() {
    $("#NewEventForm").submit(function(e) {
		
		e.preventDefault();
		var x = CheckforBal();
		if(x==true)
				{
					SubmitRequest();
				}
				else
				{
					ajaxindicatorstop();
				}
		});
});
    </script>
	<script>
function CheckforBal()
{
	var returnvalue = true;
	var from = document.getElementById("datepicker").value;
	var to = document.getElementById("datepicker1").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		returnvalue = false;
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
	<script>
	function SubmitRequest()
	{
	ajaxindicatorstart("Creating Event..");
	var data = $("#NewEventForm").serialize();
	
	$.ajax({
         data: data,
         type: "post",
         url: "CreateNewEvent.php",
         success: function(data)
		 {
			window.location = "NewEventDesc.php?id="+data;
			ajaxindicatorstop();
         }
});
	}
	</script>

<script type="text/javascript">
   $(document).on('click','.btn btn-block btn-primary btn-sm pull-right',function(e) {

	ajaxindicatorstart("Loading Event..");
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
