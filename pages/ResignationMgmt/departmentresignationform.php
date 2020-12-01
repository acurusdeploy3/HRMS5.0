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
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image,business_unit from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$location = $useridrow['business_unit'];


$certQuery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,res_id_value,
reason_for_resignation, status, if((date_format(date(date_of_leaving),'%d %b %Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%d %b %Y'))) as dl,
if((date_format(date(date_of_leaving),'%d-%b-%Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%Y-%m-%d'))) as dll,
date_format(date(actual_date_of_leaving),'%d %b %Y') as adl,ed.department,
employee_comments,reporting_manager_comments,hr_comments,process_queue,
date_format(date(ei.modified_date_and_time),'%d %b %Y') as modified_date_and_time,ei.reporting_manager_id,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process','Exit Process Initiated',''))))))) as queue,
(select concat(First_name,' ',MI,' ',Last_Name) as Manager_Name from employee_details where employee_id=ei.reporting_manager_id) as Manager_Name,
(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,(SELECT ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id and date(date_availed) >= date(date_of_submission_of_resignation)  and leave_type in ('CL','PL','SL')
and cancled='N'),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id
and date(date_availed) >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)
  and leave_type in ('CL','PL','SL')
and cancled='N'))
) as leave_count,
(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >= date(date_of_submission_of_resignation) and date <= date_of_leaving
and leave_type='LOP' and permission_type in ('','Half Day')),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)

and leave_type='LOP' and permission_type in ('','Half Day')
  ))
) as lop_count,
(select concat(First_name,' ',MI,' ',Last_Name) as Hod_Name from employee_details where employee_id=h.employee_id) as Hod_Name
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id
inner join all_hods h on ed.department=h.department
where (process_queue='Manager_Process' or process_queue='HR_Manager_Hold') and ei.employee_id <> $userid and h.location=ed.business_unit and concat($userid,'-hod')=ei.pending_queue_id
order by date(date_of_leaving)  asc");
$histquery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,res_id_value,
reason_for_resignation, if(status='Process_Completed','Process Completed',if(status='Cancel Resignation','Resignation Cancelled',status)) as status,   
if((date_format(date(date_of_leaving),'%d %b %Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%d %b %Y'))) as dl,
date_format(date(actual_date_of_leaving),'%d %b %Y') as adl,
date_format(date(date_of_cancellation_of_resignation),'%d %b %Y') as dc,ed.department,
employee_comments,reporting_manager_comments,hr_comments,process_queue,ei.reporting_manager_id,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled' || process_Queue='HOD_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process' && status='Process Resignation','Exit Process Initiated',
if(status='Process_Completed','Exit Process Completed','')))))))) as queue,hod_comments,
(select concat(First_name,' ',MI,' ',Last_Name) as Manager_Name from employee_details where employee_id=ei.reporting_manager_id) as Manager_Name,
(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,(SELECT ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id and date(date_availed) >= date(date_of_submission_of_resignation)  and leave_type in ('CL','PL','SL')
and cancled='N'),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id
and date(date_availed) >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)
  and leave_type in ('CL','PL','SL')
and cancled='N'))
) as leave_count,
(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >= date(date_of_submission_of_resignation) and date <= date_of_leaving
and leave_type='LOP' and permission_type in ('','Half Day')),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)

