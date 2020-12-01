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
$date = date("d M Y");
$date1 = date("Y-m-d");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$curShift = mysqli_query($db,"select * from employee_shift where date(now()) between start_date and end_date and employee_id='$userid'");
$curShiftrow = mysqli_fetch_assoc($curShift);

$dsrlist = mysqli_query($db,"select s.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,department,date_format(date,'%d %b %Y') as datelst,date as dt,shift_code,is_sent from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.employee_id='$userid' and s.is_active='Y' and s.is_sent<>'Y' group by date ");

$dsrlisthistory = mysqli_query($db,"select s.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,department,date_format(date,'%d %b %Y') as datelst,date as dt,shift_code,is_sent from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.employee_id='$userid' and s.is_active='Y' and s.is_sent='Y' group by date order by date desc");


$checkiftoday = mysqli_query($db,"select * from dsr_summary where employee_id= '$userid' and date=date(now()) and is_active='Y'");


?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>DSR Report</title>
  <!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=0.36, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="dist/css/w3.css">
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
table{
	 margin-bottom: 0px ! important;
	 table-layout : fixed !important;
}
td,th{
	border:1px solid #00000038 ! important
}
th {
  background-color: #fbe2d8;
}
	.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
.fa-trash{
	font-size: 20px;
    color: tomato;
}
.fa-times{
	font-size: 20px;
}
.fa-plus{
	font-size: 18px;
}
.fa-eye{
	font-size: 20px;
	color:black;
}
.fa-edit{
	font-size:20px;
	color:forestgreen;
}

