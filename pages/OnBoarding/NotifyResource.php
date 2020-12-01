<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resource Management</title>
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
        Resource Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../index.html"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="ResourceMgmtCount.php">Resource Management</a></li>
        <li class="active">Modify Resource</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
		session_start();
		
		$id = $_SESSION['EmpId'];
		$createdby =$_SESSION['login_user'];
		?>
		<input type="hidden" value ="<?php echo $id  ?>" id="EmpId">
	 <?php
			  include("config.php");
			  session_start();
			  $getName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id=$id");
			  $EmpNameRow = mysql_fetch_array($getName);
			  $EmpName = $EmpNameRow['name'];
			  $getReportingMngr = mysql_query("select reporting_manager from resource_management_table where employee_id = $id and is_Active='Y'");
			  $ManagerRow = mysql_fetch_array($getReportingMngr);
			  $repMngrid  = $ManagerRow['reporting_manager'];
			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$repMngrid'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];
			  $getmngrDept = mysql_query("select distinct(department) from resource_management_table where employee_id=$repMngrid and department!='';");
			   $ManagerDeptRow = mysql_fetch_array($getmngrDept);
			  $repMngrDept = $ManagerDeptRow['department'];
			  
			  $resQuery = mysql_query("select res_management_id,employee_id,concat(band,' ',designation,' ',level) as Designation,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager


					from resource_management_table
						 WHERE employee_id=$id and is_Active='Y'");
			  $tRow = mysql_fetch_array($resQuery);
			  $rId = $tRow['res_management_id']; 
			  $band = $tRow['band']; 
			  $desn = $tRow['Designation']; 
			  $level = $tRow['level']; 
			  $monthsserved = $tRow['Effective_From']; 
			  $projname = $tRow['project_id']; 
			  $Dept = $tRow['department']; 
			  $_SESSION['rID']=$rId;
			  ?>
			   <?php
			  include("config.php");
			  $query = mysql_query("Select designation_id,designation_desc from all_designations where designation_desc!='$desn'");
			  $query1 = mysql_query("Select band_no,band_desc from all_bands where band_desc !='$band' and band_desc!=''");
			  $query2 = mysql_query("Select level_id,level_desc from all_levels where level_desc !='$level'");
			  $query3 = mysql_query("SELECT department_id,department FROM `all_departments` where department!='$Dept'");
			  $query4 = mysql_query("select project_no,project_id,project_name from all_projects where project_id!='$projname'");
			  $MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role='Manager' and employee_id not in ('$repMngrid','$id')");
			  
			  ?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
		<div class="box-header">
			
			<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
			</td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title"><strong><?php echo $EmpName ?> : <?php echo $id  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			
            </div>
        </div>
		   
		  
		
	
        <!-- /.box-header -->
		<form id="ResForm" enctype="multipart/form-data" method="post" action="InsertLOA.php">
        <div class="box-body">
		
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">
				
                <label>Notify Employee </label>
				<?php
				$degn= $_SESSION['DesChange'];
				$rep = $_SESSION['RepMgmr'];
				$Project = $_SESSION['Proj'];
				$DepartmentVal = $_SESSION['Dept'];
				$effec =$monthsserved;
				$_SESSION['effectivefrom']=$effec;
				$Cont = $degn.$rep.$DepartmentVal.$Project.' However, this will get effect from '.$monthsserved.'.';
				?>
					<input type="hidden" id="hidEmpVal" name="hidEmpVal" value="<?php echo $id ?>" >
                <textarea id="NotVal" name="NotVal" value="<?php echo $Cont." Please contact your Manager or HR for any Queries."  ?>" class="form-control" rows="7" col="19" placeholder="Enter ..."><?php echo $Cont." Please contact your Manager or HR for any Queries."  ?></textarea>
              </div>
              <!-- /.form-group -->
            
				<div class="form-group">
				 <button type="button" style="width:50%" id="SendNotification" class="btn btn-block btn-primary" >Send Notification</button>
				 
			 </div>
              <!-- /.form-group -->
            </div>
            <div class="col-md-6">
             
			     <div class="form-group">
				<?php
				session_start();
				$_SESSION['LOAName']=$EmpName;
				$_SESSION['loacont']="Effective from ".$monthsserved.", your designation would be ".$desn." under Department - ".$Dept.". You would be reporting to ".$repMngrName." of Department - ".$repMngrDept.".";

				?>
				
			
                <label>View /  Download Office Order</label>
				 <button type="button" OnClick="window.open('GeneratePDF/DownloadLOA.php')" style="width:30%" id="GenerateLOA" class="btn btn-block btn-danger" >Generate Office Order</button>
              </div>
			   <div class="form-group">
				
                <label>Upload Signed Office Order</label>
				 <input type="file" id="ResumeFileDoc"  oninput="this.className = ''" name="ResumeFileDoc" accept= "application/msword,text/plain, application/pdf,image/x-png,image/gif,image/jpeg" required="required" />
              </div>
 
			  <div class="form-group">
				  <input type="submit" style="width:30%" id="UploadDoc" value="Upload Signed OO" class="btn btn-block btn-primary" />
				 </div> 
				   <div class="col-xs-12">
          <a href="#" target="_blank" data-toggle="modal" data-target="#modal-default-Level" class="btn btn-danger pull-right">Skip Upload</a>
          <a href="#" id="notBtn" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Not" class="btn btn-danger pull-right">Skip Upload</a>
        </div>
              <!-- /.form-group -->
            </div>
			 
            <!-- /.col -
			
            <!-- /.col -->
          </div>

			
		
			
			  </tbody>
			  </table>	  
</div>	
  <div class="modal fade" id="modal-default-Level">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Set Remainder</h4>
              </div>
            <div class="modal-body">
                <p>Do you want to set a Remainder for Office Order Upload for <?php echo $EmpName ?></p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeLvl" OnClick="window.location='ViewResource.php?id=<?php echo $id ?>'" class="btn btn-default pull-left" data-dismiss="modal">Not Required</button>
                 <button type="button" id="SetRemainder" OnClick="window.location='SetRemainderforUpload.php'" class="btn btn-primary">Yes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		  <div class="modal fade" id="modal-default-Not">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
            <div class="modal-body">
                <p>Notification E-Mail sent to &nbsp; <strong><?php echo $EmpName ?> </strong></p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
  
        </div>
</form>
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
<script type="text/javascript">
       $(document).on('click','#addRolebtn',function(e) {
		   var data = $("#inputRole").serialize();
  //var data = $("#roleForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addRole.php",
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
            var ddl = document.getElementById("RoleSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
    </script>
	
	
	
	<script type="text/javascript">
       $(document).on('click','#addBandbtn',function(e) {
		 //  var data = $("#inputBand").serialize();
  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addBand.php",
         success: function(data){
			//alert(data);
			AdditionalBand();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
   $(document).on('click','#ModifyResource',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ResForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertModifiedResource.php",
         success: function(data){
		 window.location.href = "NotifyResource.php";
		   ajaxindicatorstop();
			
         }
});

});
    </script>
	<script type="text/javascript">
       function AdditionalBand() {
          
			var modal = document.getElementById('modal-default-Band');
            var ddl = document.getElementById("BandSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBandDesc").value;
            option.value = document.getElementById("inputBandDesc").value;
            ddl.options.add(option);
			document.getElementById("closeBand").click();
			document.getElementById("inputBandDesc").value="";
			document.getElementById("inputBand").value="";
        
			     
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
			//alert(data);
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Dept');
            var ddl = document.getElementById("DeptSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDept").value;
            option.value = document.getElementById("inputDept").value;
            ddl.options.add(option);
			document.getElementById("closeDept").click();
			document.getElementById("inputDept").value="";
        
			     
        }
    </script>
	
	
	
	
	
	
	
	
	<script type="text/javascript">
       $(document).on('click','#addLvlbtn',function(e) {
		   var data = $("#inputLevel").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddLevel.php",
         success: function(data){
			//alert(data);
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
       $(document).on('click','#SendNotification',function(e) {
		   var data = $("#NotVal").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "SendNotification.php",
         success: function(data){
			//alert(data);
			ajaxindicatorstop();
			//ShowSuccessNotification();
				$('#notBtn').click();
			 
			 
         }
});
 });
    </script>

	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Level');
            var ddl = document.getElementById("LevelSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputLevel").value;
            option.value = document.getElementById("inputLevel").value;
            ddl.options.add(option);
			document.getElementById("closeLvl").click();
			document.getElementById("inputLevel").value="";
        
			     
        }
    </script>

		<script>
   function setfilename(val)
  {
    var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
	//alert(fileName);
   document.getElementById("fileName").text = fileName;
  }
</script>
</body>
</html>
