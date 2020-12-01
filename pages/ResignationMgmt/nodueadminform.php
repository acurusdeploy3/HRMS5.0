<?php   
session_start();  
$userid=$_SESSION['login_user'];
if(!isset($_SESSION["login_user"]))
{  
    header("location:Mainlogin.php");  
} 
?> 
<?php
/* session_start();
include("functions.php");
if(isset($_SESSION["username"])) {
	if(isLoginSessionExpired()) {
		header("Location:logout1.php?session_expired=1");
	}
} */
?>
<?php

require_once("config.php");
$date = date("Y-m-d");
	if(isset($_GET['resignation_id']) && $_GET['resignation_id'] != ''){
	$resignation_id = $_GET['resignation_id'];}
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$certQuery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,res_id_value,
reason_for_resignation, status,  date_format(date(date_of_leaving),'%d-%b-%Y') as dl,ed.Department,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process' && status='Process Resignation','Exit Process Initiated',
if(status='Process_Completed','Exit Process Completed','')))))))) as queue,
employee_comments,reporting_manager_comments,process_queue
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id where process_queue='HR_Manager_Process' and ei.is_active='Y' and SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1)=$userid
and no_due_sysadmin_status='Y' order by date(date_of_leaving) asc");
$listquery=mysqli_query($db,"SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` where job_role = 'System Admin' and is_active='Y'");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$histquery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds,ed.Department,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process' && status='Process Resignation','Exit Process Initiated',
if(status='Process_Completed','Exit Process Completed','')))))))) as queue,
reason_for_resignation, if(status='Process_Completed','Process Completed',if(status='Cancel Resignation','Resignation Cancelled',status)) as status,  date_format(date(date_of_leaving),'%d-%b-%Y') as dl,
employee_comments,reporting_manager_comments,process_queue
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id where ei.is_active='N' and SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1)=$userid order by date(date_of_leaving) asc");

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resignation Management</title>
  <!-- Tell the browser to be responsive to screen width -->
  
<meta name="viewport" content="width=device-width, initial-scale=0.36, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >  
  <link rel="stylesheet" href="dist/css/w3.css">	
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
  <script src="dist/js/loader.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
th {
  background-color: #fbe2d8;
}
	.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
#faicon
{
    font-size: 30px ! important;
    color: #31607c ! important;
}
#savefields{
	background-color: #4CAF50;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#goprevious{
	background-color: #286090;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#btnhistory{
	background-color: #286090;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
th {
  background-color: #31607c;
  color:white;
}
.modal-backdrop {
    position: unset ! important;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed ! important; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}
/* The Close Button 1  */

.close1 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close1:hover,
.close1:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
#myOverlay{position:absolute;height:100%;width:100%;}
#myOverlay{background:black;opacity:.7;z-index:2;display:none;}

