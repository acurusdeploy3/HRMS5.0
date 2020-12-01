<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Acurus HRMS</title>
  <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Morris charts -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
       Employee Dashboard
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<?php
include('config.php');
$employeeid = $_SESSION['login_user'];

$getCheckinCheckout = mysqli_query($db,"select  date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,time(check_in) as checkin from attendance where check_in != '0001-01-01 00:00:00' and employee_id=$employeeid order by shift_Date desc limit 1;");
$checkinRow = mysqli_fetch_array($getCheckinCheckout);
$lastcheckin = $checkinRow['checkin'];
$lastdate = $checkinRow['shiftdate'];
$getCheckout = mysqli_query($db,"select  date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,time(check_out) as checkout from attendance where check_out != '0001-01-01 00:00:00' and employee_id=$employeeid order by shift_Date desc limit 1;");
$checkoutRow = mysqli_fetch_array($getCheckout);
$lastcheckout = $checkoutRow['checkout'];
$lastdateout = $checkoutRow['shiftdate'];

$lastsevenintime = mysqli_query($db,"select date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,date_format(shift_Date,'%W') as day,time(check_in) as checkin from attendance where check_in != '0001-01-01 00:00:00' and employee_id=$employeeid
 order by shift_Date desc limit 1,7;");
 
 $lastsevenouttime = mysqli_query($db,"select date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,date_format(shift_Date,'%W') as day,time(check_out) as checkout from attendance where check_out != '0001-01-01 00:00:00' and employee_id=$employeeid
 order by shift_Date desc limit 1,7;");
 
 $leavebalance = mysqli_query($db,"SELECT cl_opening,sl_opening,pl_opening,cl_taken,sl_taken,pl_taken,cl_closing,sl_closing,pl_closing FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate()) and month=month(curdate())");
$leavebalanceRow = mysqli_fetch_array($leavebalance);	
$clOpening = $leavebalanceRow['cl_opening'];
$clavailed = $leavebalanceRow['cl_taken'];
$clbalance = $leavebalanceRow['cl_closing'];
$slOpening = $leavebalanceRow['sl_opening'];
$slavailed = $leavebalanceRow['sl_taken'];
$slbalance = $leavebalanceRow['sl_closing'];
$plOpening = $leavebalanceRow['pl_opening'];
$pltaken = $leavebalanceRow['pl_taken'];
$plbalance = $leavebalanceRow['pl_closing'];
?>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
				<div class="inner">
					<h3><?php echo $lastcheckin ?></h3>
					<p>In-Time on <?php echo $lastdate ?></p>
				</div>	
  <div class="icon">
              <i class="fa fa-fw fa-clock-o"></i>
            </div>				
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
				<div class="inner">
					<h3><?php echo $lastcheckout ?></h3>
					<p>Out-Time on <?php echo $lastdateout ?> </p>
				</div>		
	<div class="icon">
              <i class="fa fa-fw fa-clock-o"></i>
            </div>				
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>2</h3>

              <p>Leave(s) Taken this Year</p>
            </div>

          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>1</h3>

              <p>Loss of Pay</p>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- Small boxes (Stat box) -->
     <div class="row">
          <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">In-Time for Last 7 Days</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
			<div class="box-body">
			 <table class="table table-striped">
                <tr>
         
                  <th>Date</th>
                  <th>Day</th>
                  <th>In Time</th>

                </tr>
		<?php
				while ($lastsevenrow = mysqli_fetch_assoc($lastsevenintime))
			{
			?>
                <tr>
                  <td><?php  echo  $lastsevenrow['shiftdate']; ?></span></td>
                  <td><?php  echo  $lastsevenrow['day']; ?></span></td>
                  <td><span class="badge bg-blue"><?php  echo  $lastsevenrow['checkin']; ?></span></td>
                </tr>
				
				<?php
				
			}
				?>
              </table>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		</div>
        <!-- /.col -->
      <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Out-Time for Last 7 Days</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
           
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped">
                <tr>
         
                  <th>Date</th>
                  <th>Day</th>
                  <th>Out Time</th>

                </tr>
		<?php
			while ($lastsevenoutrow = mysqli_fetch_assoc($lastsevenouttime))
			{
			?>
                <tr>
                  <td><?php  echo  $lastsevenoutrow['shiftdate']; ?></span></td>
                  <td><?php  echo  $lastsevenoutrow['day']; ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $lastsevenoutrow['checkout']; ?></span></td>
                </tr>
				
				<?php
				
			}
				?>
              </table>
            </div>
			
			
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
         <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Leave Balance</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
         	
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped">
                <tr>
         
                  <th>Leave Type</th>
                  <th>Approved</th>
                  <th>Availed</th>
                  <th>Balance</th>
               
                </tr>
                <tr>
                  <td>CL</td>
                  <td><span class="badge bg-blue"><?php  echo  $clOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $clavailed ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $clbalance ?></span></td>
                </tr>
                 <tr>
                  <td>PL</td>
                  <td><span class="badge bg-blue"><?php  echo  $plOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $pltaken ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $plbalance ?></span></td>
                </tr>
                <tr>
                  <td>SL</td>
                  <td><span class="badge bg-blue"><?php  echo  $slOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $slavailed ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $slbalance ?></span></td>
                </tr>
              </table>
            </div>
			
			
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
         <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Extra Hours Tracker</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              The body of the box
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-6">
          <!-- AREA CHART -->
          
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Leave(s) Availed This Year</h3>

         
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
        
          <!-- /.box -->

          <!-- LINE CHART -->
            <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>

             
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
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
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    //"use strict";

    //DONUT CHART
    var donut = new Morris.Donut({
      element: 'sales-chart',
      resize: true,
      colors: ["#3c8dbc", "#f56954", "#00a65a"],
      data: [
        {label: "Casual Leave", value: 1},
        {label: "Paid Leave", value: 2},
        {label: "Sick Leave", value: 5}
      ],
      hideHover: 'auto'
    });
     var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [
        {y: '2019-02-01', a: 8.5 },
        {y: '2019-01-31', a: 9.3 },
        {y: '2019-01-30', a: 12 },
        {y: '2019-01-29', a: 4.5 },
        {y: '2019-01-28', a: 9.03 },
        {y: '2019-01-27', a: 9.02 },
        {y: '2019-01-26', a: 8.01 }
      ],
      barColors: ['#3c8dbc'],
      xkey: 'y',
      ykeys: ['a'],
	ymin: 0,
    ymax: 24,
    numLines: 7,
      labels: ['# of Working Hours'],
      hideHover: 'auto'
    });
  });
</script>
</body>
</html>