and leave_type='LOP' and permission_type in ('','Half Day')
  ))
) as lop_count,
(select concat(First_name,' ',MI,' ',Last_Name) as Hod_Name from employee_details where employee_id=h.employee_id) as Hod_Name
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id
inner join all_hods h on ed.department=h.department
where ei.employee_id <> $userid and h.location=ed.business_unit and ed.department in (select department from all_hods where employee_id=$userid)  and ei.is_active='N' 
order by date(date_of_leaving) asc ");
$certQuery1 = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,res_id_value,
reason_for_resignation, status,  date_format(date(date_of_leaving),'%d %b %Y') as dl,
employee_comments,reporting_manager_comments,process_queue,exit_interview_status,
no_due_sysadmin_status,no_due_acc_status,no_due_manager_status,no_due_hr_status,res_id_value
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id where SUBSTRING_INDEX(allocated_to, '-', -1)=$userid and ei.is_active='Y' and ei.employee_id <> $userid and ed.department in (select department from all_hods where employee_id=$userid)
order by date(date_of_leaving)  asc");

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Resignation Management</title>
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
.progressbar {
      counter-reset: step;
  }
  .progressbar li {
      list-style-type: none;
      width: 20%;
      float: left;
      font-size: 11px;
      position: relative;
      text-align: center;
      <!--text-transform: uppercase; -->
      color: #dd0606;
  }
  .progressbar li:before {
      width: 30px;
      height: 30px;
      content: counter(step);
      counter-increment: step;
      line-height: 30px;
      border: 2px solid #dd0606;
      display: block;
      text-align: center;
      margin: 0 auto 10px auto;
      border-radius: 50%;
      background-color: white;
  }
  .progressbar li:after {
      width: 100%;
      height: 2px;
      content: '';
      position: absolute;
      background-color: #dd0606;
      top: 15px;
      left: -50%;
      z-index: -1;
  }
  .progressbar li:first-child:after {
      content: none;
  }
  .progressbar li.active {
      color: green;
  }
  .progressbar li {
      color: #dd0606;
  }
  .progressbar li.active:before {
      border-color: #55b776;
  }
  .progressbar li.active + li:after {
      background-color: #55b776;
  }
  .close123 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close123:hover,
