<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Training Management</title>
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
        Training Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.html"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="TrainingMgmt.php">Active Trainings</a></li>
        <li class="active">New Training</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<?php 
	session_start();
	include("config2.php");
	$sesscnt = $_SESSION['sesscnt'];
	$trainid = $_SESSION['trainingId'];
	$GetTrainModule = mysqli_query($db,"select training_module_id,cycle_id,current_occurence from active_trainings where training_id='$trainid'");
	$getTrainingModuleRow = mysqli_fetch_array($GetTrainModule);
	$Module = $getTrainingModuleRow['training_module_id'];
	$Cycle = $getTrainingModuleRow['cycle_id'];
	$Occ = $getTrainingModuleRow['current_occurence'];
	$getModuleName = mysqli_query($db,"select training_desc from all_training_modules where training_module_id='$Module'");
	$getTrainingModuleRowName = mysqli_fetch_array($getModuleName);
	$ModuleName = $getTrainingModuleRowName['training_desc'];
	?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Schedule Training : <strong><?php echo " ".$ModuleName; ?> </strong></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
		 <div class="col-xs-12">
        
            <div class="box-header">
              <h3 class="box-title">Training Cycle : <?php echo " ".$Cycle ?>, Occurrence : <?php echo " ".$Occ ?></h3>

              <div class="box-tools">
              </div>
            </div>
			<form id="AllocationForm" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  <?php
			  include("config.php");
			   $Trainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from all_trainers a left join employee_details b
