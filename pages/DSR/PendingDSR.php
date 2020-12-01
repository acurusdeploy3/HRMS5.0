<?php   
session_start();  
$userid=$_SESSION['login_user'];
if(!isset($_SESSION["login_user"]))
{  
    header("location:Mainlogin.php");  
} 
?> 

<?php

require_once("config.php");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$list=mysqli_query($db,"select date,date_format(s.date,'%d %b %Y') as dt from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.tl_id='$userid' and  s.is_active='Y' and  s.is_submitted='N' group by date
UNION
select date ,date_format(s.date,'%d %b %Y') as dt from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.manager_id='$userid' and s.is_active='Y' and s.is_submitted='N' group by date order by date desc");


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
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script> 


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
	color:#3c8dbc;
}

th {
  background-color: #607d8b2e;
  color:black;
}
#faicon
{
    font-size: 30px ! important;
    color: #31607c ! important;
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
#dsrcreateform,#dsrhistform{
	padding-left: 2%;
    padding-right: 2%;
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
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 97%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc; 
}
.panel {
  padding: 0 22px;
  display: none;
  background-color: white;
  overflow: hidden;
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

require_once("config.php");
?>
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
						<input action="action" class="btn btn-info pull-left" onclick="location.href='MyDSRReports.php'" type="button" value="Back" id="goprevious"/>   <!--<h3 style='color:forestgreen;font-weight:400;'>&emsp;Active DSR</h3>-->
					</div>
				
				<div class="border-class">
						<form id="dsrcreateform" method="POST">	
						  <table id="dsrlisttable" class="table" style="font-size:14px;width:100%">
						  <thead>
							<tr>
							<th colspan="1" style="text-align:center">Pending DSR</th>
							<th colspan="6" style="background-color:white;border-top: 1px solid white ! important;border-right: 1px solid white ! important"></th>
							</tr>
							<tr>
							  <!--<th colspan="7" onclick="w3.sortHTML('#dsrlisttable', '.item', 'td:nth-child(1)')" style="cursor:pointer" >Date<a href="#" ><i class="fa fa-sort pull-right" aria-hidden="true" ></i></a></th>-->
							  <th colspan="7">Date</th>
							 </tr>
							</thead>
						  <tbody>
						  <?php if(mysqli_num_rows($list) < 1){
							  echo "<tr><td cols-span='8'>No data available in table</td></tr>";
										}else{
											while($row = mysqli_fetch_assoc($list)){ ?>
							<tr class="item"><td colspan="7">
							<button type="button" class="accordion" title='<?php echo $row['date']?>'><?php echo $row['dt']?>
							</button>&nbsp;
						  <div class="panel">
								<table id="dsrtable" class="table" style="font-size:14px;width:100%">
									<thead>
									  <th style="width: 50px">#</th>
									  <th>Employee ID</th>
									  <th>Employee Name</th>  	
									  <th>Date</th>
									  <th>Department</th>
									  <th>Status</th>
									  <th>Select&emsp;
									  <input type='checkbox' onchange="callFunction()" class="selectcheckBoxAll" name='selectalll' value=''></th>
									</thead>
									<tbody>
									
							<?php $dsrlist = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,s.status,is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where d.reporting_manager_id='$userid' and s.is_active='Y'and is_submitted='N' and is_approved='N' and date='".$row['date']."'and s.employee_id not in(select employee_id from leave_request where (employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and is_active='Y' and reason<>'WFH' and '".$row['date']."'between leave_from and leave_to and number_of_days >='1') or (employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and is_approved='Y' and reason<>'WFH'  and '".$row['date']."'between leave_from and leave_to and number_of_days >='1')) ");
									if(mysqli_num_rows($dsrlist) < 1){
										
									}else{
									  $i = 1;
									  while($row1 = mysqli_fetch_assoc($dsrlist)){
								
										echo "<tr><td style='width:05%'>".$i.".</td>";
										echo "<td style='width:5%' class='empid' id='empid'>".$row1['employee_id']."</td>";
										echo "<td style='width:15%'>".$row1['Name']."</td>";
										echo "<td style='width:15%'>".$row1['datelst']."</td>";
										echo "<td style='width:15%'>".$row1['department']."</td>";
										echo "<td>";
										echo "<select class='status' name='status".$row1['employee_id']."' id='status".$row1['employee_id']."'>";
										echo '<option value="WFO">WFO</option>';	
                                        echo '<option value="NWFH">NWFH</option>';
										
										echo "</select>";
                                        echo "</td>";
										echo "<td><input type='checkbox' id='checkme' class='selectcheckBox' onchange='callFunction()' name='selectvalues[]' value='".$row1['employee_id'].'|'.$row1['dt']."'></td>";
										
										echo "</tr>";
										$i++;
									  }
									  }
									
									?>
									
							</tbody>
							</table>
							<input type='submit' class='btn btn-info pull-right approveselected' class='approveselected' id='approveselected' value='Save Changes' disabled></input>
							</div>
							<?php }} ?>
							</table>
							</form>
							</div>
						</td></tr>
							
						
						</div>
						</div>
						</div>			 
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

<script src="https://www.w3schools.com/lib/w3.js"></script>
<!-- Page script -->
<script>
var checker = document.getElementById('checkme');
 var sendbtn = document.getElementById('approveselected');
 // when unchecked or checked, run the function
 checker.onchange = function(){
if(this.checked){
    sendbtn.disabled = false;
} else {
    sendbtn.disabled = true;
}

}
</script>
<script>
function callFunction() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);

  document.querySelectorAll('input[type="submit"]')[0].disabled = true;
  if (checkedOne) {
    document.querySelectorAll('input[type="submit"]')[0].disabled = false;
  }
}
</script>
<script>
$('.selectcheckBoxAll').click(function() {
    if ($(this).is(':checked')) {
		var table = $(this)[0].closest('table');
		$('td input:checkbox',table).prop('checked',true);
	   var button = $(this)[0].closest('table').parentNode.children[2];
	   $(button).removeAttr("disabled");
    } else {
		var table = $(this)[0].closest('table');
		$('td input:checkbox',table).prop('checked',false);
	   var button = $(this)[0].closest('table').parentNode.children[2];
    	$(button).attr('disabled',true);
    }
});
</script>