.close123:focus {
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
				  <th>Reason</th>
				  
				  <th>Date of Leaving</th>
			      <th>Status</th>
				  <th>View/Edit</th>		
                </thead>
             <tbody>
                <?php
                if(mysqli_num_rows($certQuery) < 1){
                  //echo "<tr><td cols-span='8'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($certQuery)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row['employee_id']."</td>";
					echo "<td class='resgnid' style='width:5%;display:none;'>".$row['resignation_id']."</td>";
					echo "<td style='width:15%'>".$row['Name']."</td>";
					echo "<td style='width:9%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i>";
					echo "<input type='hidden' class='reason_for_resignation' value='".$row['reason_for_resignation']."'></input>";
                  	echo "<input type='hidden' class='Department' value='".$row['department']."'></input>";
					echo "<input type='hidden' class='ResIdvalue' value='".$row['resignation_id']."'></input>";
					echo "<input type='hidden' class='employee_comments' value='".$row['employee_comments']."'></input>";
					echo "<input type='hidden' class='Manager_Name' value='".$row['Manager_Name']."'></input>";
					echo "<input type='hidden' class='Hod_Name' value='".$row['Hod_Name']."'></input>";
					echo "<input type='hidden' class='queue' value='".$row['reporting_manager_comments']."'></input>";
					echo "<input type='hidden' class='adl' value='".$row['adl']."'></input>";
					echo "<input type='hidden' class='dl' value='".$row['dl']."'></input>";
					echo "<input type='hidden' class='dateleft' value='".$row['dll']."'></input>";
					echo "<input type='hidden' class='leave_count' value='".$row['leave_count']."'></input></td>";
                  echo "<input type='hidden' class='lop_count' value='".$row['lop_count']."'></input></td>";
                    echo "<input type='hidden' class='ads' value='".$row['ds']."'></input></td>";
                  
					$empcomments=mysqli_real_escape_string($db,$row["employee_comments"]);
					echo "<input type='hidden' class='employee_comments' value='$empcomments'></input></td>";
					
					echo "<td style='width:17%'>".$row['dl']."</td>";
					echo "<td style='width:17%'>".$row['queue']."</td>";
				if($row['process_queue'] == 'Manager_Process')
					echo "<td style='width:17%'>
					<a href='#' id='myBtn'  data-toggle='modal' data-target='#myModal'><i class='fa fa-pencil-square-o' id='faicon'></i>Approve/Deny</a></td>";
					echo "</tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
			 <div id="myModal" class="modal">
			
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p></p>
						<form id="statuschange" method="POST">
							<input type="hidden" value="" name="EmpIdvalue" id="EmpIdvalue"/>
							<input type="hidden" value="" name="ResIdvalue" id="ResIdvalue"/>
							<label for="inputcomments" class="col-sm-3 control-label">Comments <span class="error">*</span></label>
								<input type="text"  class="form-control" id="hod_comments" name="hod_comments"   style="width:90%" required><br>
								<input name="btnApprove" id="btnApprove" type="button" class = "btn btn-primary" onclick='updatestatus(this.value)' value = "Approve"/>
								<input  name="btnDeny" id="btnDeny" type="button" class = "btn btn-danger" onclick='updatestatus(this.value)' value = "Deny"/>

								
						</form>
						</div>	
					</div>
            </table>
          </div>
          </div>
          <div class="box box-info" style="width:110%;">
		<!--<input type="button" id="btnhistory" name="btnhistory" value="View History of Resignations" style="margin-left: 7px;"  onclick="opendiv();"></input>-->
		  <br><br>
		  <div class="border-class w3-container w3-show" id="historydiv" >
           <h4 style="margin-left: 7px;font-weight: bold;color: tomato;">RESIGNATION HISTORY</h4>
            <table  id="histtable" class="table" style="font-size:14px;">
             
                <thead>
                  <th style="width: 10px">#</th>
				  <th>Empl. ID</th>
				  <th>Name</th>
				  <th>Reporting Manager ID</th>
				  <th>Reason</th>
				  
                  <th>Date of (Leaving or Cancellation)</th>
			      <th>Status</th>	
                </thead>
             <tbody>
                <?php
                if(mysqli_num_rows($histquery) < 1){
                  //echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row15 = mysqli_fetch_assoc($histquery)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
					echo "<td style='width:5%'>".$row15['employee_id']."</td>";
					echo "<td style='width:15%'>".$row15['Name']."</td>";
					echo "<td style='width:5%'>".$row15['reporting_manager_id']."</td>";
					echo "<td style='width:9%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i>";
                  	echo "<input type='hidden' class='Department' value='".$row15['department']."'></input>";
					echo "<input type='hidden' class='employee_comments' value='".$row15['employee_comments']."'></input>";
					echo "<input type='hidden' class='Manager_Name' value='".$row15['Manager_Name']."'></input>";
					echo "<input type='hidden' class='Hod_Name' value='".$row15['Hod_Name']."'></input>";
					echo "<input type='hidden' class='reason_for_resignation' value='".$row15['reason_for_resignation']."'></input>";
					echo "<input type='hidden' class='queue' value='".$row15['reporting_manager_comments']."'></input>";
					echo "<input type='hidden' class='adl' value='".$row15['adl']."'></input>";
					echo "<input type='hidden' class='dl' value='".$row15['dl']."'></input>";
					echo "<input type='hidden' class='leave_count' value='".$row15['leave_count']."'></input></td>";
                  	echo "<input type='hidden' class='hod_comments' value='".$row15['hod_comments']."'></input></td>";
                  echo "<input type='hidden' class='lop_count' value='".$row15['lop_count']."'></input></td>";
                  echo "<input type='hidden' class='ads' value='".$row15['ds']."'></input></td>";
					if($row15['status']=='Resignation Cancelled'){
					
                    echo "<td style='width:5%'>".$row15['dc']."</td>";}
					else if($row15['status']=='Process Completed'){
                    
                    echo "<td style='width:5%'>".$row15['dl']."</td>";}
					echo "<td style='width:17%'>".$row15['queue']."</td></tr>";
                    $i++;
                  }
				}
                ?>
              </tbody>
            </table>
          </div>
				<div id="datamodel" class="modal">
					<div class="modal-content">
						<span class="close123" data-dismiss="modal">&times;</span>
						<p style="font-size: 22px;color: #eb6027;">DETAILS</p>
						<table class="table">
							<tr>
								<td><label for="inputName" >Department</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;"  name="dept" id="dept" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Manager</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;"  name="mdet" id="mdet" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >HOD</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;"  name="hdet" id="hdet" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Reason for Resignation</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;"  name="reason" id="reason" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Employee Comments</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="empcomments" id="empcomments" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Manager Comments</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="pq" id="pq" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >HOD Comments</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="hod" id="hod" readonly></input></td>
							</tr>
<tr>
                                <td><label for="inputName" >Date of submission of resignation</label></td>
                                <td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="ads" id="ads" readonly></input></td>
                            </tr>
							<tr>
								<td><label for="inputName" >Actual Date of Leaving</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="adol" id="adol" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Modified Date of Leaving</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="mdol" id="mdol" readonly></input></td>
							</tr>
							<tr>
								<td><label for="inputName" >Leave Count(SL,CL,PL)</label></td>
								<td><input type="text" style="border-width:0px;border:none;" name="tlc" id="tlc" readonly></input>
								<!--<a href='#' id='dataleave'  style='float: right;padding-right: 15%;'   data-toggle='modal' class='dataleave' data-target='leavdata' ><i class="fa fa-database" aria-hidden="true" title='Show History of Leaves'></i></a>-->
								<a href='#' id='dataleave'    data-toggle='modal' class='dataleave' data-target='leavdata'  ><i class="fa fa-database" aria-hidden="true" title='Show History of Leaves'></i></a>					
								</td>
							</tr>
							<tr>
								<td><label for="inputName" >LOP Count</label></td>
								<td><input type="text" style="border-width:0px;border:none;" name="tloc" id="tloc" readonly></input>
								
							</tr>
						</table>
					</div>
					</div>

					<div class="modal fade" id="leavdata">
						<div class="modal-dialog">
							<div class="modal-content" style="width: 130%;">
								<div class="modal-header">
									<button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
									<h4>History of Leaves</h4>
                               </div>
								<div class="modal-body">	
									<div id="display">
									</div>
								</div>
							</div>
            <!-- /.modal-content -->
						</div>
          <!-- /.modal-dialog -->
					</div>
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
    $('#resgnchange').DataTable();
    $('#histtable').DataTable();
  })
