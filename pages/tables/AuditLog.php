<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Audit Log</title>
    <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
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
  .date {
	display: block;
	width: 100px;
	height: 70px;
	margin: 0px auto;
	background: #fff;
	text-align: center;
	font-family: 'Helvetica', sans-serif;
	position: relative;
}

.date .binds {
	position: absolute;
	height: 15px;
	width: 60px;
	background: transparent;
	border: 2px solid #999;
	border-width: 0 5px;
	top: -6px;
	left: 0;
	right: 0;
	margin: auto;
}

.date .month {
	background: #3c8dbc;
	display: block;
	padding: 8px 0;
	color: #fff;
	font-size: 10px;
	font-weight: bold;
	border-bottom: 2px solid #333;
	box-shadow: inset 0 -1px 0 0 #666;
}

.date .day {
	display: block;
	margin: 0;
	padding: 10px 0;
	font-size: 14px;
	box-shadow: 0 0 3px #3c8dbc;
	position: relative;
}

.date .day::after {
	content: '';
	display: block;
	height: 100%;
	width: 96%;
	position: absolute;
	top: 3px;
	left: 2%;
	z-index: -1;
	box-shadow: 0 0 3px #ccc;
}

.date .day::before {
	content: '';
	display: block;
	height: 100%;
	width: 90%;
	position: absolute;
	top: 6px;
	left: 5%;
	z-index: -1;
	box-shadow: 0 0 3px #ccc;
}
  </style>
  <style>
#bdays
{
	max-height: 300px;
    overflow-y: scroll;
}
</style>
 <style>
#Holidays
{
	max-height: 470px;
    overflow-y: scroll;
}
</style>
 <style>
#announcements
{
	max-height: 250px;
    overflow-y: scroll;
}
</style>
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>
<style>
.no_birthday {
    background: #fff7ee;
    color: #d8740e;
    min-height: 25px;
    padding-top: 13px;
    padding-bottom: 9px;
    border: 1px solid #d3d3d3;
    text-align: center;
}
</style>
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
<script language="javascript">
i = 0
var speed = 1
function scroll() {
i = i + speed
var div = document.getElementById("announcements")
div.scrollTop = i
if (i > div.scrollHeight - 160) {i = 0}
t1=setTimeout("scroll()",100)
}
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Department', 'Employee %'],
  <?php  
  while($getDeptCountRow = mysqli_fetch_assoc($getDepartment))
  {  
  ?>
	 ['<?php echo $getDeptCountRow['department']; ?>', <?php echo $getDeptCountRow['emp_count']; ?>],
	 
  <?php 
  }
  ?>
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Employee(s), Department Wise', 'width':550, 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
</head>
<body class="hold-transition skin-blue sidebar-mini" onload="scroll()">
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
     Audit Log
      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
       
        <li class="active">Audit Log</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">
		<?php  
		$module = $_GET['id'];
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
                    <button onclick="window.history.go(-1); return false;" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
                  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
               <h4 class="box-title"><strong>Audit Log : <?php echo $module?></strong></h4>
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config2.php");
			session_start();
			$employeeID = $_SESSION['login_user'];
			$employeegrp = $_SESSION['login_user_group'];
			$getTeamCount = mysqli_query($db,"select * from employee_Details where reporting_manager_id='$employeeID'");
			$TeamCount = mysqli_num_rows($getTeamCount);
			if($employeegrp === 'HR' || $employeegrp === 'HR Manager')
			{
			$getData = mysqli_query($db,"select employee_id,module_name,description,action, change_from,change_to,action,modified_date_and_time from audit where module_name='$module' and employee_id !='$employeeID' order by modified_date_and_time desc");
			}
			if ($TeamCount>0 && $employeegrp!='HR Manager')
			{
				$getData = mysqli_query($db,"select employee_id,module_name,description,action, change_from,change_to,action,modified_date_and_time from audit where module_name='$module' and employee_id in (select employee_id from employee_Details where reporting_manager_id='$employeeID') order by modified_date_and_time desc");	
			}
				$getSelfData = mysqli_query($db,"select employee_id,module_name,description,action, change_from,change_to,action,modified_date_and_time from audit where module_name='$module' and employee_id='$employeeID' order by modified_date_and_time desc");	
			$count = mysqli_num_rows($getData);
			$count1 = mysqli_num_rows($getSelfData);
			?>
   </form>        

            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th>Employee ID</th>
			   <th>Modified Field</th>
			    <th>Changed From</th>
                  <th>Changed To</th>
                  
				 
                  <th>Modified Time</th>
                  
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($getDataRow = mysqli_fetch_assoc($getData))
					{
				?>
                <tr>
                  <td><?php echo $getDataRow['employee_id'];  ?></td>
                  <td><?php echo $getDataRow['description'];  ?></td>
				  <td style="color:red;"><?php echo $getDataRow['change_from'];  ?></td>
				   <td style="color:green;"><?php echo $getDataRow['change_to'];  ?></td>               
				  <td><?php echo $getDataRow['modified_date_and_time'];  ?></td>
				 
                </tr>
             
					<?php
                    }
                    }
				if($count1 > 0)
                {
				while ($getSelfDataRow = mysqli_fetch_assoc($getSelfData))
					{
				?>
                <tr>
                  <td><?php echo $getSelfDataRow['employee_id'];  ?></td>
                  <td><?php echo $getSelfDataRow['description'];  ?></td>
				  <td style="color:red;"><?php echo $getSelfDataRow['change_from'];  ?></td>
				   <td style="color:green;"><?php echo $getSelfDataRow['change_to'];  ?></td>
				  <td><?php echo $getSelfDataRow['modified_date_and_time'];  ?></td>
				 
                </tr>
                <?php
					}
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

<!-- page script -->

</body>
</html>
