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
        Resource Change Request(s)
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	<form id="resForm" method="post">
      <div class="row">
        <div class="col-xs-12">
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
               <h4 class="box-title">Change Request:<strong> Reporting Manager</strong></h4>
			  <br>
			  
			  
            </div>
		 <br>
            
             
              
			<?php
			include("config.php");
			session_start();
			$UserId = $_SESSION['login_user'];
			$Usergrp = $_SESSION['login_user_group'];
			$getFirstLineLookup = mysql_query("select * from pms_manager_lookup where manager_id='$UserId'");
			if(mysql_num_rows($getFirstLineLookup)>0)
			{
				$isFirstLine='Y';
			}
			
			if($isFirstLine=='Y' && $Usergrp!='HR Manager')
			{
			$query = mysql_query("select r.id,raised_by,raised_for,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, concat(b.first_name,' ',b.last_name,' ',b.mi) as Manager,
concat(c.first_name,' ',c.last_name,' ',c.mi) as New_Reporting,concat(d.first_name,' ',d.last_name,' ',d.mi) as Current_reporting,
raised_request,new_value,old_value,reason_for_change,status,r.remarks
from
resource_change_request r  inner join employee_details a on r.raised_for=a.employee_id
inner join employee_details b on r.raised_by=b.employee_id
inner join employee_details c on r.new_value=c.employee_id
inner join employee_details d on r.old_value=d.employee_id where raised_request='Reporting Manager Change' and raised_by='$UserId' and r.is_active='Y' ");
			}
			else
			{
				$query = mysql_query("select r.id,raised_by,raised_for,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, concat(b.first_name,' ',b.last_name,' ',b.mi) as Manager,
concat(c.first_name,' ',c.last_name,' ',c.mi) as New_Reporting,concat(d.first_name,' ',d.last_name,' ',d.mi) as Current_reporting,
raised_request,new_value,old_value,reason_for_change,status,r.remarks
from
resource_change_request r  inner join employee_details a on r.raised_for=a.employee_id
inner join employee_details b on r.raised_by=b.employee_id
inner join employee_details c on r.new_value=c.employee_id
inner join employee_details d on r.old_value=d.employee_id where raised_request='Reporting Manager Change' and r.is_active='Y' and r.status not in ('Approved','Denied')");
			}
				$count = mysql_num_rows($query);
			?>
   </form>        

            <div class="box-body">
			<?php
			if($isFirstLine=='Y' && $Usergrp!='HR Manager')
			{
			?>
               <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th>Raised For</th>
                  <th>Current Reporting Manager</th>
				  <th>Requested Reporting Manager</th>
				  <th>Status</th>
				  <th>Comments from HR</th>
                  <th>Cancel Request</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
				?>
                <tr>
                  <td><?php echo $Employees['name'].' : '.$Employees['raised_for'];  ?></td>
                  <td><?php echo $Employees['Current_reporting'];  ?></td>
                  <td><?php echo $Employees['New_Reporting'];  ?></td>
                  <td><?php if($Employees['status']=='Approved') { echo '<span class="badge bg-green">Approved</span>';} elseif($Employees['status']=='Denied') { echo '<span class="badge bg-red">Denied</span>';} else { echo '<span class="badge bg-yellow">Request under Process</span>';}  ?></td>
				<td><?php echo $Employees['remarks'];  ?></td>
				<?php
				if($Employees['status']=='Request under Process')
				{
				?>
				<td><a href="CancelRequest.php?id=<?php echo $Employees['id'];?>" onclick="return confirm('Sure to Cancel Request?')" class="ApproveClass"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Deny Leave" width='18px' height='18px' /></a></td>
                <?php
				}
				else
				{
				?>
				<td>--</td>
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
			  <?php
			}
			if($Usergrp=='HR Manager' || $Usergrp=='HR')
			{
			  ?>
			  
			  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="display:none;">Raised By</th>
                  <th>Raised By</th>
				  <th>Raised For</th>
                  <th>Current Reporting Manager</th>
				  <th>Requested Reporting Manager</th>
                  <th>Approve</th>
                  <th>Deny</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query))
					{
				?>
                <tr>
                  <td style="display:none;" class="ReqID"><?php echo $Employees['id'];  ?></td>
                  <td><?php echo $Employees['Manager'];  ?></td>
                  <td class="RaisedforRM"><?php echo $Employees['name'].' : '.$Employees['raised_for'];  ?></td>
                  <td class="OldRM"><?php echo $Employees['Current_reporting'];  ?></td>
                  <td class="NewRM"><?php echo $Employees['New_Reporting'];  ?></td>
                  <?php
                if($Usergrp=='HR Manager' )
				{
                ?>
				<td><a href="ApproveChangeRM.php?id=<?php echo $Employees['id'];?>&NewVal=<?php echo $Employees['new_value']; ?>&EmpID=<?php echo $Employees['raised_for']; ?>" class="ApproveClass"><img alt='User' src='../../dist/img/Tick_Mark_Dark-512.png' title="Approve" width='18px' height='23px' /></a></td>
				<td><a href="#" id="NoReported" data-toggle="modal" data-target="#modal-default"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Deny Leave" width='18px' height='18px' /></a></td>
             	<?php
                }
                else
                {
                ?>
                <td>NA</td>
                <td>NA</td>
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
			  <?php
			}
			  ?>
			<br>
<h4 class="box-title">Change Request:<strong> Department</strong></h4>
<?php
			include("config.php");
			session_start();
			$UserId = $_SESSION['login_user'];
			$Usergrp = $_SESSION['login_user_group'];
			$getFirstLineLookup = mysql_query("select * from pms_manager_lookup where manager_id='$UserId'");
			if(mysql_num_rows($getFirstLineLookup)>0)
			{
				$isFirstLine='Y';
			}
			if($isFirstLine=='Y' && $Usergrp!='HR Manager')
			{
			$query1 = mysql_query("select r.id,raised_by,raised_for,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, concat(b.first_name,' ',b.last_name,' ',b.mi) as Manager,
raised_request,new_value,old_value,reason_for_change,r.remarks,r.status
from
resource_change_request r  inner join employee_details a on r.raised_for=a.employee_id
inner join employee_details b on r.raised_by=b.employee_id
 where raised_request='Department Change' and raised_by='$UserId' and r.is_active='Y';");
			}
			else
			{
					$query1 = mysql_query("select r.id,raised_by,raised_for,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, concat(b.first_name,' ',b.last_name,' ',b.mi) as Manager,
raised_request,new_value,old_value,reason_for_change,r.remarks
from
resource_change_request r  inner join employee_details a on r.raised_for=a.employee_id
inner join employee_details b on r.raised_by=b.employee_id
 where raised_request='Department Change' and r.is_active='Y' and r.status not in ('Approved','Denied');");
			}
				$count = mysql_num_rows($query1);
			?>
   </form>        

            <div class="box-body">
			<?php
			if($isFirstLine=='Y' && $Usergrp!='HR Manager')
			{
			?>
           <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th>Raised For</th>
                  <th>Current Department</th>
				  <th>Requested Department</th>
				  <th>Status</th>
				  <th>Comments from HR</th>
                  <th>Cancel Request</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query1))
					{
				?>
                <tr>
                  <td><?php echo $Employees['name'].' : '.$Employees['raised_for'];  ?></td>
                  <td><?php echo $Employees['old_value'];  ?></td>
                  <td><?php echo $Employees['new_value'];  ?></td>
                  
                  <td><?php if($Employees['status']=='Approved') { echo '<span class="badge bg-green">Approved</span>';} elseif($Employees['status']=='Denied') { echo '<span class="badge bg-red">Denied</span>';} else { echo '<span class="badge bg-yellow">Request under Process</span>';}  ?></td>
				<td><?php echo $Employees['remarks'];  ?></td>
				<?php
				if($Employees['status']=='Request under Process')
				{
				?>
				<td><a href="CancelRequest.php?id=<?php echo $Employees['id'];?>" onclick="return confirm('Sure to Cancel Request?')" class="ApproveClass"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Cancel" width='18px' height='18px' /></a></td>
                <?php
				}
				else
				{
				?>
				<td>--</td>
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
			  <?php
			}
			if($Usergrp=='HR Manager' || $Usergrp=='HR')
			{
			  ?>
			   
			     <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  
				  <th style="display:none;">Raised By</th>
				  <th>Raised By</th>
				  <th>Raised For</th>
                  <th>Current Department</th>
				  <th>Requested Department</th>
                  <th>Approve</th>
                  <th>Deny</th>
                
                </tr>
                </thead>
				
                <tbody>
				<?php
				if($count > 0){
				while ($Employees = mysql_fetch_assoc($query1))
					{
				?>
                <tr>
                  <td style="display:none;" class="DeptID"><?php echo $Employees['id'];  ?></td>
                  <td><?php echo $Employees['Manager'];  ?></td>
                  <td class="RaisedForDEPT"><?php echo $Employees['name'].' : '.$Employees['raised_for'];  ?></td>
                  <td class="OldDept"><?php echo $Employees['old_value'];  ?></td>
                  <td class="NewDept"><?php echo $Employees['new_value'];  ?></td>
                <?php
                if($Usergrp=='HR Manager' )
				{
                ?>
				<td><a href="ApproveChange.php?id=<?php echo $Employees['id'];?>&NewVal=<?php echo $Employees['new_value']; ?>&EmpID=<?php echo $Employees['raised_for']; ?>" class="ApproveClass"><img alt='User' src='../../dist/img/Tick_Mark_Dark-512.png' title="Approve" width='18px' height='23px' /></a></td>
				<td><a href="#" id="NoReported" data-toggle="modal" data-target="#modal-default-dept"><img alt='User' src='../../dist/img/remove-icon-png-8.png' title="Deny" width='18px' height='18px' /></a></td>
               <?php
                }
                else
                {
                ?>
                <td>NA</td>
                <td>NA</td>
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
			  <?php
			}
			  ?>
            </div>
            <!-- /.box-body -->
          </div>
		  <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reporting Manager Change Request</h4>
              </div>
              <div class="modal-body">
				 <div class="box box-info">
           <?php

		   ?>
            <form id="RemoveFormRM" action="" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
		   <div class="form-group">

			  <input type="hidden" name="RMID" id="RMID" />
			 <h5>Requested For : <label id="RaisedForRM"><strong></strong> </label></h5>
			 <br>
	

              </div>
			 <div class="form-group">
			 <h5>Change To: <label id="NewReport"><strong></strong> </label></h5>
			 <br>
	

              </div>

            </div>
			 <div class="col-md-6">
			
			  <div class="form-group">
			 <h5>Change From : <label id="OldReport"><strong></strong> </label></h5>
			 <br>
	

              </div>
              <div class="form-group">
			 <label>Reason for Denial</label>
			 <br>
	
                 <textarea name="reason" id="reason" rows="5" cols="40" maxlength="70" class="is-maxlength" required="required" style="width: 100%;" required></textarea>
              </div>
			
		</div>
            </div>
           

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>	
            </div>
            
            <div class="modal-footer">
                <button type="button" id="closeRole" onclick="clearFields1();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SubmitData" value="Deny Request" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
		  
		  
		
		
		<div class="modal fade" id="modal-default-dept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Department Change Request</h4>
              </div>
              <div class="modal-body">
				 <div class="box box-info">
           <?php

		   ?>
            <form id="RemoveForm" action="" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
		   <div class="form-group">

			  <input type="hidden" name="DeptID" id="DeptID" />
			 <h5>Requested For : <label id="RaisedForDEPT"><strong></strong> </label></h5>
			 <br>
	

              </div>
			 <div class="form-group">
			 <h5>Change To: <label id="NewDept"><strong></strong> </label></h5>
			 <br>
	

              </div>

            </div>
			 <div class="col-md-6">
			
			  <div class="form-group">
			 <h5>Change From : <label id="OldDept"><strong></strong> </label></h5>
			 <br>
	

              </div>
              <div class="form-group">
			 <label>Reason for Denial</label>
			 <br>
	
                 <textarea name="reason" id="reason" rows="5" cols="40" maxlength="70" class="is-maxlength" required="required" style="width: 100%;" required></textarea>
              </div>
			
		</div>
            </div>
           

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>	
            </div>
            
            <div class="modal-footer">
                <button type="button" id="closeRole" onclick="clearFields1();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SubmitData" value="Deny Request" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
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
<script>

	$(function() {
  var bid, trid;
  $('#example1 tr').click(function() {
       Id = $(this).find('.ReqID').text();
       name = $(this).find('.RaisedforRM').text();
       NewRM = $(this).find('.NewRM').text();
       OldRM = $(this).find('.OldRM').text();
		$('#RMID').val(Id);
		$('#RaisedForRM').text(name);
		$('#NewReport').text(NewRM);
		$('#OldReport').text(OldRM);
  });
});
	</script>
	<script>

	$(function() {
  var bid, trid;
  $('#example2 tr').click(function() {
       Id = $(this).find('.DeptID').text();
       name = $(this).find('.RaisedForDEPT').text();
       NewDept = $(this).find('.NewDept').text();
       OldDept = $(this).find('.OldDept').text();
		$('#DeptID').val(Id);
		$('#RaisedForDEPT').text(name);
		$('#NewDept').text(NewDept);
		$('#OldDept').text(OldDept);
  });
});
	</script>
	<script>
$('.ApproveClass').on('click', function() {
	ajaxindicatorstart("Please Wait..");
	location.reload();
	});
</script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable()
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
$(document).ready(function() {
    $("#RemoveForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#RemoveForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "DenyRequestDepartment.php",
         success: function(data){
		location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	<script type="text/javascript">
$(document).ready(function() {
    $("#RemoveFormRM").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#RemoveFormRM").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "DenyRequest.php",
         success: function(data){
		location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	</body>
</html>
