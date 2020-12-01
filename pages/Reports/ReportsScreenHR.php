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
require_once("odadetails.php");
date_default_timezone_set('Asia/Kolkata');

$rptname='';
$date = date("Y-m-d");
$date1 = date("d-M-Y");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image,Department from employee_details where employee_id=$userid");

$rptdropdown = mysqli_query($db,"SELECT Report_Name FROM `report_allocation_table` a
inner join report_master m on a.report_master_id=m.report_master_id
where Allocated_employee_id=$userid and a.is_active='Y' and m.is_active='Y'
union
select Report_Name as Allocated_employee_id from report_master m where m.report_classification in ('ALL_ATTENDANCE') and m.is_active='Y';");

$locationdropdown = mysqli_query($db,"select * from all_business_units where country='India'");

$deptdropdown = mysqli_query($db,"SELECT * FROM `all_departments`");

$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$userDept = $useridrow['Department'];

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Reports</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
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
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
#GenerateButton{
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
#ResetBtn{
	background-color: #d9534f;
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
	border-color:#d43f3a;
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
a:hover {
   cursor: pointer;	
}
a {
	color: forestgreen;
}

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
      <h1>Report Generation

		<input action="action" class="btn btn-info pull-right" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>       </h1>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11" style="width:100% ! important">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
            <form class="form-horizontal" id="reportform" method="post" onsubmit="return false" enctype="multipart/form-data" >
			
              <div class="box-body">
				  <div class="form-group">
				<label for="inptrpt" class="col-sm-2 control-label">Report Name</label>
				<input type="hidden" value="<?php echo $date1; ?>" id="curdate"/>
				<div class="col-sm-5">
                 <select class="form-control" name="report_name" id="report_name" onchange ='showfields(this.value)'> 
					<option value="0">Select One Report</option>
					<?php
                    while($row1 = mysqli_fetch_assoc($rptdropdown))
					{
					?>
					
			<option value="<?php echo $row1['Report_Name']; ?>"><?php echo $row1['Report_Name']; ?></option>
					<?php
                    }
                    ?>
					</select>
					<input type="hidden" value="" name="idvalue" id="idvalue"/>
					</div>	
					<label for="inptdpt" class="col-sm-2 control-label" id="dptname">Department</label>
					<div class="col-sm-3">
					 <select class="form-control" name="dept_name" id="dept_name" disabled> 
					<option value="<?php echo $userDept;?>"><?php echo $userDept;?></option>
					<?php
                    while($row3 = mysqli_fetch_assoc($deptdropdown))
					{
					?>
					
			<option value="<?php echo $row3['department']; ?>"><?php echo $row3['department']; ?></option>
					<?php
                    }
                    ?>
					</select>
					</div>
					</div>	
					<div class="form-group">	
					<label for="frmdt" class="col-sm-2 control-label">From Date</label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker" id="datepicker"  autocomplete="off" >
                  </div>
					<label for="frmdt" class="col-sm-2 control-label">To Date</label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker1" id="datepicker1" autocomplete="off" >
                  </div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Data Filter</label>
					<div class="col-sm-4">
                 	 <input type="text" id="search" placeholder="Search" autocomplete="off" class="form-control" disabled> </input>
					  <div id="display"></div>
					</div>
					<div class="col-sm-1">
					<a onclick="clearsearch();"><i class="fa fa-trash" style="color:tomato;font-size: 20px;" aria-hidden="true"></i></a>
					</div>
					<label class="col-sm-1 control-label" style='display:none;' id='loclab'>Location</label>
					<div class="col-sm-3">					   
					   <select class="form-control" name="loc_name" id="loc_name" style='display:none;'> 
					<!-- <option value="Select One Report">Select One Report width="900"</option> -->
					<?php
                    while($row2 = mysqli_fetch_assoc($locationdropdown))
					{
					?>
					
			<option value="<?php echo $row2['Value']; ?>"><?php echo $row2['business_unit']; ?></option>
					<?php
                    }
                    ?>
					</select>
					</div>
					</div>
					<?php  if(mysqli_num_rows($rptdropdown) < 1)		{?>
				  <div class="box-footer">
				<input type="Button" id="ResetBtn" value="Clear All" name="ResetBtn"  class="btn btn-info pull-right" onclick="clearfields();" ></input> 
				  <input type="Submit" id="GenerateButton" value="Generate" name="GenerateButton" class="btn btn-info pull-right" onclick="openbirtreport();" style = "margin-right: 7px;" disabled></input>
				  </div> 
					<?php } else { ?>
					<div class="box-footer">					
				  <input type="Button" id="ResetBtn" value="Clear All" name="ResetBtn" class="btn btn-info pull-right" onclick="clearfields();" ></input> 
				  <input type="Submit" id="GenerateButton" value="Generate" name="GenerateButton" class="btn btn-info pull-right" onclick="openbirtreport();" style = "margin-right: 7px;"></input>  
				  </div>
					<?php } ?>
				  
				  <div class="form-group" id="iframeid" style="display:none;">
				  <iframe id="reportframe" style = "margin-left:1%;width: -webkit-fill-available;overflow-x: scroll;overflow-y: scroll; height:600px;" src=""></iframe>
				    </div>
				</div>
			
			 <div class="box-footer">
                
              </div>
              <!-- /.box-footer -->			  		    
	</form>
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
<!-- Page script -->
	
<?php
require_once('Layouts/documentModals.php');
?>
<script>
  $(function() {
  $("#datepicker").datepicker({ 
        changeMonth: true,
        changeYear: true,
		autoclose:true,
		maxDate: +0
  });
    $("#datepicker1").datepicker({ 
        changeMonth: true,
        changeYear: true,
		autoclose:true,
		maxDate: +0,
  });
});
function openbirtreport()
{
	document.getElementById('iframeid').style.display = "block"; 
}
</script>

<script>
	$("#GenerateButton").click(function(){
		if($("#report_name option:selected").val()=='0'){
			alert("Select One Report");
			document.getElementById('reportframe').style.display="none";
		}
		else if(document.getElementById('search').value=='' && $('#report_name option:selected').val()!='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT')
		{
			document.getElementById('reportframe').style.display="none";
		}
		else if(document.getElementById('datepicker').value=='' || document.getElementById('datepicker1').value=='' )
		{
			document.getElementById('reportframe').style.display="none";
		}
		else{
			document.getElementById('reportframe').style.display="block";
		 $('#idvalue').val($('#report_name option:selected').val());
		var date = new Date(); 
		var str = $('#search').val();
		var res = str.split("| ID:");
		var empid = <?php echo "'".$userid."'"; ?>;
		var empname = <?php echo "'".$usernameval."'"; ?>;
		var repstr = $('#report_name option:selected').val();
		var res1 =repstr.split("ATTENDANCE_ATR_");
		var odaURL = <?php echo "'".$odaurl."'"; ?>;
		var odaUser = <?php echo "'".$odauser."'"; ?>;
		var odaPassword = <?php echo "'".$odapwd."'"; ?>;
		if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT' || $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-10_DEPT_SUMM_REPORT')
		{
			srcval="http://115.160.252.30:8082/birt/frameset?__report=Report%20Templates/AHRMS_Test/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId=%25&EmployeeName=%25&EmpID_Par=ALL&EmpNamePar=ALL&ReportName="+res1[1]+"&Location="+$('#loc_name option:selected').val()+"&Department="+$('#dept_name option:selected').val()+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&DataFilter=ALL&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
		}
		else
		{
			srcval="http://115.160.252.30:8082/birt/frameset?__report=Report%20Templates/AHRMS_Test/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+empid+"&EmployeeName="+empname+"&EmpID_Par="+empid+"&EmpNamePar="+empname+"&ReportName="+res1[1]+"&Location="+$('#loc_name option:selected').val()+"&Department="+$('#dept_name option:selected').val()+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&DataFilter="+empname+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
		}
		document.getElementById('reportframe').src =srcval;
		}
		  });
	</script>
	<script>
	function fill(Value) {
   $('#search').val(Value);
   $('#display').hide();
}
</script>
<script>
$(document).ready(function() {
	$('#datepicker1').datepicker({ dateFormat: 'dd-M-yy' });
    $('#datepicker1').datepicker('setDate', new Date());
	var currentTime = new Date();
	var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
	$("#datepicker").datepicker().datepicker("setDate", startDateFrom);
});
	</script>

<script>
function clearfields()
{
	$('#report_name option[value="0"]').prop("selected", true);
}
</script>
<script>
function showfields(val){
	if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-30_IND_SUMM_REPORT' || $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-30_IND_DET_REPORT' || $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-10_DEPT_SUMM_REPORT')
	{
		document.getElementById('loclab').style.display="block";
		document.getElementById('loc_name').style.display="block";
			if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-10_DEPT_SUMM_REPORT')
			{
				$("#dept_name").prop('disabled',false);
			}
			else
			{
				$("#dept_name").prop('disabled',true);
				$('#dept_name option:selected').val()=<?php echo "'".$userDept."'";?>;
				
			}
	}
	else{
		document.getElementById('loclab').style.display="none";
		document.getElementById('loc_name').style.display="none";
	}
}
</script>
</body>

</html>
