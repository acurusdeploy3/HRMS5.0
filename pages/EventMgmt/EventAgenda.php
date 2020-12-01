<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$_SESSION['eventid']=$EventID;
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getBetweenDates = mysqli_query($db,"
select * from
(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
where selected_date between '".$getEventDetailsRow['date_from']."' and '".$getEventDetailsRow['date_to']."'");
$getSpeakers = mysqli_query($db,"select id,date(session_Date) as date_of_session,date_format(session_Date,'%h:%i %p') as session_time,
timestampdiff(minute,session_date,session_to_date) as duration,activity from
event_agenda where event_id='$EventID' and iS_active='Y' order by session_Date asc;");
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
			  <strong>Agenda for the Event</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="NewSpeakerForm" method="post" enctype="multipart/form-data" action="" >
        <div class="box-body">
          <div class="row">
			
			 <input type="hidden" id="EventID" name="EventID" value="<?php echo $EventID ?>" />
					<div class="col-md-6">
							<div class="form-group">
								<label>Date<span class="astrick">*</span></label>
								<select class="form-control select2" id="DateofSession" name="DateofSession" style="width: 100%;" required="required">
								<option value="" selected disabled>Please Select a Date Below</option>
								<?php
									while($getBetweenDatesRow = mysqli_fetch_assoc($getBetweenDates))
									{
									?>
										<option value="<?php echo $getBetweenDatesRow['selected_date'] ?>" ><?php echo $getBetweenDatesRow['selected_date'] ?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-6">
							<div class="form-group">
								<label>Time<span class="astrick">*</span></label>
							  <div class="input-group">
								<input type="text" id="SessionTime" name="SessionTime" class="form-control timepicker" required>
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								</div>
								</div>
							</div>
							
							<div class="col-md-6">
							<div class="form-group">
								<label>Session Duration (in Minutes)<span class="astrick">*</span></label>
								<input type="number" min="15" step="15" id="NumberMinutes" onkeypress="return isNumberKey(event)" name="NumberMinutes" placeholder="Enter # Minutes" class="form-control" required />
								</div>
							</div>

					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Activity<span class="astrick">*</span></label>
								<textarea name="ActivityText" value="<?php echo $getEventDetailsRow['event_desc'] ?>" style="resize: none;width: 100%;" id="AboutSpeaker" rows="5" cols="88" maxlength="1500" class="is-maxlength" required="required" required ></textarea>
						</div>
				<input type="submit" value="Add to Agenda" id="EventAgendaSave" class="btn btn-success pull-right"></input> 
				
					</div>			
          
          </div>
		  <br>
		
</div>
</form>
<br>

			 <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Duration</th>
                  <th>Activity</th>
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
                  <td><?php echo $getSpeakersRow['date_of_session'];  ?></td>
                  <td><?php echo $getSpeakersRow['session_time'];  ?></td>
                  <td><?php if(($getSpeakersRow['duration']/60)>=1) { echo ($getSpeakersRow['duration']/60).' Hour(s)'; } else { echo $getSpeakersRow['duration'].' Minutes'; } ?></td>
                  <td><?php echo $getSpeakersRow['activity'];  ?></td>
                <td><a href="DeleteAgenda.php?id=<?php echo $getSpeakersRow['id']; ?> " onclick="return confirm('Sure to Remove Off from Schedule?')" class="DelAgen"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Delete Speaker" width='18px' height='18px' /></a></td>
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
			      
			   <input type="button" onclick="window.location='EventReq.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-left" value="Back" id="Back"  />
               <button type="button" name="Submit" onclick="window.location='PublishEvent.php?id=<?php echo $EventID  ?>'" class="btn btn-primary pull-right" id="savefields">Next</button>     
			
			
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
<script language="Javascript">
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </script>
<script>
	var number = document.getElementById('NumberMinutes');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
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
<script type="text/javascript">
$(document).ready(function() {
    $("#NewSpeakerForm").submit(function(e) {
	var returnval = true;
	e.preventDefault();
	ajaxindicatorstart("Adding to Schedule..");
	var DateofSession = $('#DateofSession').val();
	var SessionTime = $('#SessionTime').val();
	var EventID = $('#EventID').val();
	var NumberMinutes = $('#NumberMinutes').val();
   $.ajax({
      url: 'AgendaCheck.php',
      type: 'post',
      data: {
      	'DateofSession' : DateofSession,
		'SessionTime' : SessionTime,
      	'EventID' : EventID,
      	'NumberMinutes' : NumberMinutes,
      },
      success: function(response)
	  {
      	if (response == 'taken')
		{
			alert("Date & Time Taken up Already! Please Try different Dates!");
			ajaxindicatorstop();
			return false;
      	}
		else 
		{
			SubmitSchedule();
      	}
      }
 	});	
});
});
</script>
<script type="text/javascript">
  function SubmitSchedule() {

	ajaxindicatorstart("Please Wait..");
	
  var data = $("#NewSpeakerForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "InsertAgenda.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

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