on a.employee_id=b.employee_id");
			   $AllTrainers = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id where a.employee_id not in (select employee_id from all_trainers )");
			  $recaud = mysql_query("select distinct(department) as department from resource_management_table where department!=''");
			  $recaudRole = mysql_query("select distinct(designation) as designation from resource_management_table where department!=''");
			  $query1 = mysql_query("Select department_id,department from all_departments where department!=''");
			  $EmpQuery = mysql_query("Select a.employee_id,concat(concat(b.First_Name,' ',b.Last_Name,' ',b.Mi),' : ',a.employee_id) as Employee_Name,b.Job_Role from all_trainers a left join employee_details b on a.employee_id=b.employee_id");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager'");
			  $EmpQuery1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id");
			  $Freq = mysql_query("Select frequency from training_frequency");
			  ?>
			  
			  <input type="hidden" name="cycleVal" value="<?php echo $Cycle ?>" />
			  <input type="hidden" name="OccVal" value="<?php echo $Occ ?>" />
                 <label>Trainer <a href="#" id="additionalTrainer" title="Click to Add More Trainers" data-toggle="modal" data-target="#modal-default-Trainers"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="TrainerSel" name="TrainerSel" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($trai = mysql_fetch_assoc($Trainers))
				{
				?>
                  <option value="<?php echo $trai['employee_id']  ?>"><?php echo $trai['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  
			  <div class="form-group">
                <label>Session Date</label>
					
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="sessdate" class="form-control pull-right" id="datepicker1" required>
                </div>
              </div>
				<!-- /.form-group -->  
			
			 
			   <div class="form-group">
                 <label>Duration Per Session (Hours)</label>
             <input type="number" class="form-control pull-right" min="0" name="SessDuration" id="SessDuration" placeholder="Enter Duration" required="required" />
              </div>
			  <br>
			  <br>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
               <div class="form-group"> 
                 <label>Topic</label>
                <input type="text" name="topicText" class="form-control" placeholder="Enter Topic of Interest">
              </div>
			 <div class="form-group">
                <label>Start Time</label>
					
              <div class="input-group">
                    <input type="text" name="startTime" class="form-control timepicker">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
              </div>
			  <div class="form-group">
                <label>Mode of Training</label>
					
                 <select class="form-control select2" id="TrainingMode" name="TrainingMode" style="width: 100%;" required>
                 <option value="" selected disabled>Please Select from Below</option>
                 <option value="Classroom Training">Classroom Training</option>
                 <option value="On Demand"  >On Demand</option>
                 <option value="Live Online" >Live Online</option>

                </select>
              </div>
              <!-- /.form-group -->
              
				 
                  
			  
			   
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		  <br>
		<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
				</tr>
			  <tr>
			   <td>
			   </td>
                   <td> 
				    <a href="#" type="submit" id="AddtoSchedule" name="AddtoSchedule" class="btn btn-block btn-primary">Add to Schedule</a>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>	  
</div>	
</form>
<br>
<?php
include("config2.php");  
 $getSessions= mysqli_query($db,"select topic,trainer,concat(b.first_name,' ',b.last_name,' ',b.MI
) as Name ,Date_format(date_of_Session,'%d - %b - %Y') as start_Date,TIME_FORMAT(session_time, '%h:%i %p') as start_time,
session_duration,mode_of_training
 from training_Sessions a left join employee_details b on a.trainer=b.employee_id where training_id='$trainid'");
?>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Session No</th>
                  <th>Topic To be Discussed</th>
                  <th>Trainer</th>
                  <th>Date of Session</th>
                  <th>Start Time</th>
                  <th>Session Duration</th>
                  <th>Mode of Training</th>
                </tr>
				<?php  
				if(mysqli_num_rows($getSessions)>0)
				{
					$i=1;
					while($SessionRow = mysqli_fetch_assoc($getSessions))
					{
				?>
                <tr>
                  <td><?php echo $i ?></td>
                  <td><?php echo $SessionRow['topic'];  ?></td>
                  <td><?php echo $SessionRow['Name'];  ?></td>
                  <td><?php echo $SessionRow['start_Date'];  ?></td>
                  <td><?php echo $SessionRow['start_time'];  ?></td>
                  <td><?php echo $SessionRow['session_duration'].' '.'Hours';  ?></td>
                  <td><?php echo $SessionRow['mode_of_training'];  ?></td>
                </tr>
                <?php
					$i++;
					}
				}
				else
				{
				?>
				 <tr>
                  <td>Start Scheduling for your Training!</td>
				  </tr>
<?php				
				}
				?>
              </table>
           
            <!-- /.box-body -->
          </div>
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		  <br>
		
		  
			   <div class="col-md-6">
			<div class="form-group">
				 <label></label>
				 <label></label>
                <label>
				
                </label>
               
              </div>	  
</div>	

 <div class="modal fade" id="modal-default-Trainers">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Trainer</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label>Add Trainer</label>
                <select class="form-control select2" id="TrainerSelNew" name="TrainerSelNew" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($Atrai = mysql_fetch_assoc($AllTrainers))
				{
				?>
                  <option value="<?php echo $Atrai['employee_id']  ?>"><?php echo $Atrai['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
                </div>			
              </div>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
            </form>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeTrainer" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addTrainer"  class="btn btn-primary">Add Trainer</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  
		  
		  
		  
		  
		   <div class="modal fade" id="modal-default-Dept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Department</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Department</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDept" name="inputDept" placeholder="Enter Department" />
                    
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
                <button type="button" id="closeDept" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addDeptbtn"  class="btn btn-primary">Add Department</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		   <a href="ViewTraining.php?id=<?php echo $trainid; ?>" id="notBtn"  class="btn btn-success pull-right">Complete Schedule</a>
		  
 <div class="col-md-6">
			
			  </div>
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
  
    <strong><a href="https://adminlte.io">Acurus Solutions Private Limited</a>.</strong> 
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
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
<!-- Page script -->


<script>

var number = document.getElementById('SessDuration');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
</script>
<script type="text/javascript">
   $(document).on('click','#AddtoSchedule',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#AllocationForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertSession.php",
         success: function(data){
			 
		 window.location.href = "AllocateSession.php";
		   ajaxindicatorstop();
			
         }
});

});
    </script>
<script type="text/javascript">
       $(document).on('click','#addTrainer',function(e) {
		   var data = $("#TrainerSelNew").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddTrainer.php",
         success: function(data){
			 AdditionalTrainer();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>

<script type="text/javascript">
       function AdditionalTrainer() {
          
			var modal = document.getElementById('modal-default-Trainers');
            var ddl = document.getElementById("TrainerSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("TrainerSelNew").value;
            option.value = document.getElementById("TrainerSelNew").value;
            ddl.options.add(option);
			document.getElementById("closeTrainer").click();
			//document.getElementById("inputDept").value="";
        
			     
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
      autoclose: true
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
