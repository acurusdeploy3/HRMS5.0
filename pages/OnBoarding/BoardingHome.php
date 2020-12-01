<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp == 'HR' || $usergrp == 'HR Manager' || $usergrp=='System Admin Manager' || $usergrp=='System Admin') 
{
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Employee Boarding</title>
  <!-- Tell the browser to be responsive to screen width -->
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
<style>
.table table-striped:hover {
  background-color: #ffff99;
}
th {
  background-color: #31607c;
}
.form_error span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: #D83D5A;
}
.form_error input {
  border: 1px solid #D83D5A;
}

/*Styling in case no errors on form*/
.form_success span {
  width: 80%;
  height: 35px;
  margin: 3px 10%;
  font-size: 1.1em;
  color: green;
}
.form_success input {
  border: 1px solid green;
}
#error_msg {
  color: red;
  text-align: center;
  margin: 10px auto;
}
.highlight-error {
  border-color: red;
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Employee Boarding

      </h1>
      <ol class="breadcrumb">
        <li><a href="../../DashboardFinal.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Boarding Home</li>
      </ol>
    </section>
	
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
	  <?php
	  if($usergrp=='System Admin Manager' || $usergrp=='System Admin')
	  {
		  include("config2.php");
		  $completed = 'Y';
		  $getAdminEmp = mysqli_query($db,"select a.employee_id,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, employee_designation,department from employee_details a inner join employee_boarding b on a.employee_id=b.employee_id where mail_type!=' ' and is_login_created='No' and a.is_active='Y'");
	  ?>
	    <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee Boarding : Admin Control Request(s)</strong></h3>
              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="table table-bordered table-striped dataTable">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;">
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th align="right">Process Request</th>
                  <th align="right">Status</th>
                </tr>
				<?php
				while($getAdminEmpRows = mysqli_fetch_assoc($getAdminEmp))
				{
				?>
                <tr>
                  <td><?php echo $getAdminEmpRows['employee_id']; ?></td>
                  <td><?php echo $getAdminEmpRows['name']; ?></td>
                  <td><?php echo $getAdminEmpRows['employee_designation']; ?></td>
                  <td><?php echo $getAdminEmpRows['department']; ?></td>
          
                   <td><a href="CompleteFormalities.php?id=<?php echo $getAdminEmpRows['employee_id'];  ?>" id="AdminFormalities"><img alt='User' src='Images/download.png' title="Click to Create" width='18px' height='18px' /></a></td>
				   <?php
				   $getBoardingAdmin = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id='".$getAdminEmpRows['employee_id']."';");
$getBoardingAdminRow = mysqli_fetch_array($getBoardingAdmin);
$SystemLogin = $getBoardingAdminRow['system_login'];
$system_login_password = $getBoardingAdminRow['system_login_password'];
$mail_login = $getBoardingAdminRow['mail_login'];
$mail_login_password = $getBoardingAdminRow['mail_login_password'];
$os_type = $getBoardingAdminRow['os_type'];
if($SystemLogin=='' || $system_login_password=='' || $mail_login=='' || $mail_login_password=='' || $os_type=='')
{
	$completed = 'N';
}
else
{
	$completed = 'Y';
}
				   if($completed=='N')
				   {
				   ?>
				   <td><span class="badge bg-red">Incomplete</td>
				   <?php
				   }
				   else
				   {
				   ?>
				     <td><span class="badge bg-green">Completed</td>
				   <?php
				   }
				   ?>
				</tr>
                <?php
				}
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	   <br>
	  <br>
	  <?php
	  }
	  ?>
	  <?php
	  if($usergrp=='System Admin Manager' || $usergrp=='System Admin')
	  {
		  include("config2.php");
		  $completed = 'Y';
		  $getAdminEmpDet = mysqli_query($db,"select a.employee_id,concat(a.first_name,' ',a.last_name,' ',a.mi) as name,b.status,b.is_active, employee_designation,department from employee_details a inner join employee_data_change_request b on a.employee_id=b.raised_for where  a.is_active='Y' and b.is_active='Y'");
	  ?>
	    <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Employee Mail Change Request</strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="table table-bordered table-striped dataTable">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;">
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th align="right">Process Request</th>
                  <th align="right">Status</th>
                </tr>
				<?php
				while($getAdminEmpDetRows = mysqli_fetch_assoc($getAdminEmpDet))
				{
				?>
                <tr>
                  <td><?php echo $getAdminEmpDetRows['employee_id']; ?></td>
                  <td><?php echo $getAdminEmpDetRows['name']; ?></td>
                  <td><?php echo $getAdminEmpDetRows['employee_designation']; ?></td>
                  <td><?php echo $getAdminEmpDetRows['department']; ?></td>
                  <td><a href="ChangeMailRequest.php?id=<?php echo $getAdminEmpDetRows['employee_id'];  ?>" id="AdminFormalities"><img alt='User' src='Images/download.png' title="Click to Create" width='18px' height='18px' /></a></td>
				   <?php
					if($getAdminEmpDetRows['status']=='Requested')
					{
						$completedstatus = 'N';
					}
					else
					{
						$completedstatus = 'Y';
					}
				   if($completedstatus=='N')
				   {
				   ?>
				   <td><span class="badge bg-red">Incomplete</td>
				   <?php
				   }
				   else
				   {
				   ?>
				     <td><span class="badge bg-green">Completed</td>
				   <?php
				   }
				   ?>
				</tr>
                <?php
				}
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	   <br>
	  <br>
	  <?php
	  }
	  ?>
	  
	  
	  
	 
	  <?php
	  if($usergrp=='HR Manager' || $usergrp=='HR')
	  {
	  ?>
	  <?php
	include("config2.php");
	$getAppIds= mysqli_query($db,"select group_concat(applicant_id) as applicant_id from employee_details where applicant_id<>0");
	$getAppIdRow = mysqli_fetch_array($getAppIds);
	$AppIds = $getAppIdRow['applicant_id'];
	?>
	 <?php
			  include("config.php");
			  include("ModificationFunc.php");

			  $getJoinees = mysqli_query($db1,"select a.applicant_id,b.first_name,b.last_name,b.mi,concat(b.first_name,' ',b.last_name,' ',b.mi) as Name,a.position_applied,b.position_applied_department,date_format(a.date_of_joining,'%d - %b - %Y') as  doj,a.position_id from applicant_tracker a
							left join applicant_Details b on a.applicant_id=b.applicant_id
							where status='Selected' and date_of_joining<=curdate() and date_of_joining not in ('0000-00-00 00:00:00','0001-01-01 00:00:00') and a.applicant_id not in ($AppIds) and a.position_id!='' order by date_of_joining desc");
		?>
         <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Expected Joinee(s) Till Today : <?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y'); ?></strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;">
                   <th style="display:none">Applicant Full Name <?php echo mysqli_num_rows($getJoinees); ?></th>
                  <th>Applicant ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>MI</th>
                  <th>Expected DOJ</th>
				   <th>Position MRF ID</th>
                  <th>Position Applied</th>
                  <th>Department</th>
                 
                  <th align="right">Create Employee</th>
                  <th align="right">Did Not Report</th>
                </tr>
				<?php
				while($getAffectedRows = mysqli_fetch_assoc($getJoinees))
				{
				?>
                <tr>
                  <td style="display:none" class="AppFullName"><?php echo $getAffectedRows['Name']; ?></td>
                  <td class="AppId"><?php echo $getAffectedRows['applicant_id']; ?></td>
                  <td class="AppFirstName"><?php echo $getAffectedRows['first_name']; ?></td>
                  <td class="AppLastName"><?php  if($getAffectedRows['last_name']!='') { echo $getAffectedRows['last_name']; } else { echo '--'; } ?></td>
                  <td class="AppMIName"><?php if($getAffectedRows['mi']!='') { echo $getAffectedRows['mi']; } else { echo '--'; } ?></td>
                  <td class="DateofJoining"><?php echo $getAffectedRows['date_of_joining']; ?></td>
                  <td class="PositionMRF"><input type='hidden' name='PositionMRFHid' class="PositionMRFHid" value='<?php echo $getAffectedRows['position_id']; ?>'/><?php echo $getAffectedRows['position_id']; ?></td>
                  <td class="PosApplied"><?php echo $getAffectedRows['position_applied']; ?></td>
                  <td class="PosAppliedDept"><?php echo $getAffectedRows['position_applied_department']; ?></td>
                  <td><a href="#" class="NewEmployeeCreate" id="additionalBand" data-toggle="modal" data-target="#modal-default-create"><img alt='User' src='Images/newuser.png' title="Create AHRMS User" width='18px' height='18px' /></a></td>
                   <td><a href="#" id="NoReported" data-toggle="modal" data-target="#modal-default-Remove"><img alt='User' src='Images/notrep.png' title="Modify DOJ / Remove" width='18px' height='18px' /></a></td>
				</tr>
                <?php
				}
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>
	  <br>
	  <br>
	 <?php
	 include("config2.php");
	 $getReportedEmployees = mysqli_query($db,"select a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.MI) as name,if(aeds_doc_id='','NA',aeds_doc_id) as aeds_doc_id,
	 date_format(date_of_joining,'%d - %b - %Y') as date_of_joining,
if(hipaa_doc_id='','NA',hipaa_doc_id) as hipaa_doc_id,
if(blg_doc_id='','NA',blg_doc_id) as blg_doc_id,
if(conf_doc_id='','NA',conf_doc_id) as conf_doc_id,
if(nda_doc_id='','NA',nda_doc_id) as nda_doc_id,
b.position_applied_for,b.is_personal_data_filled
from employee_boarding a
left join employee_details b on a.employee_id=b.employee_id

where is_formalities_completed='N' and b.is_active='Y' order by a.employee_id");

	 ?>
		  <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Reported Joinee(s)</strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table id="ReportedJoinees" align="center" class="table table-striped">
			    <thead style="Color:White;">
                
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>Position Applied</th>
                  <th>AEDS Form</th>
                  <th>Commence Formalities</th>
                </thead>
				<?php
			  if(mysqli_num_rows($getReportedEmployees)>0)
			  {
				  while($getEmployees = mysqli_fetch_assoc($getReportedEmployees))
				  {
			  ?>
				<tr>
				  <td><?php echo $getEmployees['employee_id'] ?></td>
                  <td><?php echo $getEmployees['name'] ?></td>
                  <td><?php echo $getEmployees['date_of_joining'] ?></td>
                  <td><?php echo $getEmployees['position_applied_for'] ?></td>
			   <td><?php if($getEmployees['is_personal_data_filled']=='Y') { ?><a href="EmployeeDataSheet.php?id=<?php echo $getEmployees['employee_id']; ?>">View</a> <?php  } else { echo 'NA';} ?></td>
				<td><a href="CompleteFormalities.php?id=<?php echo $getEmployees['employee_id']; ?>"><img alt='User' src='Images/download.png' title="Commence Formalities" width='18px' height='18px' /></a></td>
				</tr>

				<?php
				  }
			  }
			  else
			  {
				?>
					<tr>

                  <td colspan="6">No Joinee(s) Reported</td>


				</tr>
				<?php
			  }
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>

	  <br>
	  <br>
	   <?php
	 include("config2.php");
	 $getJoinedEmployees = mysqli_query($db,"select a.employee_id,date_format(a.date_of_joining,'%d - %b - %Y') as
date_of_joining,concat(b.first_name,' ',b.last_name,' ',b.MI) as name,are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed,b.is_personal_data_filled
from employee_boarding a
left join employee_details b on a.employee_id=b.employee_id

where is_formalities_completed='P' and b.is_active='Y' order by a.employee_id");


	 ?>
	   <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Incomplete Employee Formalities</strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table id="IncompleteFormalities" align="center" class="table table-striped">
				<thead style="Color:White;">
                <th style="display:none"></th>
                 <th style="display:none"></th>
                 <th style="display:none"></th>
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Date of Joining</th>
                  <th>AEDS Form</th>
                  <th>Completion %</th>
                  <th>Status</th>
                  <th>Finish</th>
                </thead>
				<?php
			  if(mysqli_num_rows($getJoinedEmployees)>0)
			  {
				  while($getJEmployees = mysqli_fetch_assoc($getJoinedEmployees))
				  {
					  $i=0;
					  if($getJEmployees['is_data_sheet_completed']=='Y') { $i = $i+1; }
					  if($getJEmployees['is_provisions_completed']=='Y') { $i = $i+1; }
					  if($getJEmployees['is_designated']=='Y') { $i = $i+1; }
			  ?>
				<tr>
                
				  <td class="DocsUploaded" style="display:none;"><?php echo $getJEmployees['is_data_sheet_completed']; ?></td>
				  <td  class="ProvisionsCompleted" style="display:none;"><?php echo $getJEmployees['is_provisions_completed']; ?></td>
				  <td  class="DesnCompleted" style="display:none;"><?php echo $getJEmployees['is_designated']; ?></td>
				  <td><?php echo $getJEmployees['employee_id'] ?></td>
                  <td><?php echo $getJEmployees['name'] ?></td>

                  <td><?php echo $getJEmployees['date_of_joining'] ?></td>
                  <td><?php if($getJEmployees['is_personal_data_filled']=='Y') { ?><a href="EmployeeDataSheet.php?id=<?php echo $getJEmployees['employee_id']; ?>">View</a> <?php  } else { echo 'NA';} ?></td>
                  <td><span class="badge bg-blue"><?php echo round(($i/3)*(100)).' %'; ?></td>
				   <td><a href="#" id="additionalBand" data-toggle="modal" data-target="#modal-default-Pending"><img alt='User' src='Images/img_231067.png' title="View Pending Formalities" width='18px' height='18px' /></a></td>
				  <td><a href="CompleteFormalities.php?id=<?php echo $getJEmployees['employee_id']; ?>"><img alt='User' src='Images/download.png' title="Complete Formalities" width='18px' height='18px' /></a></td>
				</tr>

				<?php
				  }
			  }
			  else
			  {
				?>
					<tr>

                  <td>No Incomplete Employee Formalities!</td>


				</tr>
				<?php
			  }
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>




	  <br>
	  <br>
	   <?php
	 include("config2.php");
	 $getReportedEmployees = mysqli_query($db,"select a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.MI) as name,if(aeds_doc_id='','NA',aeds_doc_id) as aeds_doc_id,is_personal_data_filled,
if(hipaa_doc_id='','NA',hipaa_doc_id) as hipaa_doc_id,
if(blg_doc_id='','NA',blg_doc_id) as blg_doc_id,
if(conf_doc_id='','NA',conf_doc_id) as conf_doc_id,
if(nda_doc_id='','NA',nda_doc_id) as nda_doc_id
from employee_boarding a
left join employee_details b on a.employee_id=b.employee_id

where is_formalities_completed='Y' and a.are_documents_uploaded='N' and b.is_active='Y'");
	 ?>
	  <div class="row">
        <div class="col-xs-12">

            <div class="box-header">
              <h3 class="box-title" style="Color:#3c8dbc"><strong>Upload Boarding Documents</strong></h3>

              <div class="box-tools">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table name="JoinEmp" id="JoinEmp" align="center" class="table table-hover">
                <tr style="Color:White;" >
				  <th>Employee ID</th>
                  <th>Name</th>
                  <th>AEDS Form</th>
                  <th>AEDS Form (Office Use)</th>
               
                  <th>HIPAA Document</th>
                  <th>Upload Signed Documents</th>
                </tr>
				<?php
			  if(mysqli_num_rows($getReportedEmployees)>0)
			  {
				  while($getEmployees = mysqli_fetch_assoc($getReportedEmployees))
				  {
			  ?>
				<tr>
				  <td><?php echo $getEmployees['employee_id'] ?></td>
                  <td><?php echo $getEmployees['name'] ?></td>


                 <td><?php if($getEmployees['is_personal_data_filled']=='Y') { ?><a href="EmployeeDataSheet.php?id=<?php echo $getEmployees['employee_id']; ?>">View</a> <?php  } else { echo 'NA';} ?></td>
                <td align="center"><a href="EmployeeDataSheetOU.php?id=<?php echo $getEmployees['employee_id']; ?>">View</a></td>
                <td ><i class="fa fa-fw fa-download"></i><a href="GeneratePDF/HIPAADownload.php?id=<?php echo $getEmployees['name']; ?>">Download</a></td>

                  <td><a href="UploadSignedDocuments.php?id=<?php echo $getEmployees['employee_id']; ?>"><img alt='User' src='Images/upload.png' title="Upload Signed Documents" width='18px' height='18px' /></a></td>
				</tr>

				<?php
				  }
			  }
			  else
			  {
				?>
					<tr>

                  <td>No Data Found</td>


				</tr>
				<?php
			  }
				?>
              </table>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <!-- /.col -->
      </div>

<br>
	  <br>
<button OnClick="window.location.href='../tables/AuditLog.php?id=Employee Boarding'" type="button" class="btn btn-primary">View Audit Log</button>
<?php
	  }
	  ?>
      <!-- /.row -->

      <!-- Table row -->
	  <br>
	  <br>
      <div class="row">
        <div class="col-xs-12 table-responsive">

	<div class="modal fade" id="modal-default-create">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close closeButton" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Employee</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php
				include("config2.php");
				$getHighestEmpId = mysqli_query($db,"select employee_id from employee_details order by employee_id desc limit 1");
				$getHighestEmpIdRow = mysqli_fetch_array($getHighestEmpId);
				$HighestEmpId = $getHighestEmpIdRow['employee_id'];
				$SuggestedEmpId = $HighestEmpId+1;
				$getAllRoles = mysqli_query($db,"SELECT role_Desc FROM `all_job_roles`");
		   ?>
            <form id="EmployeeForm" method="post" action="">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
				<div class="form-group">
					<label>Employee Name<span class="asterix" style="color:red;">*</span></label>
					<input type="text" class="form-control pull-right"  required="required" name="EmployeeFullName" id="EmployeeFullName" placeholder="Employee Name" required />	
					<br>
					<br>
					<br>
                </div>
			</div>
          <div class="col-md-6">
				<div class="form-group">
					<label>Aadhaar Number<span class="asterix" style="color:red;">*</span></label>
					<input type="text" class="form-control pull-right" data-type="adhaar-number" onKeyPress="return isNumberKey(event)"  required="required" maxLength="14" name="EmployeeAadhaar" id="EmployeeAadhaar" placeholder="Enter Aadhaar Number" required />	
					<br>
					<br>
					<br>
              </div>
			</div>
			<div class="col-md-12">
			</div>
		  <div class="col-md-6">
              <div class="form-group">

			  <input type="hidden" name="AppID" id="AppID" />
			  <input type="hidden" name="FirsName" id="FirsName" />
			  <input type="hidden" name="LastName" id="LastName" />
			  <input type="hidden" name="MI" id="MI" />
			  <input type="hidden" name="MRF" id="MRF" />
			  <input type="hidden" name="Department" id="Department" />
                 <label >Employee ID :&nbsp;&nbsp (Next Available ID : <label id="NewEmpID"></label> )<span class="asterix" style="color:red;">*</span></label>
                 <input type="number" class="form-control pull-right" min="0" required="required" name="EmployeeID" id="EmployeeID" placeholder="Enter Employee ID" >
				 <span></span>
              </div>


            </div>
            <div class="col-md-6">
               <div class="form-group">
                 <label>Employee Role<span class="asterix" style="color:red;">*</span></label>
               <select class="form-control select2" id="EmployeeRole" name="EmployeeRole" required="required" style="width: 100%;" required>
                 <option value="" selected >Please Select from Below</option>
				 <?php
				 while($getRoleRow = mysqli_fetch_assoc($getAllRoles))
				 {
				 ?>
                 <option value="<?php echo $getRoleRow['role_Desc'] ?>"><?php echo $getRoleRow['role_Desc'] ?></option>
                <?php
				 }
				 ?>
				</select>
              </div>


              <!-- /.form-group -->





              <!-- /.form-group -->
            </div>
		<div class="col-md-6">
               <div class="form-group">
                 <label>Employment Type</label>
			  <input type="text" class="form-control pull-right"  required="required" name="JobType" id="JobType" placeholder="Job Type" required readonly />	
              </div>
              <!-- /.form-group -->

              <!-- /.form-group -->
            </div>
			<div class="col-md-6">
               <div class="form-group">
                 <label>Probation Period (in Months)</label>
               <select class="form-control select2" id="ProbationPeriod" name="ProbationPeriod" style="width: 100%;" required disabled>
                 <option value="" disabled>Please Select from Below</option>
                 <option value="1">0</option>
                 <option value="1">1</option>
                 <option value="2">2</option>
                 <option value="3">3</option>
                 <option value="4">4</option>
                 <option value="5">5</option>
                 <option value="6" selected>6</option>
                 <option value="7">7</option>
                 <option value="8">8</option>
                 <option value="9">9</option>
                 <option value="10">10</option>
                 <option value="11">11</option>
                 <option value="12">12</option>
                 
				</select>
              </div>
              <!-- /.form-group -->

              <!-- /.form-group -->
            </div>
			
				<div class="col-md-6">
               <div class="form-group">
                 <label>Temporary Duration (in Months)</label>
               <select class="form-control select2" id="ContractPeriod" name="ContractPeriod" style="width: 100%;" required disabled>
                 <option value="" selected disabled>Please Select from Below</option>
                 <option value="1">0</option>
                 <option value="1">1</option>
                 <option value="2">2</option>
                 <option value="3">3</option>
                 <option value="4">4</option>
                 <option value="5">5</option>
                 <option value="6">6</option>
                 <option value="7">7</option>
                 <option value="8">8</option>
                 <option value="9">9</option>
                 <option value="10">10</option>
                 <option value="11">11</option>
                 <option value="12">12</option>
                 
				</select>
              </div>
              <!-- /.form-group -->

              <!-- /.form-group -->
            </div>
            <!-- /.col -
			
            <!-- /.col -->
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole1" onclick="clearFields();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="CreateEmployee" value="Create Employee" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>










		   <div class="modal fade" id="modal-default-Pending">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Completion Status</h4>
              </div>
            <div class="modal-body">
			 <div id="DocsCompletion" class="">
               <label class="control-label" for="inputSuccess"> <i id="DocCompletionIcon" class=""> </i> &nbsp; Employee Data Sheet (Office Use)</label>

			     </div>
				   <br>
				  <div id="ProvisionCompletion" class="">
              <label class="control-label" for="inputSuccess"><i id="ProvisionCompletionIcon" class=""> </i> &nbsp; Employee Provisions</label>
			  </div>
			   <br>
			    <div id="EmployeementCompletion" class="">
              <label class="control-label" for="inputSuccess"> <i id="EmployeementCompletionIcon" class=""> </i> &nbsp; Employment Formalities</label>
			   </div>
			  <br>
			  <br>
			   <h6>*Completed Formalities are marked Green, Incompleted as Red.</h6>

              </div>
              </div>

              <div class="modal-footer">
                <button type="button" id="CloseNot" class="btn btn-default pull-right" data-dismiss="modal">Close</button>

              </div>
            </div>
            <!-- /.modal-content -->
          </div>








































		  <div class="modal fade" id="modal-default-Remove">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify DOJ / Remove</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
           <?php

		   ?>
            <form id="RemoveForm" action="ModifyJoinee.php" method="post">
        <div class="box-body">
          <div class="row">
		  <div class="col-md-6">
              <div class="form-group">

			  <input type="hidden" name="AppIDRemove" id="AppIDRemove" />
			  <input type="hidden" name="FirsName" id="FirsName" />
			  <input type="hidden" name="LastName" id="LastName" />
			  <input type="hidden" name="MI" id="MI" />
			  
                 <label>Reason for not Reporting</label>
                 <input type="text" class="form-control pull-right" name="ReasonNotReport" id="ReasonNotReport" placeholder="Enter Reason" required="required" />
              </div>
			<div class="form-group">
				<label>Modified Date of Joining</label>
                 <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text"  name="DOJDate" class="form-control pull-right" id="datepicker1" required>
                </div>
              </div>

            </div>
            <div class="col-md-6">
               <div class="form-group">
                 <label>Status</label>
               <select class="form-control select2" id="JoineeStatus" name="JoineeStatus" onchange="changetextbox();" style="width: 100%;" required>
                 <option value="" selected disabled>Please Select from Below</option>
                 <option value="Remove">Remove Joinee</option>
                 <option value="Modify DOJ"  >Modify Date of Joining</option>
				</select>
              </div>
              <!-- /.form-group -->

              <!-- /.form-group -->
            </div>

            <!-- /.col -

            <!-- /.col -->
          </div>

</div>
          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" onclick="clearFields1();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="SubmitData" value="Submit" class="btn btn-primary" />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>




























        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <!-- /.row -->

      <!-- this row will not appear when printing -->

    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong>
  </footer>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
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
$('[data-type="adhaar-number"]').keyup(function() {
  var value = $(this).val();
  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
  $(this).val(value);
});

$('[data-type="adhaar-number"]').on("change, blur", function() {
  var value = $(this).val();
  var maxLength = $(this).attr("maxLength");
  if (value.length != maxLength) {
    $(this).addClass("highlight-error");
  $('#CreateEmployee').prop('disabled', true);
  } else {
    $(this).removeClass("highlight-error");
  	$('#CreateEmployee').prop('disabled', false);
  }
});
</script>
<script>
function ClearData() {
  document.getElementById("EmployeeForm").reset();
}
</script>
<script type="text/javascript">
   $(document).on('click','#ModifyTraining',function(e) {
		ajaxindicatorstart("Please Wait..");
 });
</script>

<script>
$('#EmployeeID').on('blur', function() {
	
	var EmpId = $('#EmployeeID').val();
    //ajax request
   $.ajax({
      url: 'employeeCheck.php',
      type: 'post',
      data: {
      	'email' : EmpId,
      },
      success: function(response){
      	if (response == 'taken' ) {
          email_state = false;
          $('#EmployeeID').parent().removeClass();
          $('#EmployeeID').parent().addClass("form_error");
          $('#EmployeeID').siblings("span").text('ID Unavailable.');
		  $('#CreateEmployee').prop('disabled', true);
		   return false;
      	}else if (response == 'not_taken') {
      	  email_state = true;
      	  $('#EmployeeID').parent().removeClass();
      	  $('#EmployeeID').parent().addClass("form_success");
      	  $('#EmployeeID').siblings("span").text('ID Available.');
		    $('#CreateEmployee').prop('disabled', false);
		 
      	}
      }
 	});
 });
</script>

<script type="text/javascript">

function changetextbox()
{
    if (document.getElementById("JoineeStatus").value === "Remove")
		{

		document.getElementById("datepicker1").disabled=true;
	}
	else
	{
		document.getElementById("datepicker1").disabled=false;
	}

}
</script>
<script type="text/javascript">

function changeType()
{
    if (document.getElementById("JobType").value === "Permanent")
		{

		document.getElementById("ProbationPeriod").disabled=false;
		document.getElementById("ContractPeriod").disabled=true;
	}
	else
	{
		document.getElementById("ContractPeriod").disabled=false;
		document.getElementById("ProbationPeriod").disabled=true;
	}

}
</script>
<script>

	$(function() {
  var bid, trid;
  $('#JoinEmp tr').click(function() {
       Id = $(this).find('.AppId').text();
       FirstName = $(this).find(".AppFirstName").text();
	   LastName = $(this).find(".AppLastName").text();
	   MiddleName = $(this).find(".AppMIName").text();
	   PosAPplied = $(this).find(".PosApplied").text();
	   PosMRF = $(this).find(".PositionMRF").text();
	   PosDept = $(this).find(".PosAppliedDept").text();
 	   EmpFullName = $(this).find(".AppFullName").text();
		$('#AppID').val(Id);
		$('#AppIDRemove').val(Id);
		$('#FirsName').val(FirstName);
		$('#LastName').val(LastName);
		$('#MI').val(MiddleName);
		$('#MRF').val(PosMRF);
		$('#Department').val(PosDept);
  $('#EmployeeFullName').val(EmpFullName);
  });
});
	</script>
<script>

	$(function() {
  var bid, trid;
  $('#IncompleteFormalities tr').click(function() {

       DocsCompleted = $(this).find(".DocsUploaded").text();
	   EmpFornalities = $(this).find(".DesnCompleted").text();
	   Provision = $(this).find(".ProvisionsCompleted").text();
	   if(DocsCompleted=='Y')
	   {
			
			$('#DocsCompletion').attr('class',"form-group has-success");
		
			$('#DocCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
		 
			$('#DocsCompletion').attr('class',"form-group has-error");
			$('#DocCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }

	  if(Provision=='Y')
	   {
		
				$('#ProvisionCompletion').attr('class',"form-group has-success");
				$('#ProvisionCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
		  
			$('#ProvisionCompletion').attr('class',"form-group has-error");
			$('#ProvisionCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }


	   if(EmpFornalities=='Y')
	   {

				$('#EmployeementCompletion').attr('class',"form-group has-success");
				$('#EmployeementCompletionIcon').attr('class',"fa fa-check");
	   }
	   else
	   {
			$('#EmployeementCompletion').attr('class',"form-group has-error");
			$('#EmployeementCompletionIcon').attr('class',"fa fa-times-circle-o");
	   }

  });
});
	</script>
<script>

</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#EmployeeForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#EmployeeForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "CreateEmployee.php",
         success: function(data){

		location.reload();
		   ajaxindicatorstop();

         }
});

});
});
    </script>
<!-- Page script -->
<script type="text/javascript">
//note also the proper type declaration in script tags, with NO SPACES (IMPORTANT!)

function addVal(){
   document.getElementById("AllocatedPer").stepUp(5);
}
function addValMod(){
   document.getElementById("ModifiedAllocatedPer").stepUp(5);
}
function SubVal(){
    document.getElementById("AllocatedPer").stepDown(5);
}
function SubValMod(){
    document.getElementById("ModifiedAllocatedPer").stepDown(5);
}
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
	 $('#datepicker1').datepicker({
      autoclose: true,
	  startDate: 'd'
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
   $(document).on('click','#ModifyTraining',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#trainingForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "InsertModifiedTraining.php",
         success: function(data){

		 window.location.href = "TrainingMgmt.php";
		   ajaxindicatorstop();

         }
});

});
    </script>
	<script type="text/javascript">
   $(document).on('click','#closeRole',function(e) {
		 location.reload();	
});
    </script>
	<script type="text/javascript">
   $(document).on('click','#closeRole1',function(e) {
		 location.reload();	
});
    </script>
<script type="text/javascript">
   $(document).on('click','.closeButton',function(e) {
		 location.reload();	
});
    </script>
	<script>

	$(function() {
  var bid, trid;
  $('#ActProj tr').click(function() {
       trid = $(this).attr('id');
       trname = $(this).attr('name');

       rem = $('#remVal1').val();

	   maxval =parseInt(trid, 10) +parseInt(rem, 10);
		$('#ModifiedAllocatedPer').val(trid);
		$('#rowId').val(trname);
		$("#ModifiedAllocatedPer").attr({
		"max" : maxval
    });
		// table row ID
       //alert(trid);
  });
});
	</script>

	<script type="text/javascript">
	$('#ProjSelect').one('change', function() {

			$('#AddProj').prop('disabled', false);
	});

	</script>

	<script type="text/javascript">
   $(document).on('submit','#AddPro1j',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ProForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "InsertNewProj.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>



	<script type="text/javascript">
   $(document).on('click','#ModifyAlloc',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#ModForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "ModProAlloc.php",
         success: function(data){

			location.reload();
		   ajaxindicatorstop();

         }
});

});
    </script>

<script type="text/javascript">
     $('.NewEmployeeCreate').click(function() {
		    var row = $(this).closest("tr");   
			var MRFID = row.find(".PositionMRFHid").val(); 
  ajaxindicatorstart("Please Wait..");
  $.ajax({
          data: {

      	'MRFID' : MRFID,
      },
         type: "post",
         url: "GetEmployeeID.php",
		 dataType: "json",
         success: function(data){
			  $('#NewEmpID').text(data.result.suggested);
			  $('#JobType').val(data.result.job_type);
			  if(data.result.job_type=='Permanent')
				{
						$("#ProbationPeriod").prop("disabled", false);
						$("#ContractPeriod").prop("disabled", true);
				}
				else
				{
					$("#ProbationPeriod").prop("disabled", true);
					$("#ContractPeriod").prop("disabled", false);
				}
			  ajaxindicatorstop();
         }
});
 });
    </script>




	<script type="text/javascript">
   $(document).on('click','#TestTraining',function(e) {

	ajaxindicatorstart("Please Wait..");
});
    </script>
	<script type="text/javascript">
    $(document).on('click','#editClick',function(e){
        $("#ResForm :input").prop("disabled", false);
    });
</script>
<script>
  $(function () {
  $('#ReportedJoinees').DataTable()
  $('#IncompleteFormalities').DataTable()
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
<script type="text/javascript">
       $(document).on('click','#UploadDoc',function(e) {
		//var data = $("#ResumeFileDoc").files;
		 e.preventDefault();
		ajaxindicatorstart("Please Wait..");
  $.ajax({
		type: 'POST',
		url: 'UploadLOA.php',
		data: new FormData('#roleForm'),
		contentType: false,
		cache: false,
		processData:false,
		url: "UploadLOA.php",
		 enctype: 'multipart/form-data',
         success: function(data){
			alert(data);
			 window.location.href = "ViewResource.php?id="+id;
			  ajaxindicatorstop();
         }
});
 });
    </script>

<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>