#faicon
{
    font-size: 30px ! important;
    color: #31607c ! important;
}
#btnApprove{
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
#BtnNewdsr {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 7px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  margin: 10px 0px 20px 20px;
  cursor: pointer;
}
th {
  background-color: #607d8b2e;
  color:black;
}
.modal-backdrop {
    position: unset ! important;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed ! important; /* Stay in place */
   /* z-index: 1;  Sit on top */
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
  .close12 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close12:hover,
.close12:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

#dataleave 
{float: right;
padding-right: 15%;}
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
      <h1>Daily Status Report</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">DSR</a></li>
      </ol>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
          		
			 <div class="box-footer">
                   <input action="action" class="btn btn-info pull-right" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>              
				   <?php
                if(mysqli_num_rows($checkiftoday) < 1){
		  echo "<a href='#' id='BtnNewdsr' name='BtnNewdsr' data-toggle='modal' data-target='#datamodel'>&nbsp;Create DSR&nbsp;</a>";
				} else {}?>
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
		  <form id="dsrcreateform" method="POST">
		  <!--<span class="bg-red">Active DSR</span>-->
            <table id="dsrtable" class="table" style="font-size:14px;width:100%">
                
				<thead>
				<tr>
                <th colspan="1" style="text-align:center">Active DSR</th>
                <th colspan="5" style="background-color:white;border-top: 1px solid white ! important;"></th>
				</tr>
				<tr>
                  <th>#</th>
				  <th>Employee ID</th>
				  <th>Employee Name</th>
				  <th>Department</th>	
				  <th>Date</th>	
				  <th>Action</th>
				 </tr>
				</thead>
             <tbody>
                <?php
                if(mysqli_num_rows($dsrlist) < 1){
                  //echo "<tr><td cols-span='8'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($dsrlist)){
                    echo "<tr id=".$row['employee_id'].'|'.$row['dt'].'|'.$row['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row['employee_id']."</td>";
					echo "<td style='width:15%'>".$row['Name']."</td>";
					echo "<td style='width:15%'>".$row['department']."</td>";
					echo "<td style='width:15%'>".$row['datelst']."</td>";
                    echo "<input type='hidden' class='dl' value='".$row['datelst']."'></input>";
                    echo "<input type='hidden' class='sc' value='".$row['shift_code']."'></input>";
					echo "<input type='hidden' class='emp' value='".$row['employee_id']."'></input>";
					if($row['dt']==$date1 && $row['is_sent']=='N'){
					echo "<td style='width:15%'>
					<a href='#' id='BtnEditdsr' name='BtnEditdsr' class='BtnEditdsr' data-toggle='modal' data-target='#datamodel'>
					<i class='fa fa-edit' aria-hidden='true' id='edit' ></i></a>&emsp;
					<a href='deletetasks.php?wflid=".$row['employee_id'].'|'.$row['dt']."' ><i class='fa fa-times' style='color:tomato;' id='delete'></i></a>
					</td>";
					}
					else {
						}
					echo "</tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
            </table>
			</form>
          </div>
		  <hr style="width:100%;"align="left">
		  <div class="border-class">
		  <!--<h3 style='color:tomato;font-weight:400;'>&emsp;History of DSR</h3>-->
		  <form id="dsrhistform" method="POST">				
		  <br>
            <table id="dsrhisttable" class="table" style="font-size:14px;width:100%">
                <thead>
				<tr>
                <th colspan="1" style="text-align:center">History of DSR</th>
                <th colspan="6" style="background-color:white;border-top: 1px solid white ! important;"></th>
				</tr>
				<tr>
                  <th>#</th>
				  <th>Employee ID</th>
				  <th>Employee Name</th>
				  <th>Department</th>	
				  <th>Date</th>	
				  <th>Shift Code</th>	
				  <th>View</th>
				 </tr>
                </thead>
             <tbody>
                <?php
                if(mysqli_num_rows($dsrlisthistory) < 1){
                  //echo "<tr><td cols-span='8'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($dsrlisthistory)){
                    echo "<tr id=".$row1['employee_id'].'|'.$row1['dt'].'|'.$row1['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row1['employee_id']."</td>";
					echo "<td style='width:15%'>".$row1['Name']."</td>";
					echo "<td style='width:15%'>".$row1['department']."</td>";
					echo "<td style='width:15%'>".$row1['datelst']."</td>";
					echo "<td style='width:15%'>".$row1['shift_code']."</td>";
                    echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
                    echo "<input type='hidden' class='sc' value='".$row1['shift_code']."'></input>";
					echo "<td><a href='#' id='BtnVwdsr' class='BtnVwdsr' name='BtnVwdsr' data-toggle='modal' data-target='#datashowmodel' data-src=''>
					<i class='fa fa-eye' aria-hidden='true' id='view' ></i></a></td>";
					echo "</tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
            </table>
			</form>
          </div>
		  
		  <div id="datamodel" class="modal" role="dialog" aria-hidden="true">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close btn-close" id="closeforrelaod" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="box-title" id="headerText">Create DSR</h4>
						</div>
						<div class="modal-body" style="">
							<form action="" method="post" role="form" id="form" enctype="multipart/form-data">
							<div class="box-body" >
								<div class="col-md-12" id="formbody">
									<div class="col-md-12">
										<div class="form-group">
											<label for="date">Date<span class="text-red">*</span></label>
											<input type="text" class="form-control" id="currentdate" name="currentdate" value="<?php echo $date; ?>"  required disabled	 ></input>
											<input type='hidden' id='employeeid' value="<?php echo $userid; ?>"></input>
											<input type='hidden' id='employeedate' value="<?php echo $date1; ?>"></input>
											<span style="color:tomato"></span>
										</div>
										<div class="form-group">
											<label for="date">Shift<span class="text-red">*</span></label>
											<input type="text" class="form-control" id="EmpShift" name="EmpShift" value="<?php echo $curShiftrow['Shift_Code']; ?>"  required disabled	 ></input>
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="date">Work Progress<span class="text-red">*</span></label>
											<a href="#" id="myBtn" title="Click to Add New Entry" data-toggle="modal" data-target="#modal-default-Education"><i class="fa fa-plus" style="color:forestgreen;" aria-hidden="true" ></i></a>
										</div>
										<div id="myModal" class="modal">
											<div class="modal-content">
												<span class="close12">&times;</span>
												<p>Add new task </p>
												<input type="text"  class="form-control" id="workpg" name="workpg" placeholder="Enter Work Progress" onkeypress='return  checkQuote(event);' maxlength="1000" /><br>
												<input type="button" id="addnewwork"   name="addnewwork"  class = "btn btn-primary"value = "Add Task"></input>
											</div>
									</div>
									</div>
									<div class="col-md-12" id="display">
										
									</div>
								</div>
								&nbsp;
							</div>
							</form>
						</div>	
					</div>
				</div>
				<div id="datashowmodel" class="modal" role="dialog" aria-hidden="true">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close btn-close" id="closeforrelaod" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="box-title" id="headerText">View DSR</h4>
						</div>
						<div class="modal-body" style="">
							<form action="" method="post" role="form" id="form" enctype="multipart/form-data">
							<div class="box-body" >
								<div class="col-md-12" id="formbody">
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="date">Work Progress</label>
										</div>
									</div>
									<div class="col-md-12" id="display1">
										
									</div>									
									</div>
								</div>
								&nbsp;
							</div>
							</form>
						</div>	
					</div>
				</div>
          </div>
          <!-- /.box -->
		   </div>
		   
		      
      <!-- /.row -->
       </section>		  
		</div>
 
       
 
   
  
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
  $(function () {
    //$('#resgnchange').DataTable();
    $('#dsrtable').DataTable();
    $('#dsrhisttable').DataTable();
  });
  
  $('#dsrtable').dataTable( {
  "searching": false,
  "paging":   false,
   "info":     false,
} );
</script>

	<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

var modal1  = document.getElementById('datamodel');
var modal2  = document.getElementById('datashowmodel');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
	if(event.target == modal1){
		$(modal1).modal({
			backdrop: 'static',
			keyboard: false
		})
	}
	if(event.target == modal2){
		$(modal2).modal({
			backdrop: 'static',
			keyboard: false
		})
	}
}
</script>
<script>
    $(function() {
  var bid, trid;
   $('#dsrtable tbody').on('click', 'tr', function (){
       Id = $(this).attr('id');
	   dl = $(this).find('.dl').val();
       sc = $(this).find('.sc').val();
		$('#BtnVwdsr').attr('data-src',Id);
		$('#currentdate1').val(dl);
        $('#EmpShift1').val(sc);
  });
});
</script>

  <script type="text/javascript" language="javascript">
  $('#closeforrelaod').on('click', function(e) {
	  ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  });
		function checkQuote(evt) {	
		var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode == 34)
             return false;
          return true;
		}
	</script>

	<script>
$('.BtnVwdsr').click(function(){
ajaxindicatorstart("Please Wait..");
 var modalleave=document.getElementById('BtnVwdsr').dataset.target;
 $("#display").empty();
 var tr=$(this).closest("tr");
var wflid=tr[0].id;
      $.ajax({
               type: "POST",
               url: "mytasksview.php",
               data: {
                   wflid: wflid,
               },
               success: function(html) {
                   $("#display1").html(html).show();
					ajaxindicatorstop();
               }
      });
});
</script>


	<script>
	$(document).ready(function(e) {
	$("#addnewwork").on('click', function(e) {
	var strength = $('#workpg').val();
	var employeeid = $('#employeeid').val();
	var shiftcode = $('#EmpShift').val();
	//ajaxindicatorstart("Please Wait..");
	e.preventDefault();
 	$.ajax({
        url: "mytasks.php",
		type: "POST",
		data: {
			strength:strength,
			employeeid:employeeid,
			shiftcode:shiftcode,
		},
		success: function(html) {
			// location.reload();
			//$('#myModal').modal('toggle');
			document.getElementById('workpg').value="";
			document.getElementById('myModal').style.display='none';
			$("#display").html(html).show();
		}
});
});
$("#BtnNewdsr").on('click',function(e){
	$.ajax({
	url: "mytasks.php",
	type: "POST",
	success: function(html) {
		$("#display").html(html).show();
	}
	});
	});
	});
	</script>
	
	<script>
	$(".BtnEditdsr").click(function(){
		var tr=$(this).closest("tr");
		var employeeid=tr[0].id.split('|')[0];
	$.ajax({
	url: "mytasks.php",
	type: "POST",
	data: {
			employeeid:employeeid,
		},
	success: function(html) {
		$("#display").html(html).show();
		$('#datamodel').modal({
    backdrop: 'static',
    keyboard: false
})
	}
	});
});
	</script>

</body>
</html>
