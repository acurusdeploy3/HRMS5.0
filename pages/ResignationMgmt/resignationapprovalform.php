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
$certQuery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(ed.First_name,' ',ed.MI,' ',ed.Last_Name) as Name,ed.Department,no_due_manager_status,no_due_acc_status,no_due_sysadmin_status,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,res_id_value,
reason_for_resignation, status, 
if((date_format(date(date_of_leaving),'%d %b %Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%d %b %Y'))) as dl,
if((date_format(date(date_of_leaving),'%d-%b-%Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%Y-%m-%d'))) as dll,
date_format(date(actual_date_of_leaving),'%d %b %Y') as adl,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process','Exit Process Initiated',''))))))) as queue,
employee_comments,reporting_manager_comments,hr_comments,allocated_to,process_queue,ei.reporting_manager_id,
(select concat(First_name,' ',MI,' ',Last_Name) as Manager_Name from employee_details where employee_id=ei.reporting_manager_id) as Manager_Name,
(select concat(First_name,' ',MI,' ',Last_Name) as Hod_Name from employee_details where employee_id=h.employee_id) as Hod_Name,
SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1) as admall,
SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1) as accall,


(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,(SELECT ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id and date(date_availed) >= date(date_of_submission_of_resignation)  and leave_type in ('CL','PL','SL')
and cancled='N' and date(date_availed)<= date_of_leaving),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id
and date(date_availed) >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=ei.resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)
  and leave_type in ('CL','PL','SL') and date(date_availed)<= date_of_leaving
and cancled='N'))
) as leave_count,
(SELECT if((select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id= resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1) is null,
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >= date(date_of_submission_of_resignation) and date<= date_of_leaving
and leave_type='LOP' and permission_type in ('','Half Day')),
(select ifnull(sum(if(permission_type='Half Day',0.5,1)),0) from attendance_tracker where employee_id=ei.employee_id and date >=(select date(modified_date_and_time) from audit where module_name='Resignation Management'
and employee_id =ei.employee_id and module_id=ei.resignation_id and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)
  from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) order by id desc limit 1)

and leave_type='LOP' and permission_type in ('','Half Day') and date<= date_of_leaving
  ))
) as lop_count
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id 
inner join all_hods h on ed.department=h.department
where ei.reporting_manager_id = '".$userid."' and h.location=ed.business_unit and ei.is_active='Y' order by date(date_of_leaving) asc");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$histquery=mysqli_query($db,"SELECT  resignation_id,ei.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,
date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,
date_format(date(date_of_cancellation_of_resignation),'%d %b %Y') as dc,
date_format(date(actual_date_of_leaving),'%d %b %Y') as adl,
reason_for_resignation, if(status='Process_Completed','Process Completed',if(status='Cancel Resignation','Resignation Cancelled',status)) as status, 
if((date_format(date(date_of_leaving),'%d %b %Y'))='01 Jan 0001','',(date_format(date(date_of_leaving),'%d %b %Y'))) as dl,
employee_comments,reporting_manager_comments,hr_comments,allocated_to,process_queue,
(SELECT ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM leave_status where employee_id=ei.employee_id and date(date_availed) >= date(date_of_submission_of_resignation) and date(date_availed)<= date_of_leaving  and leave_type in ('CL','PL','SL')
and cancled='N') as leave_count,
(SELECT ifnull(sum(if(permission_type='Half Day',0.5,1)),0) FROM attendance_tracker where employee_id=ei.employee_id and date >= date(date_of_submission_of_resignation) and date<= date_of_leaving  and leave_type in ('LOP')) as lop_count,
ed.Department,
(select concat(First_name,' ',MI,' ',Last_Name) as Hod_Name from employee_details where employee_id=h.employee_id) as Hod_Name,
if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled' || process_Queue='HOD_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process' && status='Process Resignation','Exit Process Initiated',
if(status='Process_Completed','Exit Process Completed','')))))))) as queue,
(select concat(First_name,' ',MI,' ',Last_Name) as Manager_Name from employee_details where employee_id=ei.reporting_manager_id) as Manager_Name
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id 
inner join all_hods h on ed.department=h.department
where ei.reporting_manager_id = '".$userid."' and h.location=ed.business_unit and ei.is_active='N' order by date(date_of_leaving) asc");
$listquery=mysqli_query($db,"SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as accname FROM `employee_details` where job_role like 'Accountant' and is_active='Y'");
$listquery1=mysqli_query($db,"SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` where job_role = 'System Admin' and is_active='Y'");
$query1 = mysqli_query ($db,"select employee_id from employee_details where job_role='HR Manager'");
$detrow=mysqli_fetch_assoc($query1);
$idval=$detrow['employee_id'];
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
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
 
  <link rel="stylesheet" href="dist/css/w3.css">	
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
    font-size: 25px ! important;
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
.close13 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close13:hover,
.close13:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
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
    .icon-stack {
      position: relative;
      display: inline-block;
      width: 2em;
      height: 5em;
      line-height: 4em;
      vertical-align: middle;
    }
    .icon-stack-1x,
    .icon-stack-2x,
    .icon-stack-3x {
      position: absolute;
      /*left: 0;
      width: 100%;*/
      text-align: right;
    }
    .icon-stack-1x {
      line-height: inherit;
    }
    .icon-stack-2x {
      font-size: 1.5em;
    }
    .icon-stack-3x {
      font-size: 2em;
    }
