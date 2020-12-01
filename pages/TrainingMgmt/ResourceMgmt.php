
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resource Management</title>
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

  <!-- Font Awesome -->
 
  <!-- DataTables -->

  <!-- Theme style -->
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  

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
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Resource Management</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">

               <!-- /.box -->
		<div class="box">
		 <br>
              <h4 class="box-title">Manage / Search Resource</h4>
             
              
			<?php
			include("config.php");
			session_start();
			$query = mysql_query("select project_no,project_id,project_name from all_projects");
			$query1 = mysql_query("SELECT department_id,department FROM `all_departments`;");
			$query2= mysql_query("select distinct(concat(band,' ',designation,' ',level)) as designation from resource_management_table");
			//$query2= mysql_query("select distinct(position_name) as position_name from all_positions where position_name!=''");
			$query3= mysql_query("SELECT employee_id,concat(first_name,' ',last_name,' ',MI,'- ',employee_id) as name FROM `employee_details` where employee_id not in ('',0);");
			
			
				?>			
		 <div class="box-body">
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
					<input type="button" OnClick="window.history.go(-1); return false;" class="btn btn-block btn-primary" id="Backtbn" value="Back"/>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
          <div class="row">
		   
            <div class="col-md-6">
              <div class="form-group">
                <label>By Designation</label>
                <select id="desgSelect" name="desgSelect" class="form-control select2" style="width: 100%;">
				 <option selected="selected" disabled>Please select a Role Below</option>
                 <?php 
					while($PosData = mysql_fetch_assoc($query2))
						
						{
				 ?>
                  
                  <option value="<?php echo $PosData['designation']?>"><?php echo $PosData['designation']?></option>
                  <?php  
				  
						}
				  ?>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>By Department</label>
                 <select id="deptSelect" name="deptSelect" class="form-control select2" style="width: 100%;">
				  <option selected="selected" disabled>Please select a Department Below</option>
                <?php 
					while($DeptData = mysql_fetch_assoc($query1))
						
						{
				 ?>
                  
                  <option value="<?php echo $DeptData['department']?>"><?php echo $DeptData['department']?></option>
                  <?php  
				  
						}
				  ?>
                </select>
               
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>By Project</label>
                 <select id="proSelect" name="proSelect" class="form-control select2" style="width: 100%;">
				 <option selected="selected" disabled>Please select a Project Below</option>
				 <?php 
					while($proData = mysql_fetch_assoc($query))
						
						{
				 ?>
                  
                  <option value="<?php echo $proData['project_id']?>"><?php echo $proData['project_name']?></option>
                  <?php  
				  
						}
				  ?>
                </select>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                  <label>By Employee</label>
                 <select id="empSelect" name="empSelect" class="form-control select2" style="width: 100%;">
                 <option selected="selected" disabled>Please select a Employee Below</option>
				 <?php 
					while($EmpData = mysql_fetch_assoc($query3))
						
						{
				 ?>
                  
                  <option value="<?php echo $EmpData['employee_id']?>"><?php echo $EmpData['name']?></option>
                  <?php  
				  
						}
				  ?>
                </select>
                 
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
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
					<input type="submit" class="btn btn-block btn-primary" id="SearchBtn" value="Search"/>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
          <!-- /.row -->
        </div>
			 	
   </form>        
            <?php
				include("config.php");
				session_start();
				$empl = $_SESSION['emp_id'];
				$proj = $_SESSION['project'];
				$role = $_SESSION['desgn'];
				$depat = $_SESSION['dept'];
				if($role != '') {
						$role = trim($role);
          //  $where[] = 'V.city_id = '.$cityid.' AND V.is_enabled = "1"';
            $where[] = "concat(band,' ',designation,' ',level) like '%$role%' and is_active='Y'";
				}
			if($depat != '') {
			
				$depat = trim($depat);
            //$where[] = 'V.vid = '.$placeid;
            $where[] = "a.department like '%$depat%' and is_active='Y'";
			
        }
        
		if($proj != '') {
            $proj = trim($proj);
            //$where[] = "( V.visiting_place LIKE '%$keyword%' OR  V.history LIKE '%$keyword%'  OR  C.city LIKE '%$keyword%' )";
            $where[] = "a.project_id like '%$proj%' and is_active='Y'";
        }
		
				if($empl != '') {
            $empl = trim($empl);
            $where[] = "a.employee_id like '%$empl%' and is_active='Y'";
				}

			$sWhere     = implode(' AND ', $where);
				if($sWhere) {
				$sWhere = 'WHERE '.$sWhere;
				} 
				if(($role != '') || ($depat != '') || ($proj != '') || ($empl != '')) {
           // $query = "SELECT * FROM visiting_places AS V JOIN tourist_city AS C ON C.city_id = V.city_id $sWhere ";
				$query = mysql_query("select a.employee_id,concat(first_name,' ',last_name,' ',MI) as name,concat(band,' ',designation,' ',level) as designation,a.department,TIMESTAMPDIFF(MONTH,a.Created_Date_and_time,now()) as Months,project_name

					from resource_management_table a left join employee_details b on a.employee_id=b.employee_id
						left join all_projects c on c.project_id=a.project_id $sWhere");
						
					$count = mysql_num_rows($query);
				}
				?><!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Employee ID</th>
				   <th>Name</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Months Served</th>
                  <th>View / Modify</th>
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
				?>
                <tr>
                  <td><?php echo $Employees['employee_id'];  ?></td>
                  <td><?php echo $Employees['name'];  ?></td>
                  <td><?php echo $Employees['designation'];  ?></td>
                  <td><?php echo $Employees['department'];  ?></td>
                  <td><?php echo $Employees['Months'];  ?></td>
                  <td><a href="ViewResource.php?id=<?php echo $Employees['employee_id'] ?>"><img alt='User' src='../../dist/img/view.png' width='18px' height='18px' /></a></td>
                </tr>
                <?php
					}
				}
				else
				{
					?>
					 <tr>
					   <td>No Results Found</td>
					 </tr>
				<?php	 
				}
					?>
                </tbody>
              </table>
			   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <strong>Acurus Solutions Private Limited.</strong>
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

<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<!-- FastClick -->
<!-- AdminLTE App -->

<!-- page script -->
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
   $(document).on('click','#SearchBtn',function(e) {
     
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#resForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "SearchResource.php",
         success: function(data){
			 
		 window.location.href = "ResourceMgmt.php";
		   ajaxindicatorstop();
			
         }
});

});
    </script>
</body>
</html>
