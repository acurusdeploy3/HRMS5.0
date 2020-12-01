<?php
session_start();
include("config.php");
$EventID = $_GET['id'];
$_SESSION['eventid']=$EventID;
$getEventDetails = mysqli_query($db,"SELECT date(date_from) as date_from,date(date_to) as date_to,
									date_format(date_from,'%h:%i %p') as from_time,date_format(date_to,'%h:%i %p') as to_time,
									event_title,event_location,event_desc,additional_comments,event_category,event_website,event_logo FROM `active_events` where event_id='$EventID' ");
$getEventDetailsRow = mysqli_fetch_array($getEventDetails);
$getAllBusinessUnits = mysqli_query($db,"SELECT business_unit FROM `all_business_units` ");
$getAllDepartment = mysqli_query($db,"SELECT department FROM `all_departments` ");
$getAllEmployees = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI,' : ',employee_id) as Name FROM `employee_Details` where is_active='Y'");
$getFeedbacks = mysqli_query($db,"select a.employee_id,concat(b.first_name,' ',b.last_name,' ',mi) as Name,business_unit,department,feedback from
									event_feedbacks a inner join employee_details b on a.employee_id=b.employee_id
									where event_id='$EventID' and feedback!='';");

$getMementoDesc = mysqli_query($db,"SELECT memento_desc FROM `event_common_memento` where event_id=$EventID;");
				$getMementoDescRow = mysqli_fetch_array($getMementoDesc);
				$MementoArranged = $getMementoDescRow['memento_desc'];
				$getRespondedCount = mysqli_query($db,"SELECT employee_id FROM `event_invitation_acceptors` where event_id=$EventID and is_active='Y' and has_attended='Y';");
					$RespondedCount = mysqli_num_rows($getRespondedCount);
					$getRespondedFamily = mysqli_query($db,"SELECT * FROM `event_invitation_acceptors_family` where event_id=$EventID and is_active='Y' and has_attended='Y';");
					$FamilyCount = mysqli_num_rows($getRespondedFamily);
					$TotalAttendees = $FamilyCount+$RespondedCount;
				$MementoReceived = mysqli_query($db,"select * from event_invitation_acceptors where event_id='$EventID' and is_memento_received='Y'");	
				$mementoCount = mysqli_num_rows($MementoReceived);
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
        <li><a href="EventsInfo.php?id=<?php echo $EventID ?>">Active Events</a></li>
        <li class="active">Post Event Processing</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><strong><?php echo $getEventDetailsRow['event_title'].' : Post Event Processing & Feedback'; ?> </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
				 <div class="row">
		 <div class="col-xs-12">
		  <div class="col-xs-12">
		  <form id="PostForm" method="POST" action="SaveComments.php" onSubmit="return CheckforBal();">
			<div class="col-md-6">
			 <input type="hidden" name="EventID" value="<?php echo $EventID ?>" />
						<div class="form-group">
							<label>Total Attendee(s)</label>		
								<input type="text" name="TotalAttendees" class="form-control pull-right" value="<?php echo $TotalAttendees;  ?>" required disabled>	
						</div>
			  </div>
			  <?php 
			  if(mysqli_num_rows($getMementoDesc)>0)
			  {
			  ?>
			<div class="col-md-6">	
						<div class="form-group">
							<label>Total Memento(s) Given</label>
									<input type="text" name="TotalMementos" class="form-control pull-right" value="<?php echo $mementoCount;  ?>" required disabled>	
						</div>
			</div>
				<?php
			  }
				?>
		</div>					
          
			<br>
			<br>
			<div class="col-xs-12">
			<div class="box-header">
    <h3 class="box-title"><strong></strong></h3>
		    <br>
              <div class="box-tools">
              </div>
			  <br>
            </div>
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>S.No</th>
				  <th>Feedback Provided</th> 
                </tr>
                </thead>
				
                <tbody>
				<?php
				if(mysqli_num_rows($getFeedbacks) > 0){
					$i=1;
				while ($getFeedbacksRow = mysqli_fetch_assoc($getFeedbacks))
					{
				?>
                <tr>
                 
                  <td><?php echo $i;  ?></td>
                  <td><?php echo $getFeedbacksRow['feedback'];  ?></td>
                </tr>
                <?php
					$i=$i+1;
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
			    </div>
				
				<div class="col-md-12">
				<div class="form-group">
					<label><strong></strong></label>
				</div>
				<div class="form-group">
					<label> <strong>Please Fill your Comments</strong><span class="astrick">*</span></label>
						<textarea name="FeedBackInfo" value="<?php echo $getEventDetailsRow['additional_comments'] ?>" style="resize: none;width: 100%" id="reason" rows="7" cols="183" maxlength="1500" class="is-maxlength" required="required" required 
						><?php echo $getEventDetailsRow['additional_comments'] ?></textarea>
				</div>
			</div>
				<div class="box-footer"> 
			  <input type="submit" value="Save" id="EventCommSave" class="btn btn-success pull-right"></input> 
               <button type="button" onclick="window.location='EventInfo.php?id=<?php echo $EventID  ?>'" name="Submit" class="btn btn-primary pull-left" id="savefields">Back Home</button>     
              </div>
				
			    </div>
			</form>  			  
         
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
				ajaxindicatorstart("Saving Comments..");
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