#loadingGIF{position:absolute;top:50%;left:50%;z-index:3;display:none;}
</style>
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
 
 <div class="content-wrapper">
   <section class="content-header">
      <h1>
       ACURUS EMPLOYEE FORM
      <small> Resignation </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
      </ol>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
            <div class="box-header with-border">
              <h3 class="box-title">RESIGNATION INFORMATION</h3>
			  <small> If any </small>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
          
              <div class="box-body">
				</div>
			
			 <div class="box-footer">
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>                
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
		  <h4 style="margin-left: 7px;font-weight: bold;color: green;">ACTIVE RESIGNATIONS</h4>
            <table id="resgnchange" class="table" style="font-size:14px;">

              
                <thead>
                  <th style="width: 10px">#</th>
				  <th>Empl. ID</th>
				  <th style="display:none;">Res. ID</th>
				  <th>Name</th>
				  <th>Department</th>
			      <th>Status</th>
				  <th>View/Edit</th>
				 <?php if($userRole =='System Admin Manager'){
				  echo "<th>Allocation</th>";		}?>		  
                </thead>
				<tbody>
                <?php
                if(mysqli_num_rows($certQuery) < 1){
                  //echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($certQuery)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row['employee_id']."</td>";
					echo "<td class='resgnid' style='width:5%;display:none;'>".$row['resignation_id']."</td>";
					echo "<td style='width:15%'>".$row['Name']."</td>";
					echo "<td style='width:15%'>".$row['Department']."</td>";
					echo "<td style='width:17%'>".$row['queue']."</td>";
				if($row['process_queue'] == 'HR_Manager_Process'){
				  echo "<td><a href='adminnodueprocessingform.php?res_id=".$row['res_id_value']."'><i class='fa fa-check-circle-o' id='faicon'></i></a></td>";}
				  if($userRole == 'System Admin Manager'){
				  echo "<td><a href='#' id='myBtn'  data-toggle='modal' data-target='.modal'><i class='fa fa-user-plus' id='faicon'></i></a></td>";}
				  echo "</tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
            </table>
          </div>
          </div>
        <div class="box box-info" style="width:110%;">
		 <!-- <input type="button" id="btnhistory" name="btnhistory" value="View History of Resignations" style="margin-left: 7px;"  onclick="opendiv();"></input>-->
		  <br><br>
		  <div class="border-class w3-container w3-show" id="historydiv" >
		  <h4 style="margin-left: 7px;font-weight: bold;color: tomato;">RESIGNATION HISTORY</h4>
            <table class="table" style="font-size:14px;width:100%" id="histchange">
              
                <thead>
                  <th style="width: 10px">#</th>
				  <th>Empl. ID</th>
				  <th style="display:none;"></th>
				  <th>Name</th>
				  <th>Department</th>
			      <th>Status</th>  
                </thead>			  
                <tbody>
                <?php
                if(mysqli_num_rows($histquery) < 1){
                  //echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row3 = mysqli_fetch_assoc($histquery)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
					echo "<td style='width:5%'>".$row3['employee_id']."</td>";
					echo "<td style='width:5%;display:none;'>".$row3['resignation_id']."</td>";
					echo "<td style='width:15%'>".$row3['Name']."</td>";
					echo "<td style='width:15%'>".$row3['Department']."</td>";
					echo "<td style='width:17%'>".$row3['queue']."</td></tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
            </table>
          </div>
	
 <div id="myModal" class="modal">
			
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p></p>
						<form id="statuschange" method="POST" action="updatenodueallocation.php">
						<input type="hidden" value="" name="EmpIdvalue2" id="EmpIdvalue2"/>
						<input type="hidden" value="" name="ResIdvalue2" id="ResIdvalue2"/>
						<input type="hidden" value="" name="ScnIdvalue2" id="ScnIdvalue2"/>
						<label for="inputStatusChange" class="col-sm-3 control-label">Allocate to <span class="error">*  </span></label>				
					<select class="form-control" id="no_due_adm_allocated_to" name="no_due_adm_allocated_to" required>			
				<?php
					while($row10 = mysqli_fetch_assoc($listquery))
						{
  				 ?>
					<option value= "<?php echo $row10['admname']." ";?>" ><?php  echo $row10['admname']." "; ?></option> 
				<?php 
				    }
			   	 ?>
		 </select>	
					<br>
					<input id="addStatusChangebtn"  name="AddstatusBtn" type="submit" class = "btn btn-primary" value = "Save"/>
					
						</form>
							</div>
			</div>		  
          </div>
          <!-- /.box -->
		   </div>
   </div>
      <!-- /.row -->
        </section>		  

    </div>
      <!-- /.row -->
        
 
   
  
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

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
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Page script -->

 <script>
  $(function() {
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3,#currentdate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});

	</script>
<script>
$("#savefields").click(function() {
 ajaxindicatorstart("Processing..Please Wait..");
});
	</script>
					<script>
  $(function () {
    //$('#resgnchange').DataTable();
    $('#resgnchange').DataTable();
    $('#exitchange').DataTable();
    $('#listchange').DataTable();
    $('#histchange').DataTable();
  })
</script>
<script>

	$(function() {
  var bid, trid;
  $('#resgnchange tr').click(function() {
       Id = $(this).find('.EmpId').text();
	   ReId = $(this).find('.resgnid').text();
	   scnval='Admin';
		$('#EmpIdvalue2').val(Id);
		$('#ResIdvalue2').val(ReId);
		$('#ScnIdvalue2').val(scnval);
  });
});
	</script>
	<script>

	$(function() {
  var bid, trid;
  $('#histchange tr').click(function() {
       Id = $(this).find('.EmpId').text();
	   ReId = $(this).find('.resgnid').text();
	   scnval='Admin';
		$('#EmpIdvalue2').val(Id);
		$('#ResIdvalue2').val(ReId);
		$('#ScnIdvalue2').val(scnval);
  });
});
	</script>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
	<script>
function opendiv() {
  var x = document.getElementById('historydiv');
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>
</body>
</html>
