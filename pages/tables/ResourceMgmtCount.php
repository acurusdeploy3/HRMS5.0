<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Resource Management</title>
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
.main-header
{
    height:50px;
}
.content-wrapper
{
	max-height: 500px;
	overflow-y:scroll;   
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
        Resource Management
<button OnClick="window.location.href='AuditLog.php?id=Resource Management'" type="button" class="btn btn-success pull-right">View Change Log</button>
      </h1>
 
    </section>
	<?php
	include("config.php");
	session_start();
	$userid=$_SESSION['login_user'];
	$usergrp=$_SESSION['login_user_group'];
	$_SESSION['previous']='ResourceMgmtCount.php';
	$getFirstLineLookup = mysql_query("select * from pms_manager_lookup where manager_id='$userid'");
	if(mysql_num_rows($getFirstLineLookup)>0)
	{
		$isFirstLine='Y';
	}
	if($usergrp!='HR Manager' && $usergrp!='HR')
	{
		if($isFirstLine!='Y')
		{
	?>
	<?php 
	include("config.php");
	$desgnQuery = mysql_query("select designation,count(designation) as count from resource_management_table where is_active='Y' and designation!='' group by designation ");
	$deptQuery = mysql_query("select department,count(department) as count from resource_management_table where is_active='Y' and department!='' group by department ;");
	$projQuery = mysql_query("select a.project_id,b.project_name,count(a.project_id) as count,date_format(max(date(allocated_to)),'%D %b, %Y') as tentative_end_date from employee_projects a
left join all_projects b on a.project_id = b.project_id
where is_active='Y'
group by a.project_id;");
	?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Department based Employee Count</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Department</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($deptRow = mysql_fetch_assoc($deptQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><?php echo $deptRow['department'];  ?></td>
                 <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><span class="badge bg-yellow"><?php echo $deptRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Project Based Employee Count</h3>
              	 <button OnClick="window.location='AvailableResource.php'" style="background-color: steelblue;align:right;" type="button" class="btn btn-info pull-right" >View Available Resources</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Project</th>
                  <th>Tentative End Date</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($ProRow = mysql_fetch_assoc($projQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                
			  <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><?php echo $ProRow['project_name'];  ?></td>
				<td><?php echo  $ProRow['tentative_end_date'] ;  ?></td>
                 <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><span class="badge bg-green"><?php echo $ProRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>     
            </div>
            <!-- /.box-body -->
          </div>
		  
           
		  <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Role Based Employee Count
             
              </h3>
 <button OnClick="window.location='ResourceMgmt.php'" style="background-color: steelblue;align:right;" type="button" class="btn btn-info pull-right">Search by Employee</button>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
               <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Role</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($RoleRow = mysql_fetch_assoc($desgnQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><?php echo $RoleRow['designation'];  ?></td>
                 <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><span class="badge bg-light-blue"><?php echo $RoleRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box --
          <!-- /.box -->
	
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
	
	<?php
	}
	else
	{
	?>
	<section class="content">
	<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">
		<?php  
		$dept = $_GET['id'];
		?>
               <!-- /.box -->
		<div class="box">
		<div class="box-header">
			<br>
			<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
                    <button OnClick="window.location='../../DashBoardFinal.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
               <h4 class="box-title">Resource Management : <strong>Your Team</strong></h4>
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config.php");
			session_start();
			$reid = $_SESSION['login_user'];
			$query = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,employee_designation as designation,
 TIMESTAMPDIFF(MONTH,date_joined,now()) as Months,department
 from employee_details where employee_id in (select employee_id from pms_manager_lookup where manager_id=$reid) and is_active='Y';");
				$count = mysql_num_rows($query);
				
				$MngQuery = mysql_query("Select Employee_id,concat(First_Name,' ',Last_Name,' ',Mi) as Employee_Name,Job_Role from employee_details where Job_Role not in
				('Employee','HR','System Admin') and employee_id not in ('3','4') and is_active='Y'");
				$DepartmentQuery = mysql_query("SELECT department_id,department FROM `all_departments` where department!='' and department!='$CurrentDepartment'");
   				$MngrName = mysql_query("select employee_name from employee where employee_id=$reid");
    			$MgrNamRow= mysql_fetch_array($MngrName);
    			$ManagerName = $MgrNamRow['employee_name'];
			?>
   </form>        

            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Employee ID</th>
			      <th>Name</th>
                  <th>Designation</th>
				  <th>Department</th>
                  <th>Raise Change Request</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
				?>
                <tr>
                  <td class="EmpID"><?php echo $Employees['employee_id'];  ?></td>
                  <td><?php echo $Employees['name'];  ?></td>
                  <td><?php echo $Employees['designation'];  ?></td>
                  <td><?php echo $Employees['department'];  ?></td>
				 <?php
				 $getRequested = mysql_query("select * from resource_change_Request where raised_for='".$Employees['employee_id']."' and status='Request under Process' and is_active='Y'");
				 if(mysql_num_rows($getRequested)==0)
				 {
				 ?>
				 <td> <a href="#" class="RequestChange" id="RaiseChangeRequest" data-toggle="modal" data-target="#modal-default-Request"><img alt='User' src='../../dist/img/editimg.png' width='18px' height='18px' /></a></td>
                <?php
				 }
				else
				{
				?>
				<td>Change Requested Already!</td>
				
				<?php
				}
				?>
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
			
			<div class="modal fade" id="modal-default-Request">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Raise Change Request</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
            <form id="RequestForm" method="post" action="">
        <div class="box-body">
          <div class="row">
			<br>
			 <input type="hidden" name="EmplID" id="EmplID" />
			 <input type="hidden" name="MgmrID" id="MgmrID" />
		  
            <div class="col-md-6">
               <div class="form-group">
                 <label>Current Department</label>
				 <input type="text" class="form-control pull-right"  required="required" name="CurrentDepartment" id="CurrentDepartment" required readonly />	
              </div>
			    <br>
				  <br>
              <div class="form-group">
                 <label>Request Change of Department To</label>
              <select class="form-control select2" id="EmployeeDepartment" name="EmployeeDepartment" style="width: 100%;">
                 <option value="" selected >Please Select from Below</option>
				 <?php
				 while($DepartmentQueryRow = mysql_fetch_assoc($DepartmentQuery))
				 {
				 ?>
                 <option value="<?php echo $DepartmentQueryRow['department'] ?>"><?php echo $DepartmentQueryRow['department'] ?></option>
                <?php
				 }
				 ?>
				</select>
            </div>
		</div>
		<div class="col-md-6">
              <div class="form-group">
                 <label >Current Reporting Manager</label>
				  <input type="text" class="form-control pull-right"  required="required" name="CurrentReporting" id="CurrentReporting"  required readonly />	
              </div>
			    <br>
				  <br>
              <div class="form-group">
                 <label>Request Change of Reporting Manager To</label>
             <select class="form-control select2" id="EmployeeManager" name="EmployeeManager" style="width: 100%;">
                 <option value="" selected disabled>Please select from below</option>
					 <?php
				 while($MngQueryRow = mysql_fetch_assoc($MngQuery))
				 {
				 ?>
                 <option value="<?php echo $MngQueryRow['Employee_id'] ?>"><?php echo $MngQueryRow['Employee_Name'] ?></option>
                <?php
				 }
				 ?>
				</select>
            </div>
		</div>
		<br>
		<div class="col-md-12">
		<div class="form-group">
                 <label>Reason for Change</label>
				  <textarea name="reason" id="reason" rows="5" cols="20" maxlength="200" class="is-maxlength" style="
    width: 100%;"></textarea>
		</div>
		</div>
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole1" onclick="clearFields();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SendRequest" value="Send Request" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
          </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      <!-- /.row -->
    </section>
	
	
	<?php
		}
	}
	else
	{
	?>
	<?php 
	include("config.php");
	$desgnQuery = mysql_query("select designation,count(designation) as count from resource_management_table where is_active='Y' and designation!='' group by designation ");
	$deptQuery = mysql_query("select department,count(department) as count from resource_management_table where is_active='Y' and department!='' group by department ;");
	$projQuery = mysql_query("select a.project_id,b.project_name,count(a.project_id) as count,date_format(max(date(allocated_to)),'%D %b, %Y') as tentative_end_date from employee_projects a
left join all_projects b on a.project_id = b.project_id
where is_active='Y'
group by a.project_id;");
	?>
	<section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Department based Employee Count</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Department</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($deptRow = mysql_fetch_assoc($deptQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><?php echo $deptRow['department'];  ?></td>
                 <td><a href="ViewbyDept.php?id=<?php echo $deptRow['department'] ?>"><span class="badge bg-yellow"><?php echo $deptRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Project Based Employee Count</h3>
              	 <button OnClick="window.location='AvailableResource.php'" style="background-color: steelblue;align:right;" type="button" class="btn btn-info pull-right" >View Available Resources</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Project</th>
                  <th>Tentative End Date</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($ProRow = mysql_fetch_assoc($projQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                
			  <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><?php echo $ProRow['project_name'];  ?></td>
				<td><?php echo  $ProRow['tentative_end_date'] ;  ?></td>
                 <td><a href="ViewbyProject.php?id=<?php echo $ProRow['project_id'] ?>"><span class="badge bg-green"><?php echo $ProRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>     
            </div>
            <!-- /.box-body -->
          </div>
		  
           
		  <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Role Based Employee Count
             
              </h3>
 <button OnClick="window.location='ResourceMgmt.php'" style="background-color: steelblue;align:right;" type="button" class="btn btn-info pull-right">Search by Employee</button>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
               <table class="table table-bordered">
			  <tr>
                  <th style="width: 10px">#</th>
                  <th>Role</th>
                  <th style="width: 40px">Count</th>
                </tr>
			  <?php  
				$i = 1;
				while($RoleRow = mysql_fetch_assoc($desgnQuery))
				{
			  ?>
					<tr>
                  <td><?php echo $i;  ?></td>
                  <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><?php echo $RoleRow['designation'];  ?></td>
                 <td><a href="ViewbyRole.php?id=<?php echo $RoleRow['designation'] ?>"><span class="badge bg-light-blue"><?php echo $RoleRow['count'];  ?></span></td>
                </tr>
				
				<?php
					$i++;
				}
				?>
			</table>   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box --
          <!-- /.box -->
	
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
	
	<?php
	}
	?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
   
    <strong>Acurus Solutions Private Limited </strong>
  </footer>

  <!-- Control Sidebar -->
  
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
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(document).ready(function() {
    $("#RequestForm").submit(function(e) {
	ajaxindicatorstart("One Moment Please..");
	e.preventDefault();
		var EmployeeDepartment = $('#EmployeeDepartment').val();
		var EmployeeManager = $('#EmployeeManager').val();
		if(EmployeeDepartment=='' && EmployeeManager=='')
		{
			alert("Please choose atlease one change you wish to Request.");
			return false;
		}
		else
		{
			SubmitRequest();
		}
});
});
    </script>
	<script>
	function SubmitRequest()
	{
	ajaxindicatorstart("Please Wait..");
	var data = $("#RequestForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "RequestChange.php",
         success: function(data)
		 {
			window.location.href='ResourceChangeRequest.php';
			ajaxindicatorstop();
         }
});
	}
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
<script>

	$(function() {
  var bid, trid;
  $('#example1').on('click', 'tbody tr', function () {
       Id = $(this).find('.EmpID').text();
		$('#EmplID').val(Id);
		var EmployeeID = $('#EmplID').val();
		$.ajax({
		url: 'GetCurrentData.php',
        type: 'post',
		dataType: "json",
		data: {
      	'EmployeeID' : EmployeeID,
		},
      success: function(response){
      	if (response != '' ) {
            $('#CurrentDepartment').val(response.result.department);
            $('#MgmrID').val(response.result.reporting_manager_id);
            $('#CurrentReporting').val(response.result.Manager);
			$('#EmployeeDepartment').val(response.result.department);
			$('#EmployeeDepartment').select2().trigger('change');
         $('#EmployeeManager').val(response.result.reporting_manager_id);
			$('#EmployeeManager').select2().trigger('change');
      }
 	}
	});
  });
});
	</script>
	<!--<script>
$('#EmployeeDepartment').on('change', function() {
		var Department = $('#EmployeeDepartment').val();
		$.ajax({
				url: 'getManagers.php',
				type: 'post',
				data: {
				'department' : Department,
					},
					success: function(response){
					if (response != '' ) {
        
					 var resp = $.trim(response);
					$("#EmployeeManager").html(resp);
						}
 	}
	});	
		
	});
</script>!-->
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
	   startDate: '+0d'
	  
    })
	 $('#datepicker1').datepicker({
	  startDate: '+0d',
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
