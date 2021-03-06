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
  <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

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
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Training Management</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
               <!-- /.box -->
		<div class="box">
            <div class="box-header">
			<br>
			<?php
			session_start();
			$usergrp = $_SESSION['login_user_group'];
			$_SESSION['viewTrainees']='CreatedTrainings.php';
			if($usergrp == 'HR Manager')
			{
			?>
			<table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
                    <button OnClick="window.location='NewTraining.php'" type="button" class="btn btn-block btn-primary btn-flat">Create New Training</button>
			  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  
			  <?php
			}
			  ?>
			  <br>
              <h3 class="box-title"><strong>Training(s) Summary</strong></h3>
			  <br>
			  
			  
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Module</th>
				  <th>Department</th>
				  <th>Date of Commencement</th>
				  <th>Completion %</th>
                  <th>View Schedule</th>
                  <th>View Participants</th>
				  <th>View Summary</th>
                </tr>
                </thead>
				<?php
				session_start();
				$_SESSION['fromTrainer']='N';
				$employeeid= $_SESSION['login_user'];
				include("config.php");
				if($usergrp=='HR Manager')
				{
				$query = mysql_query("select training_id,a.training_module_id,b.training_desc,frequency,cycle_id,training_department,date_started from active_trainings a
left join all_training_modules b on a.training_module_id=b.training_module_id where is_active='Y'");
				}
				else
				{
					$query= mysql_query("select distinct(a.training_id) as training_id,a.training_module_id,b.training_desc,frequency,a.cycle_id,training_department,date_started from active_trainings a
left join all_training_modules b

 on a.training_module_id=b.training_module_id
left join training_sessions c on a.training_id=c.training_id
 where a.is_active='Y' and trainer='$employeeid' and c.is_Active='Y'");
				}
				$count = mysql_num_rows($query);
				?>
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
				$Chckparticipant = mysql_query("select * from training_participants where training_id='".$Employees['training_id']."' and employee_id='$employeeid' and is_active='Y'");
				?>
                <tr>
                  <td><?php echo $Employees['training_desc'];  ?></td>
                  <td><?php echo $Employees['training_department'];  ?></td>
                  <td><?php if($Employees['date_started']=='0001-01-01') { echo "Not Scheduled"; } else { echo $Employees['date_started']; }  ?></td>
				  <?php
				   $getper= mysql_query("select
round(
(select count(is_completed) from training_Sessions where is_completed!='N' and training_id='".$Employees['training_id']."' and cycle_id='".$Employees['cycle_id']."' and is_active='Y')
  /
 (select count(*) from training_Sessions where training_id='".$Employees['training_id']."' and cycle_id='".$Employees['cycle_id']."' and is_active='Y')
   *
   100
  ) as percent");
  $getPerRow = mysql_fetch_array($getper);
  $percentage = $getPerRow['percent'];
				  ?>
                  <td><span class="badge bg-green"><?php echo $percentage.' %';  ?></span></td>
      
                   <td><a href="ViewSchedule.php?id=<?php echo $Employees['training_id'] ?>"><img alt='User' src='../../dist/img/overtime.png' width='18px' height='18px' /></a></td>
                  <td><a href="ViewTrainees.php?id=<?php echo $Employees['training_id'] ?>"><img alt='User' src='../../dist/img/Who-Is-Participating-PD_3.png' width='18px' height='18px' /></a></td>
				<td><a href="TrainingSummary.php?id=<?php echo $Employees['training_id'] ?>"><img alt='User' src='../../dist/img/details-icons--download-8-free-details-icon-page-1--18.png' width='18px' height='18px' /></a> </td>
				</tr>
                <?php
					}
				}
				else
				{
					?>
					 <tr>
					   <td>No Active Trainings</td>
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
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
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
</body>
</html>
