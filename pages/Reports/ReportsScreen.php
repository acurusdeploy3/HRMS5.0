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
require_once("config2.php");
require_once("odadetails.php");
date_default_timezone_set('Asia/Kolkata');

$scnaccess=mysqli_query($db1,"SELECT * FROM `login` where User_ID = '$userid'");
$scnnameval = mysqli_fetch_assoc($scnaccess);
$scnRole = $scnnameval['role'];


?>
<?php
if($scnRole == 'Manager' || $scnRole == 'Admin')
{
	$rptname='';
$date = date("Y-m-d");
$date1 = date("d-M-Y");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image,Department from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$userDept = $useridrow['Department'];

$chkthatval = mysqli_query($db,"select * from pms_manager_lookup where manager_id='$userid' and Department  in ('RCM','PUBLISHING','TRAINING','MARKETING','DATA REPORTING')");
if(mysqli_num_rows($chkthatval)>0)
{
$rptdropdown = mysqli_query($db,"SELECT Report_Name,Report_Header_Name FROM `report_allocation_table` a
inner join report_master m on a.report_master_id=m.report_master_id
where Allocated_employee_id=$userid and a.is_active='Y' and m.is_active='Y'
union
select Report_Name,Report_Header_Name as Allocated_employee_id from report_master m where m.report_classification in ('ALL_ATTENDANCE','ALL_MANAGER') and m.is_active='Y'
union 
SELECT Report_Name,Report_Header_Name FROM `report_master` where report_classification='ALL_CHKLIST'");

}
else
{
$rptdropdown = mysqli_query($db,"SELECT Report_Name,Report_Header_Name FROM `report_allocation_table` a
inner join report_master m on a.report_master_id=m.report_master_id
where Allocated_employee_id=$userid and a.is_active='Y' and m.is_active='Y'
union
select Report_Name,Report_Header_Name as Allocated_employee_id from report_master m where m.report_classification in ('ALL_ATTENDANCE','ALL_MANAGER') and m.is_active='Y';");
}

$locationdropdown = mysqli_query($db,"select * from all_business_units where country='India'");
$locationdropdown1 = mysqli_query($db,"select * from all_business_units where country='India'");

$deptdropdown = mysqli_query($db,"SELECT * FROM `all_departments`");
$deptdropdown1 = mysqli_query($db,"SELECT * FROM `all_departments` where department!='$userDept'");
if($userid == '1' || $userid == '2' || $userid =='1034')
{
 $reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details where is_active='Y') and employee_id not in (10001,10000,10002,10003,10004,10005,10006,10007,10008,10009,10010,9999) and employee_id!=$userid and is_active='Y' order by employee_id");
}
else
{
$reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details where is_active='Y') and employee_id not in (1,2,3,10001,10000,10002,10003,10004,10005,10006,10007,10008,10009,10010,9999) and employee_id!=$userid and is_active='Y' order by employee_id");
}


$monthdropdown = mysqli_query($db,"SELECT * FROM `month_lookup`");
$Yeardropdown = mysqli_query($db,"SELECT * FROM `year_lookup` where years<= year(now()) and years>=2015 order by years");
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
                  
                  <input type="hidden" value="<?php echo $userid; ?>" id="curuser"/>
                  <input type="hidden" value="<?php echo $userRole; ?>" id="currole"/>
				<div class="col-sm-7">
                 <select class="form-control" name="report_name" id="report_name" onchange="showfields(this.value);"> 
					<option value="0">Select One Report</option>
					<?php
                    while($row1 = mysqli_fetch_assoc($rptdropdown))
					{
					?>
					
			<option value="<?php echo $row1['Report_Name']; ?>"><?php echo $row1['Report_Header_Name']; ?></option>
					<?php
                    }
                    ?>
					</select>
					<input type="hidden" value="" name="idvalue" id="idvalue"/>
					</div>	</div>
					<div class="form-group">
					<label class="col-sm-2 control-label" id="locnm">Location</label>
					<div class="col-sm-2">					   
					   <select class="form-control" name="loc_name" id="loc_name" onchange="LocationChange();"> 
					<!-- <option value="Select One Report">Select One Report width="900"</option> -->
					<?php
                    while($row2 = mysqli_fetch_assoc($locationdropdown1))
					{
					?>
					
			<option value="<?php echo $row2['Value']; ?>"><?php echo $row2['business_unit']; ?></option>
					<?php
                    }
                    ?>
					</select>
					</div>
					<label for="inptdpt" class="col-sm-1 control-label" id="deptnm">Department</label>
					<div class="col-sm-3">
					 <select class="form-control" name="dept_name" id="dept_name" onchange="DeportmentChange(this.value);"> 
					 <option value="ALL">ALL</option>
					<option value="<?php echo $userDept;?>"><?php echo $userDept;?></option>
					<?php
                    while($row3 = mysqli_fetch_assoc($deptdropdown1))
					{
					?>
					
			<option value="<?php echo $row3['department']; ?>"><?php echo $row3['department']; ?></option>
					<?php
                    }
                    ?>
					</select>
					</div>	  
			  
					<label class="col-sm-1 control-label" id="repto" style="display:none;">Reporting To</label>
					<div class="col-sm-3">					   
					   <select class="form-control" name="rep_name" id="rep_name" style="display:none;"> 
							<option value="" selected>Select Reporting</option> 
							<option value="<?php echo $userid;?>"><?php echo $usernameval;?></option>
					   					   					<?php
							while($row4 = mysqli_fetch_assoc($reptodropdown))
							{
							?>
							<option value="<?php echo $row4['employee_id']; ?>"><?php echo $row4['Manager']; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					</div>	
					<div class="form-group" id='multidep' Style='display:none;'>
						<label class="col-sm-2 control-label" >Department</label>
						<div class="col-sm-10">	
							<select class="form-control select2" id="SelByRole" name="SelByRole[]" multiple="multiple" placeholder="Select Department(s)">
							<?php
							while ($audR = mysqli_fetch_assoc($deptdropdown))
							{
							?>
								<option value="<?php echo $audR['department']  ?>"><?php echo $audR['department']  ?></option>
							<?php
							}
							?>
							</select>
						</div>
					</div>
					<div class="form-group" id='multiloc' Style='display:none;'>
						<label class="col-sm-2 control-label" >Location</label>
						<div class="col-sm-5">	
							<select class="form-control select2" id="SelByLoc" name="SelByLoc[]" multiple="multiple" placeholder="Select Location(s)" >
							<?php
							while ($audRR = mysqli_fetch_assoc($locationdropdown))
							{
							?>
								<option value="<?php echo $audRR['Value']  ?>"><?php echo $audRR['business_unit']  ?></option>
							<?php
							}
							?>
							</select>
						</div>
					</div>
              <div class="form-group" id='multiyear' Style='display:none;'>
						<label class="col-sm-2 control-label" >Year</label>
						<div class="col-sm-4">	
							<select class="form-control" name="Year_Name" id="Year_Name" style="display:none;" onchange='YearChange(this.value);'> 
							<option value="" selected>Select Year</option> 
							<?php
							while($row6 = mysqli_fetch_assoc($Yeardropdown))
							{
							?>
							<option value="<?php echo $row6['Years']; ?>"><?php echo $row6['Years']; ?></option>
							<?php
							}
							?>
						</select>
						</div>
					</div>
					<div class="form-group" id='multimonth' Style='display:none;'>
						<label class="col-sm-2 control-label" >Month</label>
						<div class="col-sm-4">	
							<select class="form-control" name="month_name" id="month_name" style="display:none;"> 
							<option value="" selected>Select Month</option> 
							<?php
							while($row5 = mysqli_fetch_assoc($monthdropdown))
							{
							?>
							<option value="<?php echo $row5['ID']; ?>"><?php echo $row5['month_name']; ?></option>
							<?php
							}
							?>
						</select>
						</div>
					</div>
					<div class="form-group">	
					<label for="frmdt" id="frmdt" class="col-sm-2 control-label">From Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker" id="datepicker"  autocomplete="off" required></input>
                  </div>
					<label for="todate" id="todate" class="col-sm-2 control-label">To Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker1" id="datepicker1" autocomplete="off" required></input>
                  </div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label" id="dfilter">Data Filter<span class="error">*  </span></label>
					<div class="col-sm-5">
                 	 <input type="text" id="search" placeholder="Search by Employee Name" autocomplete="off" class="form-control" required="required"> </input>
					  <div id="display"></div>
					</div>
					<div class="col-sm-1">
					<a onclick="clearsearch();" id="clrsearch"><i class="fa fa-trash" style="color:tomato;font-size: 20px;" aria-hidden="true"></i></a>
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
				  <iframe id="reportframe"  style = "margin-left:1%;width: -webkit-fill-available;overflow-x: scroll;overflow-y: scroll; height: 600px;" src=""></iframe>
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
		maxDate: +0,
		orientation: "bottom"
  });
    $("#datepicker1").datepicker({ 
        changeMonth: true,
        changeYear: true,
		autoclose:true,
		maxDate: +0,
		orientation: "bottom"
  });
});
function openbirtreport()
{
	document.getElementById('iframeid').style.display = "block"; 
}
</script>

