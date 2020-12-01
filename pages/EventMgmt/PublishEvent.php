<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$fromHome = $_SESSION['fromEventsHome'];
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,date_format(date_from,'%D %M, %Y') as date_formatted,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,if(event_location='','our Office premises',event_location) as event_location,event_desc,event_category,event_website,event_logo,event_rsvp_subject,date_format(reg_last_date,'%D %M, %Y') as RegLastDate,is_registration_required FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);

$allCategories = mysqli_query($db,"SELECT category_Desc FROM `event_category` where category_Desc not in ('".$getEventDetailsRow['event_category']."')");

if($getEventDetailsRow['is_registration_required']=='Y')
{

$EventContent = "We are very Pleased to inform you that ".$getEventDetailsRow['event_title']." has been Scheduled on ".$getEventDetailsRow['date_formatted']." at ".$getEventDetailsRow['event_location'].". Your Presence is very Important to us. Please RSVP, to confirm your Attendance by registering using the Link Below. Last Date for Registration would be ".$getEventDetailsRow['RegLastDate']."";

}
else
{
	$EventContent = "We are very Pleased to inform you that ".$getEventDetailsRow['event_title']." has been Scheduled on ".$getEventDetailsRow['date_formatted']." at ".$getEventDetailsRow['event_location'].". Your Presence is very Important to us. ";
}
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
          <h3 class="box-title"><strong><?php echo $getEventDetailsRow['event_title']; ?> </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
		 <div class="col-xs-12">
		  <div class="box-header">
              <h3 class="box-title"><strong>Publish Event</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="RSVPForm" method="post" enctype="multipart/form-data" action="SendRSVP.php" onsubmit="return CheckforBal();">
        <div class="box-body">
          <div class="row">
			
			
			<div class="col-md-12">
			<input type="hidden" id="EventID" name="EventID" value="<?php echo $EventID ?>" />
				<div class="form-group">
					<label>Draft Your Invitiation E-Mail for <strong><?php echo ' '.$getEventDetailsRow['event_title'] ?></strong><span class="astrick">*</span></label>
						<textarea name="AboutInfo" value="<?php if($getEventDetailsRow['event_rsvp_subject']!='') { echo $getEventDetailsRow['event_rsvp_subject'];  } else { echo $EventContent; } ?>" style="resize: none;width: 100%;" id="reason" rows="7" cols="183.5" maxlength="1500" class="is-maxlength" required="required" required><?php if($getEventDetailsRow['event_rsvp_subject']!='') { echo $getEventDetailsRow['event_rsvp_subject'];  } else { echo $EventContent; } ?></textarea>
				</div>
			</div>	
			<br>
			<div class="col-md-6">
			<?php
		if($fromHome=='Y')
		{
		?>
		 <input type="button" onclick="window.location='EventInfo.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-left" value="Back" id="Back"  />
		<?php
		}
		?>
			</div>	
		<div class="col-md-6">
		<?php
		if($fromHome!='Y')
		{
		?>
				  <input type="submit" value="Publish Event" id="PublishEvent" class="btn btn-success pull-right"></input> 
		<?php
		}
		else
		{
		?>
		 <input type="submit" value="Save" id="PublishEvent" class="btn btn-success pull-right"></input> 
		<?php
		}
		?>
			</div>			
           
			
          
          </div>
		  <br>
		
</div>
</form>
<br>

              <!-- /.form-group -->
            </div>

            <!-- /.col -

            <!-- /.col -->
          </div>
		  <br>
	
 
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
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
	<script>
function CheckforBal()
{
	var returnvalue = true;
	
			if(returnvalue == true)
			{
				ajaxindicatorstart("Sending Invite..");
				return true;
			}
			
			else
	{
			return false;
	}
}
	</script>
	<script>
	
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
</body>
</html>