</script>
<script>

	$(function() {
   var bid, trid;
  $('#resgnchange tbody').on('click', 'tr', function () {
       Id = $(this).find('.EmpId').text();
  		Department = $(this).find('.Department').val();
       employee_comments = $(this).find('.employee_comments').val();
       adl = $(this).find('.adl').val();
       dl = $(this).find('.dl').val();
       Manager_Name = $(this).find('.Manager_Name').val();
       Hod_Name = $(this).find('.Hod_Name').val();
       reason_for_resignation = $(this).find('.reason_for_resignation').val();
       queue = $(this).find('.queue').val();
       leave_count = $(this).find('.leave_count').val();
	    ReId = $(this).find('.resgnid').text();
		reson = $(this).find('.reason_for_resignation').val();
		employee_comments = $(this).find('.employee_comments').val();
  lop_count = $(this).find('.lop_count').val();
$('#tloc').val(lop_count);
  Ads = $(this).find('.ads').val();
		$('#EmpIdvalue').val(Id);
		$('#ResIdvalue').val(ReId);
  		$('#empcomments').val(employee_comments);
		$('#reason').val(reason_for_resignation);
		$('#dept').val(Department);
		$('#hdet').val(Hod_Name);
		$('#mdet').val(Manager_Name);
		$('#adol').val(adl);
		$('#mdol').val(dl);
		$('#pq').val(queue);
		$('#tlc').val(leave_count);
  $('#ads').val(Ads);
		$('#empcomments').prop('title',employee_comments );
  		$('#pq').prop('title',queue );
  $('#dataleave').attr('data-src',Id);
  
  if(lop_count=='0.0' && leave_count=='0.0')
        {
        	document.getElementById('dataleave').style.display='none';
        }
  else
  {
  	document.getElementById('dataleave').style.display='block';
  }
  });
});
	</script>
