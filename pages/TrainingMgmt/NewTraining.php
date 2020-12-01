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

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Training</h3>
        </div>
        <!-- /.box-header -->
		<form id="trainingForm" onSubmit="Validate();" method="post" action="InsertNewTraining.php">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
			  <?php
			  include("config.php");
			  $query = mysql_query("Select training_module_id,training_desc from all_training_modules");
			  $recaud = mysql_query("select distinct(department) as department from resource_management_table where department!=''");
			  $recaudRole = mysql_query("select distinct(designation) as designation from resource_management_table where department!=''");
			  $query1 = mysql_query("Select department_id,department from all_departments where department!=''");
			  $EmpQuery = mysql_query("Select a.employee_id,concat(concat(b.First_Name,' ',b.Last_Name,' ',b.Mi),' : ',a.employee_id) as Employee_Name,b.Job_Role from all_trainers a left join employee_details b on a.employee_id=b.employee_id");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager'");
			  $EmpQuery1 = mysql_query("select a.employee_id,concat(First_Name,' ',Mi,' ',Last_Name,' : ',a.employee_id) as Name from resource_management_Table a left join employee_details b
on a.employee_id=b.employee_id");
			  $Freq = mysql_query("Select frequency from training_frequency");
			  ?>
                <label>Training Module  <a href="#" id="additionalMod" title="Click to Add More Modules" data-toggle="modal" data-target="#modal-default-Mod"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2"  id="TrainingModule" name="TrainingModule" required="required" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($module = mysql_fetch_assoc($query))
				{
				?>
                  <option value="<?php echo $module['training_module_id']  ?>"><?php echo $module['training_desc']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			   <div class="form-group">
                 <label>Training Frequency</label>
                <select class="form-control select2" onchange="changeFreq();" id="TrainFreq" name="TrainFreq" required="required" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				<?php
				
				while ($Fre = mysql_fetch_assoc($Freq))
				{
				?>
                  <option value="<?php echo $Fre['frequency']  ?>"><?php echo $Fre['frequency']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div> 
			  <div class="form-group">
                <label>Is Mandatory for All</label>
					
             <select class="form-control select2" onchange="changetextbox();" id="MandForAll" name="MandForAll" required="required" style="width: 100%;" required>
				<option value="" selected disabled>Please Select from Below</option>
				    <option value="Yes">Yes</option>
                   <option value="No">No</option>
                </select>
              </div>
				<!-- /.form-group -->  
			
			 
			   <div class="form-group">
                <label>Recommended Audience (Department Wise)</label>
					
                <select class="form-control select2" id="SelByDept" name="SelByDept[]" multiple="multiple" data-placeholder="Select Department(s)"
                        style="width: 100%;">
                 <?php
				
				while ($aud = mysql_fetch_assoc($recaud))
				{
				?>
                  <option value="<?php echo $aud['department']  ?>"><?php echo $aud['department']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <br>
			  <br>
			 
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
             <div class="form-group">
			  <label>Training Department  <a href="#" id="additionalDept" title="Click to Add More Departments" data-toggle="modal" data-target="#modal-default-Dept"><i class="fa fa-fw fa-plus"></i></a></label>
                <select class="form-control select2" id="TrainingDept" name="TrainingDept" required="required" style="width: 100%;">
				<option value="" selected disabled>Please Select from Below</option>
                 <?php
				
				while ($dept = mysql_fetch_assoc($query1))
				{
				?>
                  <option value="<?php echo $dept['department']  ?>"><?php echo $dept['department']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			   <div class="form-group">
                 <label>Occurrence per Frequency</label>
          <select class="form-control select2" id="TrainFreqOcc" name="TrainFreqOcc" required="required" style="width: 100%;">
				<option value="" selected disabled>Please Select from Below</option>
                   <option value="1">Once</option>
                   <option value="2">Twice</option>
                   <option value="3">Thrice</option>
                   <option value="4">4 Times</option>
                   <option value="5">5 Times</option>
                   <option value="6">6 Times</option>
                   <option value="7">7 Times</option>
                   <option value="8">8 Times</option>
                   <option value="9">9 Times</option>
                   <option value="10">10 Times</option>
                
                </select>
              </div>
			  <div class="form-group">
                <label>Recommended Audience (Role Wise)</label>
					
                <select class="form-control select2" id="SelByRole" name="SelByRole[]" multiple="multiple" data-placeholder="Select Role(s)"
                     style="width: 100%;" >
                 <?php
				
				while ($audR = mysql_fetch_assoc($recaudRole))
				{
				?>
                  <option value="<?php echo $audR['designation']  ?>"><?php echo $audR['designation']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			  <div class="form-group">
                 <label>Is Evaluation Required</label>
			    <select class="form-control select2" id="isEvalRequired" name="isEvalRequired" required="required" style="width: 100%;">
                   <option value="" selected disabled>Please Select from Below</option>
                   <option value="Yes">Yes</option>
                   <option value="No">No</option>                
                </select>
             
              </div>
              <!-- /.form-group -->
              
				 
                  
			  
			   
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>
		
		   <div class="form-group">
                <label>Select Individual Employee(s) (If Required)</label>
					
                <select class="form-control select2" id="TraineesSel" name="TraineesSelVal[]" multiple="multiple" data-placeholder="Select Trainee(s)"
                        style="width: 100%;">
                 <?php
				
				while ($emp2 = mysql_fetch_assoc($EmpQuery1))
				{
				?>
                  <option value="<?php echo $emp2['employee_id']  ?>"><?php echo $emp2['Name']  ?></option>
                 <?php
				}
				 ?>
                </select>
              </div>
			   <div class="col-md-6">
			<div class="form-group">
				 <label></label>
				 <label></label>
                <label>
				
                </label>
               
              </div>
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
				   <input type="submit" id="SubmitTraining" value="Move to Scheduling" class="btn btn-block btn-primary" />
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>	  
</div>	
</form>

 <div class="modal fade" id="modal-default-Mod">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Training Module</h4>
              </div>
              <div class="modal-body">
                 <div class="box box-info">
           
            <form id="DeptForm" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Module</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputMod" name="inputMod" placeholder="Enter Module" />
                    
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
                <button type="button" id="closeMod" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                 <button type="button" id="addModbtn"  class="btn btn-primary">Add Module</button>
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
<!-- Page script -->
<script type="text/javascript">
$(document).ready(function (){
    validate();
    $('#TrainingModule, #TrainFreq, #MandForAll').change(validate);
});

function validate(){
    if ($('#TrainingModule').val().length   >   0   &&
        $('#TrainFreq').val().length  >   0   &&
        $('#MandForAll').val().length    >   0) {
        $("input[type=submit]").prop("disabled", false);
    }
    else {
        $("input[type=submit]").prop("disabled", true);
    }
}
</script>


<script type="text/javascript">
       $(document).on('click','#addModbtn',function(e) {
		   var data = $("#inputMod").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddMod.php",
         success: function(data){
			AdditionalMod();
			location.reload();
			 ajaxindicatorstop();
			 
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalMod() {
          
			var modal = document.getElementById('modal-default-Mod');
            var ddl = document.getElementById("TrainingModule");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputMod").value;
            option.value = document.getElementById("inputMod").value;
            ddl.options.add(option);
			document.getElementById("closeMod").click();
			document.getElementById("inputMod").value="";
        
			     
        }
    </script>


<script type="text/javascript">
       $(document).on('click','#addDeptbtn',function(e) {
		   var data = $("#inputDept").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDept.php",
         success: function(data){
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Dept');
            var ddl = document.getElementById("TrainingDept");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDept").value;
            option.value = document.getElementById("inputDept").value;
            ddl.options.add(option);
			document.getElementById("closeDept").click();
			document.getElementById("inputDept").value="";
        
			     
        }
    </script>
	








<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("MandForAll").value === "Yes") {
		document.getElementById("SelByRole").disabled=true;
		document.getElementById("SelByDept").disabled=true;
		document.getElementById("TraineesSel").disabled=true;
	}
	else
	{
		document.getElementById("SelByRole").disabled=false;
		document.getElementById("SelByDept").disabled=false;
		document.getElementById("TraineesSel").disabled=false;
	}
	
}
</script>
<script type="text/javascript">
function changeFreq()
{
    if (document.getElementById("TrainFreq").value === "Once") {
		document.getElementById("TrainFreqOcc").disabled=true;
		
	}
	else
	{
		document.getElementById("TrainFreqOcc").disabled=false;
		
	}
	
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
<script type="text/javascript">
   $(document).on('click','#SubmitTraining1',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#trainingForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertNewTraining.php",
         success: function(data){
			 
		 window.location.href = "AllocateSession.php";
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
</body>
</html>
