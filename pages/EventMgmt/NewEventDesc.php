<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,event_category,event_website,event_logo,
									if(reg_last_date='0001-01-01','',reg_last_date) as reg_last_date,is_registration_required,location_type FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);

$allCategories = mysqli_query($db,"SELECT category_Desc FROM `event_category` where category_Desc not in ('".$getEventDetailsRow['event_category']."')");
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
              <h3 class="box-title"><strong>Event Particulars</strong></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="NewEventForm" method="post" enctype="multipart/form-data" action="UpdateEventDesc.php" onsubmit="return CheckforBal();">
        <div class="box-body">
          <div class="row">
			<div class="col-md-6">
			 <input type="hidden" name="EventID" value="<?php echo $EventID ?>" />
					<div class="col-md-6">
						<div class="form-group">
							<label>Date From<span class="astrick">*</span></label>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" value="<?php echo $getEventDetailsRow['date_from'] ?>" name="StartDate" class="form-control pull-right" id="datepicker" required>
								</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Time<span class="astrick">*</span></label>
							  <div class="input-group">
								<input type="text" id="startTime" value="<?php echo $getEventDetailsRow['from_time'] ?>" name="startTime" class="form-control timepicker" required>
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								</div>
						</div>
					</div>			
            </div>
           <div class="col-md-6">
					<div class="col-md-6">
						<div class="form-group">
							<label>Date To<span class="astrick">*</span></label>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" value="<?php echo $getEventDetailsRow['date_to'] ?>"  name="endDate" class="form-control pull-right" id="datepicker1" required>
								</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Time<span class="astrick">*</span></label>
							  <div class="input-group">
								<input type="text" id="endTime" value="<?php echo $getEventDetailsRow['to_time'] ?>" name="endTime" class="form-control timepicker" required>
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								</div>
						</div>
					</div>			
            </div>
			<br>
			<div class="col-md-12">
				<div class="form-group">
					<label>About <strong><?php echo ' '.$getEventDetailsRow['event_title'] ?></strong><span class="astrick">*</span></label>
						<textarea name="AboutInfo"  style="resize: none;width: 100%;" id="reason" rows="7" cols="183" maxlength="1500" class="is-maxlength" required="required" value="<?php echo $getEventDetailsRow['event_desc'] ?>" required><?php echo $getEventDetailsRow['event_desc'] ?></textarea>
				</div>
			</div>
		<div class="col-md-6">
				<div class="col-md-6">
						<div class="form-group">
							<label>Event Location<span class="astrick">*</span></label>
							<select class="form-control select2" onchange="changetextbox();" id="EventLoc" name="EventLoc" required="required" style="width: 100%;" required>
								<?php 
									if($getEventDetailsRow['location_type']=='External Location')
									{
									?>
										<option value="Internal Location">Internal Location</option>
										<option value="External Location" selected>External Location</option>
									<?php
									}
									else
									{
									?>
										<option value="Internal Location" selected>Internal Location</option>
										<option value="External Location" >External Location</option>
									<?php
									}
									?>
								</select>
						</div>
				</div>
				<div class="col-md-6">
						<div class="form-group">
							<label>Venue of Event<span class="astrick">*</span></label>
							<input type="text" name="EventVenue" value="<?php if($getEventDetailsRow['location_type']=='External Location') { echo $getEventDetailsRow['event_location']; }?>" id="EventVenue" class="form-control" placeholder="Type in Address of Venue (With Pin / Zip)" required  <?php if($getEventDetailsRow['location_type']!='External Location') {?> disabled  <?php  } ?>>
						</div>
				</div>
						<div class="form-group">
							<label>Event Media Link (Website /  Social Pages)</label>
								<input type="text" class="form-control" placeholder="Give a Link" id="EventWeb" name="EventWeb" value="<?php echo $getEventDetailsRow['event_website'] ?>" />
						</div>
						
		</div>	
			
		<div class="col-md-6">
				<div class="col-md-6">
						<div class="form-group">
							<label>Registration Required?<span class="astrick">*</span></label>
								<select class="form-control select2" onchange="changetextbox1();" id="IsRegRequired" name="IsRegRequired" required="required" style="width: 100%;" required>									
									<?php 
									if($getEventDetailsRow['is_registration_required']=='Y')
									{
									?>
									<option value="Y" selected>Yes</option>
									<option value="N">No</option>
									
									<?php
									}
									else
									{
									?>
									<option value="Y" >Yes</option>
									<option value="N" selected>No</option>
									<?php
									}
									?>
								</select>
						</div>
				</div>
				<div class="col-md-6">
						<div class="form-group">
							<label>Last Date for Registration<span class="astrick">*</span></label>
							<input type="text" name="RegLastDate" value="<?php if($getEventDetailsRow['is_registration_required']=='Y') { echo $getEventDetailsRow['reg_last_date']; } ?>" id="datepicker2" class="form-control" placeholder="Last Date for Registration." required <?php if($getEventDetailsRow['is_registration_required']=='N') {  ?>disabled <?php  } ?>>
						</div>
				</div>
				
			</div>
			<div class="col-md-6">
				<div class="col-md-6">
						<div class="form-group">
							<label>Upload Event Poster / Banner</label>
								<input type="file" id="ResumeFileDoc" value="<?php echo $getEventDetailsRow['event_logo'] ?>" oninput="this.className = ''" style="height: 35px;" name="ResumeFileDoc" accept="application/msword,text/plain, application/pdf,image/x-png,image/gif,image/jpeg">
						</div>
				</div>
				<div class="col-md-6">
						<div class="form-group">
						
							<label>Event Category
							<a href="#" title="Click to Add More Categories" id="additionalRole" data-toggle="modal" data-target="#modal-default-Role"><i class="fa fa-fw fa-plus"></i></a>
							 </label>
							 <select class="form-control select2" id="CategorySel" name="CategorySel" style="width: 100%;" >
							  <?php
							  if($getEventDetailsRow['event_category']==' ')
							  {
							  ?>
							  <option value="" selected disabled>Please Select from Below</option>
							  <?php
							  }
							  else
							  {
							  ?>
								<option value="<?php echo $getEventDetailsRow['event_category'] ?>" selected ><?php echo $getEventDetailsRow['event_category'] ?></option>
								<?php
							  }
								?>
								<?php
									while ($CategoriesRow = mysqli_fetch_assoc($allCategories))
									{
								?>
									<option value="<?php echo $CategoriesRow['category_Desc']  ?>"><?php echo $CategoriesRow['category_Desc']  ?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
				</div>
						<br>
						<?php
						if($_SESSION['fromEventsHome']!='Y')
						{
						?>
						  <input type="submit" value="Save & Continue" id="EventParticularsSave" class="btn btn-success pull-right"></input> 
						  <?php
						}
						else
						{
						  ?>
						  <input type="submit" value="Save" id="EventParticularsSave" class="btn btn-success pull-right"></input>  
						  <?php
						}
						  ?>
			</div>			
           
			
          
          </div>
		  <br>
		
</div>
</form>
<br>
 <div class="modal fade" id="modal-default-Role">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Category</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="roleForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Category Desc</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputRole" name="inputRole" placeholder="Enter Category" />
                  </div>
                </div>
               
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addRolebtn"  class="btn btn-primary">Add Category</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
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
<script type="text/javascript">
       $(document).on('click','#addRolebtn',function(e) {
		   var data = $("#inputRole").serialize();
  //var data = $("#roleForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addCategory.php",
         success: function(data){
			//alert(data);
			AdditionalRole();
			 ajaxindicatorstop();
			 
         }
});
 });