<script>

	$(function() {
   var bid, trid;
  $('#histtable tbody').on('click', 'tr', function () {
       Id = $(this).find('.EmpId').text();
  		Department = $(this).find('.Department').val();
       employee_comments = $(this).find('.employee_comments').val();
       adl = $(this).find('.adl').val();
       dl = $(this).find('.dl').val();
       Manager_Name = $(this).find('.Manager_Name').val();
       Hod_Name = $(this).find('.Hod_Name').val();
       reason_for_resignation = $(this).find('.reason_for_resignation').val();
       queue = $(this).find('.queue').val();
       leave_count = $(this).find('.leave_count').val();
	    ReId = $(this).find('.resgnid').text();
		reson = $(this).find('.reason_for_resignation').val();
		employee_comments = $(this).find('.employee_comments').val();
  hod_comments = $(this).find('.hod_comments').val();
  lop_count = $(this).find('.lop_count').val();
$('#tloc').val(lop_count);
  Ads = $(this).find('.ads').val();
		$('#EmpIdvalue').val(Id);
		$('#ResIdvalue').val(ReId);
  $('#empcomments').val(employee_comments);
		$('#reason').val(reason_for_resignation);
		$('#dept').val(Department);
		$('#hdet').val(Hod_Name);
		$('#mdet').val(Manager_Name);
		$('#adol').val(adl);
		$('#mdol').val(dl);
		$('#pq').val(queue);
		$('#tlc').val(leave_count);
  $('#ads').val(Ads);
		$('#empcomments').val(employee_comments);
  $('#reason').prop('title',reason_for_resignation );
  $('#empcomments').prop('title',employee_comments );
  $('#hod').val(hod_comments);
  $('#pq').prop('title',queue );
  $('#hod').prop('title',hod_comments );
   $('#dataleave').attr('data-src',Id);
  
  if(lop_count=='0.0' && leave_count=='0.0')
        {
        	document.getElementById('dataleave').style.display='none';
        }
  else
  {
  	document.getElementById('dataleave').style.display='block';
  }
  });
});
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
<script type="text/javascript">
/* 	$("#btnDeny").click(function() {
	if(document.getElementById('hod_comments').value=='')
	{
	 alert('Kindly fill the comments');
	 return false;
	}
	else
	{
		function updatestatus(that) {
		//debugger;
		ajaxindicatorstart("Processing..Please Wait..");
		var process= that;
		var EmpIdvalue = $('#EmpIdvalue').val();
		var ResIdvalue = $('#ResIdvalue').val();	   
		var hod_comments = $('#hod_comments').val();	   
           $.ajax({
               type: "POST",
               url: "updatestatus.php",
               data: {
                   EmpIdvalue: EmpIdvalue,
				   process:process,
				   ResIdvalue:ResIdvalue,	
				   hod_comments:hod_comments,
               },
           });
		}
	}
}); */
function updatestatus(that) {
	if(document.getElementById('hod_comments').value=='')
	{
	 alert('Kindly fill the comments');
	 return false;
	}
	else{
	ajaxindicatorstart("Processing..Please Wait..");
		var process= that;
		var EmpIdvalue = $('#EmpIdvalue').val();
		var ResIdvalue = $('#ResIdvalue').val();	   
		var hod_comments = $('#hod_comments').val();	   
           $.ajax({
               type: "POST",
               url: "updatestatus.php",
               data: {
                   EmpIdvalue: EmpIdvalue,
				   process:process,
				   ResIdvalue:ResIdvalue,	
				   hod_comments:hod_comments,
               },
			   success: function(data){
				   location.reload();
				}
           });
}
}
	  //ajaxindicatorstart("Processing..Please Wait..");
</script>
<script>
$('#dataleave').click(function(){
ajaxindicatorstart("Please Wait..");
 var modalleave=document.getElementById('dataleave').dataset.target;
 
// var modal1 = document.getElementById(modalval);
// modal1.style.display = "block";
var empidval=document.getElementById('dataleave').attributes[5].value;
	  $.ajax({
               type: "POST",
               url: "leavedates.php",
               data: {
                   empidval: empidval,
               },
               success: function(html) {
                   $("#display").html(html).show();
               	ajaxindicatorstop();
               }
      });
var modalidleave= '#'+modalleave;
 $(modalidleave).modal({ 
            show: true, 
        });
$(modalidleave).modal('show');

});
</script>
<script>
$('.close').click(function(e) {
//alert('Hi');
//
$('#leavdata').modal('hide');
$("#display").html("");
});
</script>

</body>
</html>
