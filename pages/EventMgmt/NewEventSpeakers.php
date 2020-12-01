<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$_SESSION['eventid']=$EventID;
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getSpeakers = mysqli_query($db,"select id,name,`desc`,email,contact_info from event_External_invitees where event_id='$EventID' and iS_active='Y'");
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
          <h3 class="box-title"><strong><?php echo $getEventDetailsRow['event_title']; ?>  </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
		 <div class="col-xs-12">
		  <div class="box-header">
              <h3 class="box-title">
           <br>
			  <strong>Key Note Speakers</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="NewSpeakerForm" method="post" enctype="multipart/form-data" action="InsertSpeaker.php" onsubmit="return CheckforBal();">
        <div class="box-body">
          <div class="row">
			
			 <input type="hidden" name="EventID" value="<?php echo $EventID ?>" />
					<div class="col-md-6">
						<div class="form-group">
							<label>Name<span class="astrick">*</span></label>
								<input type="text" name="SpeakerName" id="SpeakerName" class="form-control" placeholder="Enter Name" required>
						</div>
						<div class="form-group">
							<label>Gender<span class="astrick">*</span></label>
							  <select class="form-control select2" id="Gender" name="Gender" style="width: 100%;" required="required">
							  <option value="" selected disabled>Please Select from Below</option>
							  <option value="Male" >Male</option>
							  <option value="Female">Female</option>
							  <option value="Others">Others</option>
							</select>
						</div>
						<div class="form-group">
							<label>Contact</label>
								<input type="text" pattern="[6789][0-9]{9}" name="SpeakerContact" onkeypress="return isNumberKey(event)" maxlength="10" id="SpeakerContact" class="form-control" placeholder="Enter Contact Info.">
						</div>
						<div class="form-group">
							<label>Photograph</label>
								<input type="file" id="ResumeFileDoc" oninput="this.className = ''" style="height: 35px;" name="ResumeFileDoc" accept="image/x-png,image/gif,image/jpeg">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Is External Speaker<span class="astrick">*</span></label>
							  <select class="form-control select2" id="SpeakerType" name="SpeakerType" style="width: 100%;" required="required">
							  <option value="" selected disabled>Please Select from Below</option>
							  <option value="Yes" >Yes</option>
							  <option value="No">No</option>
							</select>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" name="SpeakerEmail" id="SpeakerEmail" class="form-control" placeholder="Enter Email">
						</div>
						<div class="form-group">
							<label>About the Speaker<span class="astrick">*</span></label>
								<textarea name="AboutSpeaker" value="<?php echo $getEventDetailsRow['event_desc'] ?>" style="resize: none;width: 100%;" id="AboutSpeaker" rows="5" cols="87" maxlength="1500" class="is-maxlength" required="required" required></textarea>
						</div>
				<input type="submit" value="Save" id="EventParticularsSave" class="btn btn-success pull-right"></input> 
				
					</div>			
          
          </div>
		  <br>
		
</div>
</form>
<br>

			 <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Name of the Speaker</th>
                  <th>Contact</th>
                  <th>Email</th>
                  <th>About the Speaker</th>
                  <th>Delete</th>
                </tr>
				<?php
				if(mysqli_num_rows($getSpeakers)>0)
				{
					$i=1;
					while($getSpeakersRow = mysqli_fetch_assoc($getSpeakers))
					{
				?>
                <tr>
                  <td><?php echo $getSpeakersRow['name'];  ?></td>
                  <td><?php echo $getSpeakersRow['contact_info'];  ?></td>
                  <td><?php echo $getSpeakersRow['email'];  ?></td>
                  <td><?php echo $getSpeakersRow['desc'];  ?></td>
                <td><a href="DeleteSpeaker.php?id=<?php echo $getSpeakersRow['id']; ?> " class="DelSpeaker"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Delete Speaker" width='18px' height='18px' /></a></td>
                </tr>
                <?php
					$i++;
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
              </table>

            <!-- /.box-body -->
          </div>
<br>
<br>
           <div class="col-md-6">   
			</div>  
		   <div class="col-md-6">   
			</div>   
            </div>

            <!-- /.col -

            <!-- /.col -->
          </div>
		  <br>
	
 
          <!-- /.row -->
        </div>
		<?php
		if($_SESSION['fromEventsHome']!='Y')
						{
		?>
		<div class="box-footer">
			      
			   <input type="button" onclick="window.location='NewEventDesc.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-left" value="Back" id="Back"  />
               <button type="button" name="Submit" onclick="window.location='NewEventInvitees.php?id=<?php echo $EventID  ?>'" class="btn btn-success pull-right" id="savefields">Save and Continue</button>     
			
			
              </div>
			  
			  <?php
						}
						else
						{
			  ?>
			  <div class="box-footer">
			      
               <button type="button" name="Submit" onclick="window.location='EventInfo.php?id=<?php echo $EventID  ?>'" class="btn btn-success pull-right" id="savefields">Finish</button>     
			
			
              </div>
			  
			  <?php
						}
			  ?>
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
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
	<script>
function CheckforBal()
{
	var returnvalue = true;

			if(returnvalue == true)
			{
				ajaxindicatorstart("Saving Data..");
			}
			return true;
}
	</script>
<script type="text/javascript">
   $(document).on('click','.DelSpeaker',function(e) {

	ajaxindicatorstart("Deleting..");
});
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
</body>
</html>
