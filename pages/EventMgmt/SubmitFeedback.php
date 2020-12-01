<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$userid = $_SESSION['login_user'];
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getFamily=mysqli_query($db,"select name,age,employee_in_relation,gender from event_invitation_Acceptors_family where employee_in_relation='$userid' and event_id='$EventID' and is_active='Y'");
$isFamEligible = mysqli_query($db,"select * from event_invitees where employee_id='$userid' and event_id='$EventID' and is_family_included='Y'");
$isMemEligible = mysqli_query($db,"select * from event_invitation_Acceptors where employee_id='$userid' and event_id='$EventID' and is_memento_eligible='Y'");
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
              <h3 class="box-title"><strong>Submit Feedback</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="NewEventForm" method="post" enctype="multipart/form-data" action="SaveFeedBack.php" onsubmit="return CheckforBal();">
        <div class="box-body">
          <div class="row">
		   <input type="hidden" name="EventID" value="<?php echo $EventID ?>" />
         
					<div class="col-md-6">
						<div class="form-group">
			      <label>
				&nbsp;&nbsp;&nbsp;&nbsp; Did you attend this Event ? &nbsp; <label>
                  <input type="radio" name="r1" value="Y" class="minimal" checked> &nbsp; Yes
                </label>
                <label>&nbsp;
                  <input type="radio" name="r1" value="N" class="minimal" > &nbsp; No
                </label>
                </label>
                
              </div>
					</div>
					<?php 
					
					if(mysqli_num_rows($isFamEligible)>0)
					{
					?>
					<div class="col-md-6">
					<div class="form-group">
			      <label>
				&nbsp;&nbsp;&nbsp;&nbsp; Did your Guests Accompany you ? &nbsp; <label>
                  <input type="radio" name="r2" value="Y" class="minimal" checked> &nbsp; Yes
                </label>
                <label>&nbsp;
                  <input type="radio" name="r2" value="N" class="minimal"> &nbsp; No
                </label>
                </label>
                
              </div>
					</div>

<?php
}
?>				
<?php 
					
					if(mysqli_num_rows($isMemEligible)>0)
					{
					?>	
		<div class="col-md-6">
					<div class="form-group">
			      <label>
				&nbsp;&nbsp;&nbsp;&nbsp; Did your Receive your Corporate Gift ? &nbsp; <label>
                  <input type="radio" name="r3" value="Y" class="minimal" checked> &nbsp; Yes
                </label>
                <label>&nbsp;
                  <input type="radio" name="r3" value="N" class="minimal" > &nbsp; No
                </label>
                </label>
                
              </div>
					</div>						
					
					<?php
					}
					?>
			<br>
			<div class="col-md-12">
				<div class="form-group">
					<label>Please fill in your Comments <strong></strong></label>
						<textarea name="FeedBackInfo" style="resize: none;width: 100%" id="reason" rows="7" cols="183" maxlength="1500" class="is-maxlength"  ></textarea>
				</div>
            <label>P.S : Your inputs will be anonymous. <strong></strong></label>
			</div>			  
          </div>
		  <br>
		
</div>
<div class="box-footer">
              <input type="submit" value="Submit" id="EventParticularsSave" class="btn btn-success pull-right"></input> 
              </div>
				
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
	var from = document.getElementById("datepicker").value;
	var to = document.getElementById("datepicker1").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		returnvalue = false;
	}
	if(returnvalue == true)
	{
			if(returnvalue == true)
			{
				ajaxindicatorstart("Saving Particulars..");
			}
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
