<?php   
session_start();  
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
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

require_once("config2.php");
require_once("config5.php");
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$month= date("F");

$scnval = $_GET['scnval'];
$idval= $_GET['idval'];
$querymylst=mysqli_query($db,"SELECT c.employee_id,concat(e.First_Name,' ',e.MI,' ',e.Last_Name) as Name,e.employee_designation as designation,t.department,c.date_joined,c.cos_master_id,
Date_Of_Completion_of_Probation,concat(ed.First_Name,' ',ed.MI,' ',ed.Last_Name) as Manager_Name,cs.employee_strengths,cs.employee_development_areas,cs.manager_feedback,
(select employee_id from all_hods a where a.department=t.department and a.location=e.business_unit) as hod_id,
c.extension_comments,cos_process_queue_id,
date_format(c.date_joined,'%d %b %Y') as doj,
date_format(c.Date_Of_Completion_of_Probation,'%d %b %Y') as doc,
l.Process
FROM `cos_master` c 
inner join employee_details e on c.employee_id=e.employee_id
inner join employee_details ed on c.Manager_ID=ed.employee_id
inner join resource_management_table t on c.employee_id=t.employee_id
left join employee_list l on c.employee_id=l.EmpID
left join cos_review_summary cs on c.cos_master_id=cs.cos_master_id
where  t.is_active='Y' and c.cos_master_id='$idval'");
$useridrow1 = mysqli_fetch_array($querymylst);
$nameid = $useridrow1['employee_id'];
$date_joined = $useridrow1['date_joined'];
$Date_Of_Completion_of_Probation = $useridrow1['Date_Of_Completion_of_Probation'];

$productivitylist=mysqli_query($db1,"Select
round(((sum(a.Actual_Prod)-sum(a.Errors_Rec))/sum(a.Target_prod))*100,2) as Eff_Prod,
round((sum(a.Actual_Prod)/sum(a.Target_prod))*100,2) as Prod,
round((1-(sum(Errors_Rec)/sum(Actual_Prod)))*100,2) as Quality_Perc
from (select d.*,((target*Percentage)/100) as `% of Target`,((Actual_Prod-Errors_Rec)/Target_prod) as Eff_Prod,(Actual_Prod/Target_prod) as Prod,
(1-(Errors_Rec/Actual_Prod))*100 as Quality_Perc from(select Doc_Type,Doc_Sub_Type,Process,level_grp,grpdays,Percentage,Target,sum(Total_Hours)*Target as Target_prod,sum(Total_Hours) as Actual_Hours,sum(case when process='QC' and doc_type='PAYMENT_POSTING' then Total_Units/2 else total_units end) as Actual_Prod,
sum(case when process='CAPTURE' and doc_type='PAYMENT_POSTING' then Total_Errors*2 else total_errors end) as Errors_Rec,sum(ifnull(Total_Audited_Units,0)) as Aud_Units,Project
from(select if(level_grp='E',GroupDays_Exp,GroupDays_Fresh) as grpdays,
if(level_grp='E',if(GroupDays_Exp='0-6 Days','30',if(GroupDays_Exp='7-30 Days','50',if(GroupDays_Exp='31-45 Days','75','100'))),if(GroupDays_Fresh='0-6 Days','30',if(GroupDays_Fresh='7-30 Days','50',if(GroupDays_Fresh='31-45 Days','75','100')))) as Percentage,b.* from (SELECT if(date_Add(doj , interval 366 day)<=date(now()),'E','F') as level_grp
,a.* FROM `pms_report_old_rampup_75` a where substring_index(user_name,'_',-1)='$nameid' and date(dop) between '$date_joined' and '$Date_Of_Completion_of_Probation') as b) as c group by target,process,doc_type,doc_sub_type,project,grpdays) as d) a;");
$productivitylistrow = mysqli_fetch_assoc($productivitylist);
$productivity = $productivitylistrow['Eff_Prod'];
$Quality = $productivitylistrow['Quality_Perc'];


$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$nameid");
$useridrow = mysqli_fetch_assoc($username);
$usernamev = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$hrdetails = mysqli_query($db,"SELECT value FROM `application_configuration` where config_type='COS_HANDLING' and parameter ='HR_ID'");
$hrid = mysqli_fetch_array($hrdetails);
$hrval = $hrid['value'];

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Boarding</title>
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
th {
  background-color: #fbe2d8;
}
	.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
td{
	border: 1px solid black ! important;
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
#Complete{
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
#CnfrmLetter{
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
#addstrengthbtn{
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
textarea {
	    resize: none;
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
    width: 60%;
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
       Confirmation Of Services
      <small>PIP Form </small>
      </h1>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
            <div class="box-header with-border" style="text-align: center;">
			<input action="action" class="btn btn-info pull-left" onclick="window.location='ConfirmServices.php';" type="button" value="Back" id="goprevious"/>
			 <div style="display:inline-block;">
				<div style='font-size: 25px;color: #e74908;'>Process Improvement Plan</div>
			</div>	
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
            
	<form class="form-horizontal" id="cosform" method="post" action="" enctype="multipart/form-data" onsubmit="CheckRequired(event);" autocomplete="off">
		<div class="border-class">
		    <div id="myCarousel" class="carousel slide" data-interval="false" data-ride="carousel">
				<div class="carousel-inner">
					<div class="item active" id="one">	
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Employee ID</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empid" name="empid" value="<?php echo $nameid; ?>" autocomplete="off" readonly disabled>
								<input type="hidden" class="form-control" id="empcosid" name="empcosid" value="<?php echo $useridrow1['cos_master_id']; ?>" autocomplete="off" readonly disabled>
								<input type="hidden" class="form-control" id="emphod" name="emphod" value="<?php echo $hrval; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Employee Name</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empname" name="empname" value="<?php echo $useridrow1['Name']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>	
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Designation</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdes" name="empdes" value="<?php echo $useridrow1['designation']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Department</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" value="<?php echo $useridrow1['department']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Process</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" value="<?php echo $useridrow1['Process']; ?>"  autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Project</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Date of Joining</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" value="<?php echo $useridrow1['doj']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Employment confirmation w.e.f</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" value="<?php echo $useridrow1['doc']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-5 control-label">Name of Manager</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="empdep" name="empdep" value="<?php echo $useridrow1['Manager_Name']; ?>" autocomplete="off" readonly disabled>
							</div>				  
						</div>
					</div>
					<div class="item" style="height:70%" id="two">
						<table class="table" style="font-size:14px;width:90%;margin: 0 auto;">
							<tr>
								<td style='font-weight: bold;text-align: -webkit-center;color: black;
								font-size: 18px;'>Work Deliverables (Productivity)</td>
							</tr>
						</table>
						<table class="table" style="font-size:14px;width:90%;margin: 0 auto;    table-layout: fixed;">
						<tbody>
							<tr style="font-weight: bold;text-align: -webkit-center;color: black ! important;">
								<td style='width:5%'>S.No.</td>
								<td style='width:30%'>Category</td>
								<td style='width:50%'>Expected Deliverables</td>
								<td style='width:5%'>
								<?php if($scnval=='N') { ?>
									<a href="#" id="myBtn1" title="Click to Add Strength" data-toggle="modal" data-target="#modal-default-Education"><i class="fa fa-fw fa-plus" style="color: forestgreen;"></i></a>
								<?php  } ?></td>
								<div id="myModal1" class="modal">
									<div class="modal-content">
										<span class="close12">&times;</span>
										<p>Add Expected Deliverables</p>
										<input type="text"  class="form-control" id="category" name="category" placeholder="Enter Category" maxlength="255" /><br>
										<input type="hidden"  class="form-control" id="cos_id" name="cos_id" value ="<?php echo $idval; ?>"/>
										<textarea rows="4" cols="50" class="form-control" id="expdev" name="expdev" placeholder="Enter Expected Deliverables " ></textarea>
										</br>
										<input type="button" id="addstrengthbtn"  name="addstrengthbtn"value = "Add Deliverables" onclick='getval();'></input>
									</div>
								</div> 
							</tr>
							<tbody>
						</table>
						<?php
							$strengtharea="SELECT * FROM `cos_master` m
							left join cos_pip_summary d on m.cos_master_id=d.cos_master_id
							where m.cos_master_id='$idval' and d.is_active='Y'";
							$chkstrengtharea = mysqli_query($db,$strengtharea);
							$j=1;
							while ($Result1 = MySQLi_fetch_array($chkstrengtharea)) {
						?>	
						<table class="table" id="strtable" style="font-size:14px;width:90%;margin: 0 auto;    table-layout: fixed;">
							<tbody>
								<tr id="rows" style="text-align: -webkit-center;">
									<td style='width:5%'><?php echo $j;?></td> 
									<td style='width:30%'>
									<TextArea rows="4" cols="40" style="width: -webkit-fill-available;" type="text" id="strength" name="" autocomplete="off" readonly disabled> <?php echo $Result1['Category'];?></TextArea>
									<td style='width:50%'>
									<TextArea rows="4" cols="70" style="width: -webkit-fill-available;"  type="text" id="dev" name="" autocomplete="off" readonly disabled> <?php echo $Result1['Expected_Deliverables'];?></TextArea>
									<input type='hidden' id='pipsummaryid' name='pipsummaryid'value="<?php echo $Result1['COS_PIP_SUMMARY_ID']; ?>"></input>
									</td>
									<td style='width:5%'>
									<?php if($scnval=='N') { echo "<a href='DeletePIPValues.php?idval=".$idval."&pipval=".$Result1['COS_PIP_SUMMARY_ID']."&scnval=Complete'><i class='fa fa-trash' style='color: tomato;'></i></a>"; } else {}?>
									</td>
								</tr>
							</tbody>
						</table>	
							<?php $j++; } ?>
							<br><br>
							<div class="form-group">
							 <label for="inputName" class="col-sm-3 control-label">HR Comments<span class="error">*  </span></label>
							<div class="col-sm-7">
							<textarea id="extcomm" name="extcomm" rows="4" cols="95" onkeyup='btndisable();' required><?php echo $useridrow1['extension_comments']; ?></textarea>
							</div>	
							</div>
						<div class="form-group">
							<div class="col-sm-4" style='text-align:right;'>
								<input type= "submit" name= "btnSubmit" class="btn btn-info" value= "Save" id="savefields"  disabled></input>
							</div>
							<?php if($useridrow1['cos_process_queue_id']=='9') {?>
							<div class="col-sm-3" style='text-align:center;'>
								<a id="CnfrmLetter">Download Extension Letter</a>
							</div>
							<div class="col-sm-4" style='text-align:left;'>
								<input type= "button" name= "button" class="btn btn-info" value= "Complete Formalities" id="Complete" onclick="updatecompletion();"></input>
							</div>
							<?php  } ?>
						</div>
					</div>
				</div>
				<a class="left carousel-control" id="leftone" href="#myCarousel" data-slide="prev" style="width:4%;    background-image: none ! important;" onclick=''>
				<span class="glyphicon glyphicon-chevron-left" style="color: #21515f;"></span>
				<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" id="rightone" href="#myCarousel" data-slide="next" style="width: 4%;    background-image: none ! important;">
				<span class="glyphicon glyphicon-chevron-right" style="color: #21515f;"></span>
				<span class="sr-only">Next</span>
				</a>
			</div>
        </div>
		<div class="border-class" id="display"></div>
		<div class="box-footer">
	  </div>
	</form>
          </div>
          <!-- /.box -->
		   </div>
		   
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
<script src="dist/js/loader.js"></script>
<!-- Page script -->
<script>
function CheckRequired(event) {
		if (!confirm('Are you sure you want to save the changes?')){
  event.preventDefault();
        }
  else{
	ajaxindicatorstart("Processing..Please Wait..");
	   var process= 'HR_PIP_Approved';
       var EmpIdvalue = $('#empid').val();
	   var AllIdvalue = $('#emphod').val();
	   var CosIdValue = $('#empcosid').val();
	   var StrValue = $('#empstr').val();
	   var ImpValue = $('#empimp').val();
	   var FedValue = $('#empfeed').val();
	   var ProdValue = $('#empprod').val();
	   var QualValue = $('#empqual').val();
	   var ExtQual = $('#extcomm').val();
           $.ajax({
               type: "POST",
               url: "UpdateCOSAllocation.php",
               data: {
                   EmpIdvalue: EmpIdvalue,
				   process:process,
				   AllIdvalue:AllIdvalue,
				   CosIdValue : CosIdValue,
				   StrValue : StrValue,
				   ImpValue : ImpValue,
				   FedValue : FedValue,
				   ProdValue : ProdValue,
				   QualValue : QualValue,
				   ExtQual : ExtQual,
				   Role:'HRMNGR'
               },
			    success: function(data) {
                debugger;
				   location.reload();
                
               }
           });
		   document.getElementById('savefields').disabled=true;
       }
	}

$(document).ready(function () {
       <?php if( $_SESSION['checkedinval']=='Child'){ ?>
		$("#one").removeClass("item active");
        	$("#two").removeClass("item");
        	$("#one").addClass("item");
        	$("#two").addClass("item active");
	<?php } else {
$_SESSION['checkedinval']='';
}
?>
});
</script>
<script>
function updatecompletion()
{
	if (!confirm('Kindly download the Letter . Are you sure you want to extend the services of the employee?')){
  that.preventDefault();}
  else{
	ajaxindicatorstart("Processing..Please Wait..");
	   var process= 'IN_PIP';
       var EmpIdvalue = $('#empid').val();
	   var CosIdValue = $('#empcosid').val();
           $.ajax({
               type: "POST",
               url: "UpdateCOSCompletion.php",
               data: {
                   EmpIdvalue: EmpIdvalue,
				   process:process,
				   CosIdValue : CosIdValue
               },
			    success: function(data) {
				 location.href = "ConfirmServices.php";
              }
           });
       }
}
</script>
<script language="Javascript">
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
             return false;
          return true;

	   }
       //-->
    </script>
		<script>
	var checkitem = function() {
  var $this;
  $this = $("#myCarousel");
  if ($("#one").hasClass("item active")) {
    document.getElementById('leftone').style.display="none";
    document.getElementById('rightone').style.display="block";
  } else if ($("#two").hasClass("item active")) {
    document.getElementById('leftone').style.display="block";
    document.getElementById('rightone').style.display="none";
  } else {
    $this.children(".carousel-control").show();
  }
};

checkitem();

$("#myCarousel").on("slid.bs.carousel", "", checkitem);
	</script>
		<script>
// Get the modal
var modal1 = document.getElementById('myModal1');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
btn1.onclick = function() {
    modal1.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal1.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal1) {
        modal1.style.display = "none";
    }
}
</script>

<script>
function getval(){
	var category = $('#category').val();
	var expdev = $('#expdev').val();
	var formval = $('#cos_id').val();
	ajaxindicatorstart("Please Wait..");
	//e.preventDefault();
 	$.ajax({
        url: "addpip.php",
		type: "POST",
		data: {
			category:category,
			   expdev:expdev,
			   formval:formval,
		},
		success: function(data) {
			 location.reload();
         <?php $_SESSION['checkedinval']='Child';
        ?>
			 //return false;
		}
});
}
</script>
<script>
$(document).ready(function()
{
    $('#CnfrmLetter').on('click', function()
    {
        var dateval = $("#datepicker1").val();
		var Id = $('#empid').val();
		if(dateval=='')
		{
			alert("Please fill in Effective from Date!");
			return false;
		}
		else
		{
			$("#CnfrmLetter").prop("href", "GeneratePDF/DownloadEL.php?id="+Id+"&date="+dateval);
				return true;
		}
    });
});
</script>
	<script>
function btndisable(){
		document.getElementById('savefields').disabled=false;
}
</script>
</body>

</html>