<script>
    $("#report_name").change(function() {
     var attreports=["Leave_Details_Report","LOP_Details_Report","Night_Shift_Allowance_Report","Executive_LOP_Details_Report","Monthly_Extended_Hours_Report"];
	   
	   var flap='0';
	  		for(var k=0;k<attreports.length;k++)
			{
				if($("#report_name option:selected").val()==attreports[k])
				{
					flap='1';
				}
				else
				{
				}
			}
	   if(flap == '1')
	   {
			var currentTime = new Date();
			currentTime = new Date(currentTime.setMonth(currentTime.getMonth() - 1));
			var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
			$("#datepicker").datepicker().datepicker("setDate", startDateFrom);
		   
			$('#datepicker1').datepicker({ dateFormat: 'dd-M-yy' });
			var makeDate = new Date();
			makeDate = new Date(makeDate.getFullYear(),makeDate.getMonth(),0);
			var startDateTo = makeDate;
			$('#datepicker1').datepicker('setDate', startDateTo);	   
	   }
	   else {
		    $('#datepicker1').datepicker({ dateFormat: 'dd-M-yy' });
			$('#datepicker1').datepicker('setDate', new Date());
			var currentTime = new Date();
			var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
			$("#datepicker").datepicker().datepicker("setDate", startDateFrom);
	   }
	   
	   if($("#report_name option:selected").val()=="CPP_Report")
	   {
		   var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
		   
		   var makeDate = new Date();
		   makeDate = new Date(makeDate.setMonth(makeDate.getMonth() - 1));
		   var startDateFrom = new Date(makeDate.getFullYear(),makeDate.getMonth(),1);
		   var selectedYear = startDateFrom.getFullYear();
		   var selectedMonth = months.indexOf(startDateFrom.toDateString().split(" ")[1]);
		   $('select[name^="Year_Name"] option[value='+selectedYear+']').attr("selected","selected");
		   YearChange(selectedYear);
		 //  $('select[name^="month_name"] option[value='+selectedMonth+']').attr("selected","selected");
	// $('select[name^="month_name"] option[value*=selectedMonth]').attr("selected","selected");
	   }
		
		
		
      
       var hrvalues = ["Exit_Report","Department_Summary_Report","MRF_Status_Report","Deletion_List_Report","New_Hires_Report","Exit_Report","Attrition_Summary_Report"];  
	  
		var flag1='0';
	  		for(var j=0;j<hrvalues.length;j++)
			{
				if($("#report_name option:selected").val()==hrvalues[j])
				{
					flag1='1';
				}
				else
				{
				}
			}
			if(flag1=='1')
		{
			//document.getElementById('loc_name').style.display="none";
			//document.getElementById('dept_name').style.display="none";
			$("#loc_name").prop("disabled", true);
			$("#search").prop("disabled", true);
			$("#search").prop("required", false);
			$("#dept_name").prop("disabled", true);
			$("#rep_name").prop("disabled", true);
		}
		else
		{
			$("#loc_name").prop("disabled", false);
			$("#search").prop("disabled", false);
			//$("#search").prop("required", true);
			$("#dept_name").prop("disabled", false);
			$("#rep_name").prop("disabled", false);
		}
    });

	$("#GenerateButton").click(function(){
		var reportnames = ["ATTENDANCE_ATR_2010-30_IND_DET_REPORT","ATTENDANCE_ATR_2010-30_IND_SUMM_REPORT","ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT_DETAILED"];
		var flag='0';
		for(var i=0;i<reportnames.length;i++)
			{
				if($("#report_name option:selected").val()==reportnames[i])
				{
					flag='1';
					$("#search").prop("required", true);
				}
				else
				{
					$("#search").prop("required", false);
				}
			}
				
		if($("#report_name option:selected").val()=='0'){
			alert("Select One Report");
			document.getElementById('reportframe').style.display="none";
		}
		else if(document.getElementById('search').value=='' && flag=='1')
		{
			document.getElementById('reportframe').style.display="none";
		}
		else if(document.getElementById('datepicker').value=='' || document.getElementById('datepicker1').value=='' )
		{
			document.getElementById('reportframe').style.display="none";
		}
		else {
		document.getElementById('reportframe').style.display="block";
		 $('#idvalue').val($('#report_name option:selected').val());
		 var date = new Date(); 
		var str = $('#search').val();
		var res = str.split("| ID:");
		var repstr = $('#report_name option:selected').val();
		var res1 =repstr.split("ATTENDANCE_ATR_");
		var datafltr= res[0];
		var odaURL = <?php echo "'".$odaurl."'"; ?>;
		var odaUser = <?php echo "'".$odauser."'"; ?>;
		var odaPassword = <?php echo "'".$odapwd."'"; ?>;
		if($('#dept_name option:selected').val()=='ALL')
		{
			var deptnamesel='%25';
		}
		else
		{
			var deptnamesel=$('#dept_name option:selected').val();
		}
		if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT' || $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-10_DEPT_SUMM_REPORT'
		|| $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT_1' || $('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT_DETAILED')
		{	
		var reportingto = $('#rep_name option:selected').val();
		if(reportingto=='')
		{
			
			//reportingto=<?php echo "'".$userid."'"; ?>;
			reportingto = '459874566'
		}
		if($('#search').val()=='')
		{
			res[0]='%25';
			res[1]='%25';
			datafltr='ALL'
		}
		
		<?php $chkQuery = "SELECT * FROM `all_fields` where category='Reports_UI' and field_name='Data_Filter'
		and locate('$userid',value);"; 
		 $chkExecQuery = MySQLi_query($db, $chkQuery);
		if(mysqli_num_rows($chkExecQuery) < 1){	
		?>
		srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+res[1]+"&EmployeeName="+res[0]+"&EmpID_Par="+res[1]+"&EmpNamePar="+res[0]+"&ReportName="+res1[1]+"&DataFilter="+res[0]+"&Location="+$('#loc_name option:selected').val()+"&Department="+deptnamesel+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword+"&ReportTo=Reporting To :"+$('#rep_name option:selected')[0].innerText+"&ReportToid="+reportingto;
		<?php } else {?>
		srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+res[1]+"&EmployeeName="+res[0]+"&EmpID_Par="+res[1]+"&EmpNamePar="+res[0]+"&ReportName="+res1[1]+"&DataFilter="+datafltr+"&Location="+$('#loc_name option:selected').val()+"&Department="+deptnamesel+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword+"&ReportTo=Reporting To :"+$('#rep_name option:selected')[0].innerText+"&ReportToid="+reportingto;
		<?php } ?>
		}
		else if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-40_IND_LATE_IN_AND_EARLY_OUT_DET_REPORT'){
		if($('#search').val()=='')
		{
			res[0]='%25';
			res[1]='%25';
			datafltr='ALL'
		}
		var reportingto = $('#rep_name option:selected').val();
		var reptfilter = $('#rep_name option:selected')[0].innerText;
		if(reportingto=='')
		{
			reptfilter='All';
			//reportingto=<?php echo "'".$userid."'"; ?>;
			reportingto = '%25'
		}
		var today = new Date();
		var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
		if (date == $('#datepicker1').val())
		{
			currentdateparams ='Yes';
		}
		else
		{
			currentdateparams ='No';
		}
		srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+res[1]+"&EmployeeName="+res[0]+"&EmpID_Par="+res[1]+"&EmpNamePar="+res[0]+"&ReportName="+res1[1]+"&DataFilter="+res[0]+"&Location="+$('#loc_name option:selected').val()+"&Department="+deptnamesel+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword+"&ReportTo=Reporting To :"+reptfilter+"&ReportToid="+reportingto+"&currentdateparams="+currentdateparams;
		}
		else if($('#report_name option:selected').val()=='On_Roll_Report')
		{
			var deptvalue ='';
			var totlen=$('#SelByRole option:selected').length;
			if(totlen>0){
			for(var j=0;j<totlen;j++)
			{
				if(j==0)
				{
					deptvalue = '\''+$('#SelByRole option:selected')[j].innerText+'\',\'';
				}
				if(j==totlen-1)
				{
					deptvalue = deptvalue+$('#SelByRole option:selected')[j].innerText+'\'';
				}
				else
				{
					deptvalue = deptvalue+$('#SelByRole option:selected')[j].innerText+'\',\'';
				}
			}
			}
			else 
			{
				deptvalue = '%25'
			}
			var locvalue ='';
			var totloclen=$('#SelByLoc option:selected').length;
			if(totloclen>0){
			for(var j=0;j<totloclen;j++)
			{
				if(j==0)
				{
					locvalue = '\''+$('#SelByLoc option:selected')[j].innerText+'\',\'';
				}
				if(j==totloclen-1)
				{
					locvalue = locvalue+$('#SelByLoc option:selected')[j].innerText+'\'';
				}
				else
				{
					locvalue = locvalue+$('#SelByLoc option:selected')[j].innerText+'\',\'';
				}
			}
			}
			else 
			{
				locvalue = '%25'
			}
			srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+res[1]+"&EmployeeName="+res[0]+"&EmpID_Par="+res[1]+"&EmpNamePar="+res[0]+"&ReportName="+res1[1]+"&DataFilter="+res[0]+"&Location="+locvalue+"&Department="+deptvalue+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
			
			
		}
         else if($('#report_name option:selected').val()=='Appraisee_List_Report')
		{
				
        	
        	var currole = document.getElementById('currole').value;
        	if(currole=='HR' || currole=='HR Manager')
            {
            	var curuser = '%25';
            	srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&ReportName="+$("#report_name option:selected").val()+"&ManagerID="+curuser+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
            }
        	else
            {
            var curuser = document.getElementById('curuser').value;
			srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&ReportName="+$("#report_name option:selected").val()+"&ManagerID="+curuser+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;		
            }
        }
        else if($('#report_name option:selected').val()=='CPP_Report')
		{
			  	var Month = $('#month_name option:selected').val();
				var Year = $('#Year_Name option:selected').val();
				if(Month=='' && Year=='')
				{
					alert("Select Month and Year");
				}
				else{
					srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS_TEST/"+$('#report_name option:selected').val()+".rptdesign&Month="+Month+"&Year="+Year+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
				}
        }
		else
		{
		srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+res[1]+"&EmployeeName="+res[0]+"&EmpID_Par="+res[1]+"&EmpNamePar="+res[0]+"&ReportName="+res1[1]+"&DataFilter="+res[0]+"&Location="+$('#loc_name option:selected').val()+"&Department="+deptnamesel+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
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
   $("#search").keyup(function() {
       var name = $('#search').val();
	   var deptname = $('#dept_name option:selected').val();
	   var reptname = $('#report_name option:selected').val();
	   var usrid = <?php echo "'".$userid."'"; ?>;
       if (name == "") {
           $("#display").html("");
       }
       else {
           $.ajax({
               type: "POST",
               url: "searchval.php",
               data: {
                   search: name,
				   usid: usrid,
				   dept: deptname,
				   rept:reptname,
               },
               success: function(html) {
                   $("#display").html(html).show();
               }
           });
       }
   });
});
	</script>
	<script type="text/javascript">
function clearsearch() {
    document.getElementById("search").value="";
	       var name = $('#search').val();
       if (name == "") {
           $("#display").html("");
       }
       else {
           $.ajax({
               type: "POST",
               url: "searchval.php",
               data: {
                   search: name
               },
               success: function(html) {
                   $("#display").html(html).show();
               }
           });
       }
}
</script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
  });