#myOverlay{position:absolute;height:100%;width:100%;}
#myOverlay{background:black;opacity:.7;z-index:2;display:none;}
#dataleave 
{float: right;
padding-right: 15%;}
#loadingGIF{position:absolute;top:50%;left:50%;z-index:3;display:none;}
</style>
  <!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php require_once('Layouts/main-header.php'); ?>
<!-- Left side column. contains the logo and sidebar -->
<?php require_once('Layouts/main-sidebar.php');?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>  ACURUS EMPLOYEE FORM
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
							echo $temp;?>
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
										<th style="display:none;">Resignation ID</th>
										<th>Name</th>
										<th>Information</th> 
										<th>Status</th>
										<th>View/Edit</th>
										<th>Allocation</th>					
										<th>No Due Formalities</th>
									</thead>
									<tbody>
								<?php
								if(mysqli_num_rows($certQuery) < 1){
									//echo "<tr><td cols-span='4'> No Results Found </td></tr>";
								}else{
									$i = 1;
									while($row = mysqli_fetch_assoc($certQuery)){
									echo "<tr><td style='width:1%'>".$i.".</td>";
									echo "<td class='resgnid' style='width:5%;display:none;'>".$row['resignation_id']."</td>";
									echo "<td class='EmpId' style='width:5%'>".$row['employee_id']."</td>";
									echo "<td style='width:20%'>".$row['Name']."</td>";
									echo "<td style='width:9%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i>";
									echo "<input type='hidden' class='reason_for_resignation' value='".$row['reason_for_resignation']."'></input>";
									echo "<input type='hidden' class='Department' value=".$row['Department']."></input>";
                                    echo "<input type='hidden' class='Manager_Name' value='".$row['Manager_Name']."'></input>";
									echo "<input type='hidden' class='Hod_Name' value='".$row['Hod_Name']."'></input>";
                                    echo "<input type='hidden' class='queue' value='".$row['reporting_manager_comments']."'></input>";
									echo "<input type='hidden' class='adl' value='".$row['adl']."'></input>";
									echo "<input type='hidden' class='dl' value='".$row['dl']."'></input>";
                                  	echo "<input type='hidden' class='queue' value='".$row['reporting_manager_comments']."'></input>";
									echo "<input type='hidden' class='leave_count' value='".$row['leave_count']."'></input></td>";
                                    echo "<input type='hidden' class='lop_count' value='".$row['lop_count']."'></input></td>";
                                    echo "<input type='hidden' class='ads' value='".$row['ds']."'></input></td>";
									$empcomments=mysqli_real_escape_string($db,$row["employee_comments"]);
									echo "<input type='hidden' class='employee_comments' value='$empcomments'></input></td>";
									echo "<td style='width:17%'>".$row['queue']."</td>";
									
									if($row['process_queue'] == 'Employee_Process' || $row['process_queue'] == 'Manager_Hold'){
									echo "<td style='width:17%'>
									<a href='#' id='myBtn'  data-toggle='modal' data-target='#myModal'><i class='fa fa-pencil-square-o' id='faicon'></i>Manager Process</a></td>";
									}else if($row['process_queue'] == 'HR_Manager_Process' && $row['no_due_manager_status']=='Y') 
									{echo "<td><a href='managernodueprocessingform.php?res_id=".$row['res_id_value']."'><i class='fa fa-newspaper-o icon-stack-3x' id='faicon' title='Knowledge Transfer'></i><i class='fa fa-pencil icon-stack-1x' style='font-size: 18px ! important;padding-left: 2%;' id='faicon' title='Knowledge Transfer'></i></a></td>";}
									else if($row['employee_id']==$idval && $row['process_queue']=='Manager_Process')
									{
										echo "<td><a href='hrprocessingform.php?res_id=".$row['res_id_value']."'><i class='fa fa-pencil-square-o' id='faicon'></i>HR Manager Process</a></td>";
									}
									else{
										echo "<td style='width:17%'></td>";}
									if($row['accall'] == $userid && $usergrp != 'Accounts Manager'  && $row['no_due_acc_status'] =='Y'){
										echo "<td style='width:10%'><a href='#' id='myBtn1'  data-toggle='modal' data-target='#myModal1'><i class='fa fa-user-plus' id='faicon'></i>Accounts</a></td>";
										echo "<td style='width:10%'><a href='accountsnodueprocessingform.php?res_id=".$row['res_id_value']."'><i class='fa fa-pencil-square-o' id='faicon'></i>Accounts</a></td>";
										echo "<td class='scnId' style='width:5%;display:none;'>Accounts</td>";
										}
									else if ($row['admall'] == $userid && $usergrp != 'System Admin Manager' && $row['no_due_sysadmin_status'] == 'Y'){
										echo "<td style='width:10%'><a href='#' id='myBtn2'  data-toggle='modal' data-target='#myModal2'><i class='fa fa-user-plus' id='faicon'></i></a></td>";
										echo "<td style='width:10%'><a href='adminnodueprocessingform.php?res_id=".$row['res_id_value']."'><i class='fa fa-pencil-square-o' id='faicon'></i></a></td>";
										echo "<td class='scnId' style='width:5%;display:none;'>Admin</td>";
									}
									else
									{
										echo "<td style='width:10%'></td>";
										echo "<td style='width:10%'></td>";
									}
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
					<!--<input type="button" id="btnhistory" name="btnhistory" value="View History of Resignations" style="margin-left: 7px;"  onclick="opendiv();"></input>-->
					<br><br>
					<div class="border-class w3-container w3-show" id="historydiv" >
						<h4 style="margin-left: 7px;font-weight: bold;color: tomato;">RESIGNATION HISTORY</h4>
						<table class="table" style="font-size:14px;width:100%" id="histtable">
								<thead>
								  <th style="width: 10px">#</th>
								  <th style="display:none;">Resignation ID</th>
								  <th>Empl. ID</th>
								  <th>Name</th>
								  <th>Information</th> 
								  <th>Status</th>
								</thead>
								<tbody>
								<?php
								if(mysqli_num_rows($histquery) < 1){
								  //echo "<tr><td colspan='7'> No Results Found </td></tr>";
								}else{
								  $i = 1;
								  while($row1 = mysqli_fetch_assoc($histquery)){
									echo "<tr><td style='width:1%'>".$i.".</td>";
									echo "<td style='width:5%;display:none;'>".$row1['resignation_id']."</td>";
									echo "<td style='width:5%'>".$row1['employee_id']."</td>";
									echo "<td style='width:15%'>".$row1['Name']."</td>";
									echo "<input type='hidden' class='Department' value='".$row1['Department']."'></input>";
									echo "<input type='hidden' class='employee_comments' value='".$row1['employee_comments']."'></input>";
									echo "<input type='hidden' class='Manager_Name' value='".$row1['Manager_Name']."'></input>";
									echo "<input type='hidden' class='Hod_Name' value='".$row1['Hod_Name']."'></input>";
									echo "<input type='hidden' class='reason_for_resignation' value='".$row1['reason_for_resignation']."'></input>";
									echo "<input type='hidden' class='queue' value='".$row1['reporting_manager_comments']."'></input>";
									echo "<input type='hidden' class='adl' value='".$row1['adl']."'></input>";
                                 	 echo "<input type='hidden' class='ads' value='".$row1['ds']."'></input></td>";
									echo "<input type='hidden' class='dl' value='".$row1['dl']."'></input>";
									echo "<input type='hidden' class='leave_count' value='".$row1['leave_count']."'></input></td>";
                                  echo "<input type='hidden' class='lop_count' value='".$row1['lop_count']."'></input></td>";
                                 	echo "<td style='width:9%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i>";
									
									echo "<td style='width:20%'>".$row1['queue']."</td>";
									echo "</tr>";
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
						<form id="statuschange" method="POST" action="updatemngrstatus.php">
							<input type="hidden" value="" name="EmpIdvalue" id="EmpIdvalue"/>
							<input type="hidden" value="" name="ResIdvalue" id="ResIdvalue"/>
							<label for="inputStatusChange" class="col-sm-2 control-label">Status </label>
							<select class="form-control" name="status" id ="statusselect" required style="width:50%" >
								<option value="Process Resignation">Process Resignation</option>
								<option value="Request for Cancellation of Resignation">Request for Cancellation of Resignation</option>
							  <!--<option value="Hold Resignation">Hold Resignation</option>-->       
							</select>
							<br>
							<label for="inputcomments" class="col-sm-2 control-label">Comments </label>
							<input type="text"  class="form-control" id="reporting_manager_comments" name="reporting_manager_comments"   style="width:80%" required><br>
							<input id="addStatusChangebtn"  name="AddstatusBtn" type="submit" class = "btn btn-primary" value = "Save"/>
							<label style="padding-left: 60%;"><span class="error">**</span>All fields are mandatory</label>
						</form>
					</div>
					</div>
					<div id="myModal1" class="modal">
					<!-- Modal content -->
					<div class="modal-content">
						<span class="close12"  data-dismiss="modal" >&times;</span>
						<p></p>
						<form id="statuschangeacc" method="POST" action="updatenodueallocation.php">
							<input type="hidden" value="" name="EmpIdvalue1" id="EmpIdvalue1"/>
							<input type="hidden" value="" name="ResIdvalue1" id="ResIdvalue1"/>
							<input type="hidden" value="" name="ScnIdvalue1" id="ScnIdvalue1"/>
							<label for="inputStatusChange" class="col-sm-3 control-label">Allocate to <span class="error">*  </span></label>				
							<select class="form-control" id="no_due_acc_allocated_to" name="no_due_acc_allocated_to" required>			
								<?php
								while($row10 = mysqli_fetch_assoc($listquery)){?>
								<option value= "<?php echo $row10['accname']." ";?>" ><?php  echo $row10['accname']." "; ?></option> 
								<?php } ?>
							</select>	
							<br>
							<input id="addStatusChangebtn"  name="AddstatusBtn" type="submit" class = "btn btn-primary" value = "Save"/>
						</form>
					</div>
					</div>	
					<div id="myModal2" class="modal">
					<!-- Modal content -->
					<div class="modal-content">
						<span class="close13"  data-dismiss="modal">&times;</span>
						<p></p>
						<form id="statuschangeadm" method="POST" action="updatenodueallocation.php">
							<input type="hidden" value="" name="EmpIdvalue2" id="EmpIdvalue2"/>
							<input type="hidden" value="" name="ResIdvalue2" id="ResIdvalue2"/>
							<input type="hidden" value="" name="ScnIdvalue2" id="ScnIdvalue2"/>
							<label for="inputStatusChange" class="col-sm-3 control-label">Allocate to <span class="error">*  </span></label>				
							<select class="form-control" id="no_due_adm_allocated_to" name="no_due_adm_allocated_to" required>			
								<?php
								while($row11 = mysqli_fetch_assoc($listquery1)){ ?>
								<option value= "<?php echo $row11['admname']." ";?>" ><?php  echo $row11['admname']." "; ?></option> 
								<?php } ?>
							</select>	
							<br>
							<input id="addStatusChangebtn"  name="AddstatusBtn" type="submit" class = "btn btn-primary" value = "Save"/>
						</form>
					</div>
					</div>
					<div id="datamodel" class="modal">
					<div class="modal-content">
						<span class="close123" data-dismiss="modal">&times;</span>
						<p style="font-size: 22px;color: #eb6027;">DETAILS</p>
						<table class="table">
							<tr>
								<td><label for="inputName" >Department</label></td>
								<td><input type="text" style="border-width:0px;border:none;width: -webkit-fill-available;" name="deptcomm" id="deptcomm" readonly></input></td>
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
								<!--<a href='#' id='dataleave'  data-toggle='modal' class='dataleave' data-target='leavdata' ><i class="fa fa-database" aria-hidden="true" title='Show History of Leaves'></i></a>-->
								<a href='#' id='dataleave'    data-toggle='modal' class='dataleave' data-target='leavdata'  ><i class="fa fa-database" aria-hidden="true" title='Show History of Leaves'></i></a></td>
								</td>
							</tr>
							<tr>
								<td><label for="inputName" >LOP Count</label></td>
								<td><input type="text" style="border-width:0px;border:none;" name="tloc" id="tloc" readonly></input>
								<!--<a href='#' id='dataleave'  data-toggle='modal' class='dataleave' data-target='leavdata' ><i class="fa fa-database" aria-hidden="true" title='Show History of Leaves'></i></a>--></td>
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
			<!-- /.row -->
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
$("#addStatusChangebtn").click(function() {
	if(document.getElementById("reporting_manager_comments").value=='')
	{
		
	}
	else
	{
		ajaxindicatorstart("Processing..Please Wait..");
	}
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
	function clearfields()
	{
		document.getElementById("reason_for_resignation").value="";
	}
	</script>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

// When the user clicks the button, open the modal 


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
//var btn = document.getElementById("myBtn");
$('#myBtn').onclick = function() {
debugger;
   $('#myModal').modal('show'); 
	$('.modal-backdrop').remove();
}

</script>
<script>
$('.close1').click(function(e) {

$('#myModal').modal('hide');
});
</script>
<script>
// Get the modal
var modal1 = document.getElementById('myModal1');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span1 = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
btn1.onclick = function() {

    modal1.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span1.onclick = function() {
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

	$(function() {
  var bid, trid;
  $('#resgnchange tbody').on('click', 'tr', function () {
       Id = $(this).find('.EmpId').text();
	   ReId = $(this).find('.resgnid').text();
	   ScId = $(this).find('.scnId').text();
	   reson = $(this).find('.reason_for_resignation').val();
	   Department = $(this).find('.Department').val();
 	   queue = $(this).find('.queue').val();
   	   Manager_Name = $(this).find('.Manager_Name').val();
       Hod_Name = $(this).find('.Hod_Name').val();
	   employee_comments = $(this).find('.employee_comments').val();
  	   adl = $(this).find('.adl').val();
       dl = $(this).find('.dl').val();
  	   leave_count = $(this).find('.leave_count').val();
  lop_count = $(this).find('.lop_count').val();
  Ads = $(this).find('.ads').val();
		$('#EmpIdvalue').val(Id);
		$('#ResIdvalue').val(ReId);
		$('#EmpIdvalue1').val(Id);
		$('#ResIdvalue1').val(ReId);
		$('#ScnIdvalue1').val(ScId);
		$('#EmpIdvalue2').val(Id);
		$('#ResIdvalue2').val(ReId);
		$('#ScnIdvalue2').val(ScId);
		$('#reason').val(reson);
		$('#empcomments').val(employee_comments);
  		$('#hdet').val(Hod_Name);
  		$('#ads').val(Ads);
  		$('#adol').val(adl);
		$('#mdol').val(dl);
  		$('#mdol').val(dl);
 		$('#pq').val(queue);
 		$('#tlc').val(leave_count);
  		$('#tloc').val(lop_count);
		$('#mdet').val(Manager_Name);
  		$('#deptcomm').val(Department);
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
	   ReId = $(this).find('.resgnid').text();
	   ScId = $(this).find('.scnId').text();
	   reson = $(this).find('.reason_for_resignation').val();
	   Department = $(this).find('.Department').val();
 	   queue = $(this).find('.queue').val();
   	   Manager_Name = $(this).find('.Manager_Name').val();
       Hod_Name = $(this).find('.Hod_Name').val();
	   employee_comments = $(this).find('.employee_comments').val();
  	   adl = $(this).find('.adl').val();
       dl = $(this).find('.dl').val();
  	   leave_count = $(this).find('.leave_count').val();
  lop_count = $(this).find('.lop_count').val();
  Ads = $(this).find('.ads').val();
		$('#EmpIdvalue').val(Id);
		$('#ResIdvalue').val(ReId);
		$('#EmpIdvalue1').val(Id);
		$('#ResIdvalue1').val(ReId);
		$('#ScnIdvalue1').val(ScId);
		$('#EmpIdvalue2').val(Id);
		$('#ResIdvalue2').val(ReId);
		$('#ScnIdvalue2').val(ScId);
		$('#reason').val(reson);
		$('#empcomments').val(employee_comments);
  		$('#hdet').val(Hod_Name);
  		$('#adol').val(adl);
  		$('#ads').val(Ads);
		$('#mdol').val(dl);
 		$('#pq').val(queue);
 		$('#tlc').val(leave_count);
		$('#mdet').val(Manager_Name);
  		$('#deptcomm').val(Department);
  $('#tloc').val(lop_count);
 		$('#empcomments').prop('title',employee_comments );
  $('#pq').prop('title',queue );
  $('#reason').prop('title',reson );
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
<script type="text/javascript">
   $(document).on('click','#test',function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#statuschange").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "updatemngrstatus.php",
         success: function(data){
			alert('HI');
		 location.reload();
		   ajaxindicatorstop();

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
var modal2 = document.getElementById('myModal2');

// Get the button that opens the modal
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span2 = document.getElementsByClassName("close13")[0];

// When the user clicks the button, open the modal 
btn2.onclick = function() {
    modal2.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
    modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
}
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
