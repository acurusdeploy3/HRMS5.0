<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Employee Search</title>
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
<style>
.astrick
{
	color:red;
}
</style>
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
        Employee Search
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li ><a href="#">Employee Info</a></li>
        <li class="active">Employees in Acurus</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">
		<?php  
		$dept = $_GET['id'];
		?>
		<?php 
			include("config.php");
		
			?>
               <!-- /.box -->
		<div class="box">
		<?php session_start();
		if($_SESSION['smessage'] != ''){ ?>
                          <div class="alert alert-success alert-dismissible custom-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $_SESSION['smessage']; ?>
                          </div>
                          <?php 
						  $_SESSION['smessage']='';
						  } ?>
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
               <h4 class="box-title"><strong>Employee(s) in Acurus</strong></h4>
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config.php");
			session_start();
			$_SESSION['fromSearchEmployee']='Y';
         
 $_SESSION['searchBack']='SearchEmployee.php';
			$userid = $_SESSION['login_user'];
			$query = mysql_query("select employee_id,concat(first_name,' ',last_name,' ',mi) as name,Employee_designation,
if(date_joined<>'0001-01-01',TIMESTAMPDIFF(MONTH,date_joined,now()),'--') as Months,department,substring_index(official_email,'@',-1) as mail_type from employee_details where is_active='Y'
AND employee_id!='$userid' and employee_id not in (3)");
				$count = mysql_num_rows($query);
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
                  <th>Months in Acurus</th>
                  <th>View</th>
                  <th>Deactivate</th>
                  <th>Mail Change</th>
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
						$empidval =$Employees['employee_id'];
				?>
                <tr>
                  <td class="EmpID"><?php echo $empidval;   ?></td>
                  <td><?php echo $Employees['name'];  ?></td>
                  <td><?php echo $Employees['Employee_designation'];  ?></td>
                  <td><?php echo $Employees['department'];  ?></td>
				  <td><?php echo $Employees['Months'];  ?></td>
				  <td> <a href="../../searchMydetails.php?empId=<?php echo $Employees['employee_id'] ?>"><img alt='User' src='../../dist/img/view.png' width='18px' height='18px' /></a></td>
                  <td> <a href="#" class="RequestChange" id="RaiseChangeRequest" data-toggle="modal" data-target="#modal-default-Deactivate"><img alt='User' src='Images/notrep.png' width='18px' height='18px' /></a></td>
				  <?php 
				  if ($Employees['mail_type'] == 'intramail.acurussolutions.com') 
				  { 
						$query1 = mysql_query("SELECT status,is_active,raised_for FROM `employee_data_change_request` a  where a.raised_for='$empidval' and is_active='Y'");
						$checkemp = mysql_fetch_array($query1);
						if($empidval == $checkemp['raised_for'] && $checkemp['status']=='Requested')
						{
						?>
							<td><span>Requested</span></td>
						<?php	
						} else { ?>
						<td> <a href="SendMailChangeRequest.php?emplid=<?php echo $Employees['employee_id'] ?>" id="sendmail" >Request</a></td>
						<?php }
					} 
					else
					{
						echo "<td>NA</td>";
					} ?>
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
			  <button OnClick="window.location='SearchDocument.php'" type="button" class="btn btn-primary pull-left">Search Employee Documents</button> 
            </div>
        
            <!-- /.box-body -->
          </div>
      
          <!-- /.box -->
        </div>
    <div class="modal fade" id="modal-default-Deactivate">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Deactivate Employee</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
            <form id="DeactivateForm" autocomplete="off" method="post" action="">
        <div class="box-body">
          <div class="row">
			<br>
			 <input type="hidden" name="EmplID" id="EmplID" />
		  <div class="col-md-6">
              <div class="form-group">
                 <label>Status<span class="astrick">*</span> </label>
             <select class="form-control select2" id="Status" name="Status" style="width: 100%;" required>
                 <option value="" selected >Please Select from Below</option>
                 <option value="Un-Authorised Absence" >Un-Authorised Absence</option>
                 <option value="Terminated" >Terminated</option>
                 <option value="Resigned" >Resigned</option>
                 <option value="Relieved" >Relieved</option>
				 </select>
            </div>
		</div>
            <div class="col-md-6">
              <div class="form-group">
			    <label>Last Date of Working<span class="astrick">*</span> </label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
				  
                  <input type="text" name="LastDate" class="form-control pull-right" required="required" id="datepicker" placeholder="Pick a date" autocomplete="off" required>
				
                </div>
				</div>
		</div>
		<br>
		<div class="col-md-12">
		<div class="form-group">
                 <label>Reason for Deactivation<span class="astrick">*</span> </label>
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
				  <input type="submit"  id="SendRequest" value="Deactivate Employee" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
    <div class="modal fade" id="modal-default-aeds">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Employee Deactivation</h4>
              </div>
            <div class="modal-body">
                <p>Employee Deactivated Successfully!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
		   <a href="#" id="btnaeds" style="display:none;" data-toggle="modal" data-target="#modal-default-aeds" class="btn btn-danger pull-right">Skip Upload</a>
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
<!-- SlimScroll -->
<!-- FastClick -->
<!-- AdminLTE App -->
<script type="text/javascript">
  $(document).ready(function() {
	  $("#DeactivateForm").submit(function(e) {
      debugger;
      e.preventDefault();
    ajaxindicatorstart("Please Wait..");
	var data = $("#DeactivateForm").serialize();

	$.ajax({
         data: data,
         type: "post",
         url: "DeactivateEmployee.php",
         success: function(data)
		 {
			$('#btnaeds').click();
			ajaxindicatorstop();
         }
});
});
});
    </script>
<script>
function pagereload()
{
			location.reload();
			 ajaxindicatorstop();
}
</script>
	<script>

	$(function() {
  var bid, trid;
  $('#example1 tr').click(function() {
       Id = $(this).find('.EmpID').text();
		$('#EmplID').val(Id);
  });
});
	</script>
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
<script type="text/javascript">
jQuery(document).ready(function($) {
		$('.active').removeClass('menu-open');
		$('.active').removeClass('active');
        $('#EmployeeInfo').addClass('active');
  });
</script>

</body>
</html>