</script>
<script>
function clearfields()
{
	//document.getElementById('search').value='';
	//$('#report_name option[value="0"]').prop("selected", true);
	//document.getElementById('repto').style.display="none";
	//document.getElementById('rep_name').style.display="none";
	//document.getElementById('reportframe').style.display="none";
	ajaxindicatorstart("Processing..Please Wait..");
	location.reload();
	
	
}
</script>
<script>
function DeportmentChange(val){
		var location=$('#loc_name option:selected').text();
            $.ajax({
                type: "POST",
                url: "dropdown.php",
                data: {
					department:val,
					location:location,
				},	
                success: function(data){
                    $('#rep_name').html(data);
                }
            });
}
</script>
<script>
function LocationChange(){
		var location=$('#loc_name option:selected').text();
		var val=$('#dept_name option:selected').text();
            $.ajax({
                type: "POST",
                url: "dropdown.php",
                data: {
					department:val,
					location:location,
				},	
                success: function(data){
                    $('#rep_name').html(data);
                }
            });
}
</script>
<script>
function showfields(val){
	if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT')
	{
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('repto').style.display="block";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('rep_name').style.display="block";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		document.getElementById('deptnm').style.display="block";
		document.getElementById('dept_name').style.display="block";
		document.getElementById('search').style.display="block";
		document.getElementById('dfilter').style.display="block";
		document.getElementById('clrsearch').style.display="block";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
    	
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		
	}
	else if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT_DETAILED')
	{
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('repto').style.display="block";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('rep_name').style.display="block";
		$("#search").prop('required',true);
		$("#search").prop('disabled',false);
		document.getElementById('deptnm').style.display="block";
		document.getElementById('dept_name').style.display="block";
		document.getElementById('search').style.display="block";
		document.getElementById('dfilter').style.display="block";
		document.getElementById('clrsearch').style.display="block";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		
	}
	else if ($('#report_name option:selected').val()=='Attendance_Verification_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		//document.getElementById('locnm').style.display="none";
		//document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		
	}
	else if ($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-05_SUMMARY_REPORT')
	{
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
	}
	else if ($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-10_DEPT_SUMM_REPORT')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		$("#search").prop('required',false);
		$("#search").prop('disabled',true);
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
	}
	else if ($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-40_IND_LATE_IN_AND_EARLY_OUT_DET_REPORT')
	{
		document.getElementById('repto').style.display="block";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('rep_name').style.display="block";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('deptnm').style.display="block";
		document.getElementById('dept_name').style.display="block";
		document.getElementById('search').style.display="block";
		document.getElementById('dfilter').style.display="block";
		document.getElementById('clrsearch').style.display="block";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
    	//$("#dept_name option[value='ALL']").remove();
    	//var val = document.getElementById('dept_name').value;
    	//DeportmentChange(val)
    	
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
	}
	else if($('#report_name option:selected').val()=='ATTENDANCE_ATR_2010-15_REPT_TO_SUMM_REPORT')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('rep_name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',true);
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="block";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
	}
	else if ($('#report_name option:selected').val()=='Working_Days_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		
	}
else if ($('#report_name option:selected').val()=='Attendance_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		
	}
else if ($('#report_name option:selected').val()=='Leave_Details_Report' || $('#report_name option:selected').val()=='Night_Shift_Allowance_Report' || $('#report_name option:selected').val()=='Executive_LOP_Details_Report' || $('#report_name option:selected').val()=='LOP_Details_Report' || $('#report_name option:selected').val()=='Monthly_Extended_Hours_Report' )
	{
		
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		
	}
	else if ($('#report_name option:selected').val()=='CPP_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="none";
		document.getElementById('frmdt').style.display="none";
		document.getElementById('datepicker1').style.display="none";
		document.getElementById('todate').style.display="none";
		document.getElementById('multimonth').style.display="block";
		document.getElementById('month_name').style.display="block";
		document.getElementById('multiyear').style.display="block";
		document.getElementById('Year_Name').style.display="block";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
	}
else if ($('#report_name option:selected').val()=='Attendance_Manager_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		
	}

else if ($('#report_name option:selected').val()=='Appraisee_List_Report')
	{
		document.getElementById('repto').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('multidep').style.display="none";
		document.getElementById('datepicker').style.display="none";
		document.getElementById('frmdt').style.display="none";
		document.getElementById('datepicker1').style.display="none";
		document.getElementById('todate').style.display="none";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('required',false);
		$("#search").prop('disabled',false);
		
	}
	else if($('#report_name option:selected').val()=='On_Roll_Report')
	{
		document.getElementById('multidep').style.display="block";
		document.getElementById('multiloc').style.display="block";
		document.getElementById('SelByRole').nextSibling.style.width='-webkit-fill-available';
		document.getElementById('SelByLoc').nextSibling.style.width='-webkit-fill-available';
		$('#multidep').prop("disabled",false);
		$('#multiloc').prop("disabled",false);
		$("#loc_name").prop("disabled", true);
		$("#search").prop("disabled", true);
		$("#search").prop("required", false);
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('datepicker').style.display="none";
		document.getElementById('frmdt').style.display="none";
		document.getElementById('datepicker1').style.display="none";
		document.getElementById('todate').style.display="none";
		document.getElementById('repto').style.display="none";
		document.getElementById('rep_name').style.display="none";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#dept_name").prop("disabled", true);
		$("#rep_name").prop("disabled", true);
    	$("#SelByRole").select2({
			placeholder: "Select Department(s)"
		});
		$("#SelByLoc").select2({
			placeholder: "Select Location(s)"
		});
	}
else if($('#report_name option:selected').val()=='COS_Report')
	{
		document.getElementById('multidep').style.display="none";
		document.getElementById('multiloc').style.display="none";
   		document.getElementById('SelByLoc').style.display="none";
    	document.getElementById('SelByRole').style.display="none";
		document.getElementById('SelByRole').nextSibling.style.width='-webkit-fill-available';
		document.getElementById('SelByLoc').nextSibling.style.width='-webkit-fill-available';
		$('#multidep').prop("disabled",false);
		$('#multiloc').prop("disabled",false);
		$("#loc_name").prop("disabled", true);
		$("#search").prop("disabled", true);
		$("#search").prop("required", false);
		document.getElementById('dept_name').style.display="none";
		document.getElementById('deptnm').style.display="none";
		document.getElementById('loc_name').style.display="none";
		document.getElementById('locnm').style.display="none";
		document.getElementById('search').style.display="none";
		document.getElementById('clrsearch').style.display="none";
		document.getElementById('dfilter').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
		document.getElementById('repto').style.display="none";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		document.getElementById('rep_name').style.display="none";
		$("#dept_name").prop("disabled", true);
		$("#rep_name").prop("disabled", true);
    	$("#SelByRole").select2({
			placeholder: "Select Department(s)"
		});
		$("#SelByLoc").select2({
			placeholder: "Select Location(s)"
		});
	}
	else{
		document.getElementById('multidep').style.display="none";
		document.getElementById('multiloc').style.display="none";
		document.getElementById('loc_name').style.display="block";
		document.getElementById('locnm').style.display="block";
		document.getElementById('deptnm').style.display="block";
		document.getElementById('dept_name').style.display="block";
		document.getElementById('search').style.display="block";
		document.getElementById('dfilter').style.display="block";
		document.getElementById('clrsearch').style.display="block";
		document.getElementById('repto').style.display="none";
		document.getElementById('rep_name').style.display="none";
		document.getElementById('datepicker').style.display="block";
		document.getElementById('frmdt').style.display="block";
		document.getElementById('datepicker1').style.display="block";
		document.getElementById('todate').style.display="block";
    
		document.getElementById('multimonth').style.display="none";
		document.getElementById('month_name').style.display="none";
		document.getElementById('multiyear').style.display="none";
		document.getElementById('Year_Name').style.display="none";
		$("#search").prop('disabled',false);
		$("#search").prop('required',true);
	}
}
</script>
<script>
function YearChange(val){
            $.ajax({
                type: "POST",
                url: "mnthdropdown.php",
                data: "Year="+val,
                success: function(data){
                    $('#month_name').html(data);
                }
            });
}
</script>
 <script>
 
$("#datepicker1").change(function () {
    var datepicker = document.getElementById("datepicker").value;
    var datepicker1 = document.getElementById("datepicker1").value;
 
    if ((Date.parse(datepicker1) < Date.parse(datepicker))) {
        alert("From Date should be less than To Date.");
        document.getElementById("datepicker1").value = "";
    }
});
	</script>
</body>

</html>
<?php } else if ($scnRole == 'Employee')
{
	$rptname='';
$date = date("Y-m-d");
$date1 = date("d-M-Y");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image,Department from employee_details where employee_id=$userid");

$rptdropdown = mysqli_query($db,"SELECT Report_Name,Report_Header_Name FROM `report_allocation_table` a
inner join report_master m on a.report_master_id=m.report_master_id
where Allocated_employee_id=$userid and a.is_active='Y' and m.is_active='Y'
union
select Report_Name,Report_Header_Name as Allocated_employee_id from report_master m where m.report_classification in ('ALL_ATTENDANCE') and m.is_active='Y';");

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
                 <select class="form-control" name="report_name" id="report_name"> 
					<option value="0">Select One Report</option>
					<?php
                    while($row1 = mysqli_fetch_assoc($rptdropdown))
					{
					?>
					
			<option value="<?php echo $row1['Report_Name']; ?>"><?php echo $row1['Report_Header_Name']; ?></option>
					<?php
                    }
                    ?>
					</select>
					<input type="hidden" value="" name="idvalue" id="idvalue"/>
					</div>	
					<label for="inptdpt" class="col-sm-2 control-label">Department</label>
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
					<label for="frmdt" class="col-sm-2 control-label">From Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker" id="datepicker"  autocomplete="off" required></input>
                  </div>
					<label for="frmdt" class="col-sm-2 control-label">To Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker1" id="datepicker1" autocomplete="off" required></input>
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
					<label class="col-sm-1 control-label">Location</label>
					<div class="col-sm-3">					   
					   <select class="form-control" name="loc_name" id="loc_name"> 
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
				  <iframe id="reportframe" style = "margin-left:1%;width: -webkit-fill-available;height: 600px;overflow-x: scroll;overflow-y: scroll;" src=""></iframe>
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
		srcval="https://ahrms.acurussolutions.com:8443/birt/frameset?__report=Report%20Templates/AHRMS/"+$('#report_name option:selected').val()+".rptdesign&FromDate="+$('#datepicker').val()+"&ToDate="+$('#datepicker1').val()+"&FromDatepar="+$('#datepicker').val()+"&ToDatePar="+$('#datepicker1').val()+"&EmployeeId="+empid+"&EmployeeName="+empname+"&EmpID_Par="+empid+"&EmpNamePar="+empname+"&ReportName="+res1[1]+"&Location="+$('#loc_name option:selected').val()+"&Department="+$('#dept_name option:selected').val()+"&ReportName="+res1[1]+"&Parameters=From Date: "+$('#datepicker').val()+", To Date: "+$('#datepicker1').val()+", Employee ID:ALL,Employee Name:All&AttandanceHeader=Attendance Summary - Date of Report:"+$('#curdate').val()+"&DataFilter="+empname+"&odaURL="+odaURL+"&odaUser="+odaUser+"&odaPassword="+odaPassword;
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
 
$("#datepicker1").change(function () {
    var datepicker = document.getElementById("datepicker").value;
    var datepicker1 = document.getElementById("datepicker1").value;
 
    if ((Date.parse(datepicker1) < Date.parse(datepicker))) {
        alert("End date should be greater than Start date");
        document.getElementById("datepicker1").value = "";
    }
});
	</script>
</body>

</html>
<?php }
else
{
}
?>