<script>
$('.dsrtable').click(function () {
	$('.dsrtable tr').each(function () {
		if ($(this).find('.selectcheckBox').prop('checked')) {
			doEnableButton = true;
            		//return false;
		}
		else{
			doEnableButton = false;
        }
		if (doEnableButton == false) {   
            var button = $(this)[0].closest('table').parentNode.children[2];
			$(button).attr('disabled',true);
            }
        else {
			var button = $(this)[0].closest('table').parentNode.children[2];
			$(button).removeAttr("disabled");
			return false;
        }
    });
    });
</script>

<script>
$('.approveselected').on('click', function() {
debugger;
	ajaxindicatorstart("This might take a while..");
	//var table = $(this)[0].closest('table');
	var data = $("#dsrcreateform").serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "PendingInsert.php",
         success: function(data)
		 {
			// debugger;         
			location.reload();
			//ajaxindicatorstop();
         }
});
	});

</script>
<script>
$('#btnFinish').click(function(e){
	if(!confirm('Are you sure you want to change the DSR to the employee?')){
		e.preventDefault();
	}
	else{
		ajaxindicatorstart('Processing... Please Wait....');
		var formData = $('#form').serialize();
		var saveid1 = $('#employeeid').val();
		var dateval1= $('#employeedate').val();
		$.ajax({
			url:"confirmTasks.php",
			cache: false,
			dataType: "json",
			type: "POST",
			data: formData,
			success: function() {
			}
			});
		location.reload();
	}
});

 $('#closeforrelaod').on('click', function(e) {
	  ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  });
</script>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>
</body>
</html>