</script>
<script type="text/javascript">
       function AdditionalRole() {
          
			var modal = document.getElementById('modal-default-Role');
            var ddl = document.getElementById("CategorySel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
</script>
<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("EventLoc").value === "Internal Location") {
		document.getElementById("EventVenue").disabled=true;
		
	}
	else
	{
		document.getElementById("EventVenue").disabled=false;
		
	}
	
}
</script>
<script type="text/javascript">
function changetextbox1()
{
    if (document.getElementById("IsRegRequired").value === "N") {
		document.getElementById("datepicker2").disabled=true;
		
	}
	else
	{
		document.getElementById("datepicker2").disabled=false;
		
	}
	
}
</script>
	<script>
function CheckforBal()
{
	var returnvalue = true;
	var from = document.getElementById("datepicker").value;
	var to = document.getElementById("datepicker1").value;
	var LDOR = document.getElementById("datepicker2").value;
	if(Date.parse(from) > Date.parse(to))
	{
		alert("From date Should be lesser than To Date");
		returnvalue = false;
	}
	if(Date.parse(LDOR) > Date.parse(to))
	{
		alert("Last Date of Registration should be Lesser than the Event Dates.");
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
      autoclose: true,
	  startDate: '+d'
    })
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: '+d'
    })
	$('#datepicker2').datepicker({
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
