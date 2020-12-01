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
//$date1 = date("Y-m-d");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];


$curShift = mysqli_query($db,"select * from employee_shift where date(curdate()) between start_date and end_date and employee_id='$userid'");
$curShiftrow = mysqli_fetch_assoc($curShift);


$checkiftoday = mysqli_query($db,"select * from dsr_summary where employee_id= '$userid' and  is_active='Y'");
while($row = mysqli_fetch_assoc($checkiftoday)){
$date1= $row['date'];
}
$dsrlist = mysqli_query($db,"select s.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,s.date as dt,es.shift_code,s.is_submitted,s.status from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join employee_shift es on s.employee_id=es.employee_id where s.employee_id='$userid' and s.is_active='Y' and s.is_submitted<>'Y' group by date order by dt desc");

$dsrlisthistory = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,s.status,s.is_sent,s.is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join dsr_comments c on s.dsr_summary_id=c.summary_id where s.employee_id='$userid' and  is_submitted='Y' group by dsr_summary_id order by dt desc ");


$dsrlist1 = mysqli_query($db,"select date_format(s.date,'%d %b %Y') as dt,s.date as date1 from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where (s.tl_id='$userid' and s.is_active='Y' and is_sent='N'  and s.is_completed='N') or (s.employee_id='$userid' and s.is_active='Y' and is_sent='N' and s.is_completed='N') 
union
select date_format(s.date,'%d %b %Y') as dt,s.date as date1 from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where (s.manager_id='$userid' and s.is_active='Y' and is_approved='N' and s.is_completed='N')or
(s.employee_id='$userid' and s.is_active='Y' and is_sent='N' and s.is_completed='N') group by dt order by date1 desc");

					
$dsrhistorylist1 = mysqli_query($db,"select date_format(s.date,'%d %b %Y') as dt,s.date as date1 from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where (s.tl_id='$userid' and s.is_sent='Y' and s.tl_id<>s.manager_id)or(s.employee_id='$userid' and s.is_submitted='Y' and s.tl_id<>s.manager_id) 
union
select date_format(s.date,'%d %b %Y') as dt,s.date as date1 from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where (s.manager_id='$userid' and s.is_sent='Y' and s.is_approved='Y')or(s.employee_id='$userid' and s.is_submitted='Y')group by dt order by date1 desc");


$dsrcount = mysqli_query($db,"select s.employee_id,concat(First_name,' ',MI,' ',Last_Name) as Name,department,date_format(date,'%d %b %Y') as datelst,date as dt,shift_code from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.manager_id='$userid' and s.is_active='Y' and s.is_sent='Y' and s.is_approved='Y'  and s.is_completed='N' group by date order by date");

$GetTeamCount = mysqli_query($db,"select * from employee_Details where reporting_manager_id='$userid'");
$TeamCount = mysqli_num_rows($GetTeamCount);
if($TeamCount>0)
{
	$HasTeam='Y';
}else{}

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
#dsrcreateform,#dsrhistform,#dsrapprove,#dsrhistform1{
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
  padding: 0 18px;
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
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>   
				   <?php if($HasTeam=='Y'){?>
				   <input action="action" class="btn btn-info pull-right" onclick="location.href='PendingDSR.php'" type="button" value="Pending DSR" id="goprevious"/>
				   <?php }?>				   
				   </div>
              <!-- /.box-footer -->			  		  
            <?php if($HasTeam=='Y'){?>
			<div class="border-class">
						<form id="dsrapprove" method="POST">	
						  <table id="dsrlisttable" class="table" style="font-size:14px;width:100%">
						  <thead>
							<tr>
							<th colspan="1" style="text-align:center">Active DSR</th>
							<th colspan="6" style="background-color:white;border-top: 1px solid white ! important;border-right: 1px solid white ! important"></th>
							</tr>
							<tr>
							  <!--<th colspan="7" onclick="w3.sortHTML('#dsrlisttable', '.item', 'td:nth-child(1)')" style="cursor:pointer" >Date<a href="#" ><i class="fa fa-sort pull-right" aria-hidden="true" ></i></a></th>-->
							  <th colspan="7">Date</th>
							 </tr>
							</thead>
						  <tbody>
						  <?php if(mysqli_num_rows($dsrlist1) < 1){
										}else{
											while($row = mysqli_fetch_assoc($dsrlist1)){ ?>
							<tr class="item"><td colspan="7">
							<button type="button" class="accordion" title='<?php echo $row['date1']?>'><?php echo $row['dt']?>
							<?php 
							$dsrlist3 = mysqli_query($db,"select date_format(s.date,'%d %b %Y') as dt,s.date as date1,s.is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.tl_id='$userid' and s.is_active='Y' and s.is_submitted='Y' and s.is_completed='N' and date='".$row['dt']."'
							UNION
							select date_format(s.date,'%d %b %Y') as dt,s.date as date1,s.is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id where s.manager_id='$userid' and s.is_active='Y' and s.is_submitted='Y' and s.is_completed='N' and date='".$row['dt']."' group by is_approved order by dt asc");
							$row15 = mysqli_fetch_assoc($dsrlist3);
							if(mysqli_num_rows($dsrlist3)==1 && $row15['is_approved']=='Y' ){?>
							<?php }?>
							</button>&nbsp;
							<div class="panel">
								<table id="dsrtable" class="table dsrtable" style="font-size:14px;width:100%">
									<thead>
									  <th>#</th>
									  <th>Employee ID</th>
									  <th>Employee Name</th>
									  <th>Department</th>	
									  <th>Date</th>
									  <th>Shift Code</th>
									  <th>Status</th>
									  <th>Edit</th>
									  <th>Select&emsp;
									  <?php if(mysqli_num_rows($dsrlist3)==1 && $row15['is_approved']=='Y' ) {} else {?>
									  <input type='checkbox' onchange="callFunction()" class="selectcheckBoxAll" name='selectalll' value=''></th>
									  <?php }?>
									</thead>
									<tbody>
									<?php $dsrlist = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved,is_submitted,c.employee_comments from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join dsr_comments c on s.dsr_summary_id=c.summary_id left join employee_shift es on es.employee_id=d.employee_id where (s.tl_id='$userid' and s.is_active='Y'and is_submitted='Y' and is_sent='N' and '".$row['date1']."' between start_date and end_date and date='".$row['date1']."')or (s.employee_id='$userid' and s.is_active='Y' and is_sent='N' and '".$row['date1']."' between start_date and end_date and date='".$row['date1']."')group by s.employee_id
                                    union
                                    select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved ,is_submitted,c.employee_comments from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join dsr_comments c on s.dsr_summary_id=c.summary_id left join employee_shift es on es.employee_id=d.employee_id where date='".$row['date1']."' and is_approved='N' and '".$row['date1']."' between start_date and end_date and s.employee_id in(select employee_id from leave_request where ( employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and '".$row['date1']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH' and number_of_days>='1' and s.is_sent='N')or(employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and '".$row['date1']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH' and number_of_days>='1' and s.is_sent='N'))group by s.employee_id
                                    union
                                    select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved,is_submitted,c.employee_comments from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join dsr_comments c on s.dsr_summary_id=c.summary_id left join employee_shift es on es.employee_id=d.employee_id where (s.manager_id='$userid' and s.is_active='Y'and is_submitted='Y' and is_sent='Y' and is_approved='N' and '".$row['date1']."' between start_date and end_date and date='".$row['date1']."')or(s.employee_id='$userid' and s.is_active='Y' and  is_sent='N' and '".$row['date1']."' between start_date and end_date and date='".$row['date1']."')group by s.employee_id
                                    union
                                    select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved,is_submitted,c.employee_comments from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join dsr_comments c on s.dsr_summary_id=c.summary_id left join employee_shift es on es.employee_id=d.employee_id where date='".$row['date1']."' and is_approved='N' and '".$row['date1']."' between start_date and end_date and s.employee_id in(select employee_id from leave_request where (employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and '".$row['date1']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH' and number_of_days>='1' and s.is_sent='N')or(employee_id in(select employee_id from employee_details where reporting_manager_id='$userid') and '".$row['date1']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH' and number_of_days>='1' and s.is_sent='N'))group by s.employee_id ");
									
									if(mysqli_num_rows($dsrlist) < 1){
										
									}else{
									  $i = 1;
									  while($row1 = mysqli_fetch_assoc($dsrlist)){
										  
										  
										   $leavelist = mysqli_query($db,"select * from leave_request where (employee_id='".$row1['employee_id']."' and '".$row1['dt']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH') or(employee_id='".$row1['employee_id']."' and '".$row1['dt']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH')");
										  $list = mysqli_fetch_assoc($leavelist);	
                                       
										echo "<tr id=".$row1['employee_id'].'|'.$row1['dt'].'|'.$row1['dsr_summary_id']." class=".$row1['employee_id'].'|'.$row1['dt'].'|'.$row1['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
										echo "<td class='EmpId' style='width:5%'>".$row1['employee_id']."</td>";
										echo "<td style='width:15%'>".$row1['Name']."</td>"; 
										echo "<td style='width:15%'>".$row1['department']."</td>";
										echo "<td style='width:15%'>".$row1['datelst']."</td>";
										echo "<td style='width:15%'>".$row1['shift_code']."</td>";
										
										if($list<1){ 
				    echo "<td style='width:15%'>".$row1['status']."</td>";
					}
				    elseif($list['is_approved']=='Y' && $list['number_of_days']>='1'){
					if($list['leave_type']=='Sick'){
					echo "<td style='width:15%'>SL</td>";
					}
					elseif($list['leave_type']=='Privilege'){
					echo "<td style='width:15%'>PL</td>";
					}
					elseif($list['leave_type']=='Casual'){
					echo "<td style='width:15%'>CL</td>";
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL</td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL</td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF</td>';
					}
					elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%">OD</td>';
					}
                    elseif($list['leave_type']=='Maternity'){		
					echo '<td style="width:15%">ML</td>';		
					}
					}
					elseif($row1['employee_id']==$userid && $list['is_approved']==' ' && $list['number_of_days']>='1'){
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%" >SL REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%" >PL REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%" >CL REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%" >CL&SL REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%" >PL&SL REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%" >C-OFF REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%" >OD REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
                    elseif($list['leave_type']=='Maternity'){		
					echo '<td style="width:15%" >ML REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';		
					}
					}
					
					elseif($list['is_approved']==' ' && $list['number_of_days']>='1'){
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL REQUESTED<a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%">PL REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></i></td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">PL&SL REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">CL&SL REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></i></td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></i></td>';
					}
					elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%">OD REQUESTED<a title="info" href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></i></td>';
					}
                     elseif($list['leave_type']=='Maternity'){		
					echo '<td style="width:15%" >ML REQUESTED<a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';		
					}
					}
					elseif($row1['employee_id']==$userid && $list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					}
					elseif($row1['employee_id']==$userid && $list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					}
				  elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					}
					elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH] REQUESTED <a href="../LeaveMgmt/TeamLeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row1['status'].'</td>';
					}
					}
					elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH]/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH]/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH]/'.$row1['status'].'</td>';
					}
					}
					elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH]/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH]/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH]/'.$row1['status'].'</td>';
					}
					}
					
					else{
						echo "<td></td>";
					}
										echo "<input type='hidden' class='dt' value='".$row1['dt']."'></input>";
										echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
										echo "<input type='hidden' class='sc' value='".$row1['shift_code']."'></input>";
										echo "<input type='hidden' class='emp' value='".$row1['employee_id']."'></input>";
										if($row1['is_approved']=='N'){
											if($list > 0 && $list['number_of_days']>='1'){
												echo "<td></td>";
											}
											elseif($row1['status']=="WFH" || $row1['status']=="CL[SH]/WFH" || $row1['status']=="CL[FH]/WFH" || $row1['status']=="SL[SH]/WFH" || $row1['status']=="SL[FH]/WFH" || $row1['status']=="C-OFF[SH]/WFH" || $row1['status']=="C-OFF[FH]/WFH" || $row1['status']=="WFO" || $row1['status']=="NA"){
										echo "<td style='width:15%'>
										<a href='#' id='BtnEditdsr' name='BtnEditdsr' class='BtnEditdsr' data-toggle='modal' data-target='#datamodel'>
										<i class='fa fa-edit' aria-hidden='true' id='edit' ></i></a>
											</td> ";}
											else{
												echo "<td></td>";
											}
											if($row1['employee_id']<>$userid && $list['is_approved']==' '){
												echo "<td></td>";
											}
											elseif($row1['employee_id']==$userid && $list['is_approved']==' ' && is_null($row1['employee_comments'])){
												echo "<td><input type='checkbox' class='selectcheckBox' name='selectvalues[]' value='".$row1['dsr_summary_id'].'|'.$row1['employee_id'].'|'.$row1['dt']."'></td>";
											}
											elseif($row1['employee_id']==$userid && is_null($row1['employee_comments'])){
												echo "<td></td>";
											}
											
											else{
												echo "<td><input type='checkbox' class='selectcheckBox' onchange='callFunction()' name='selectvalues[]' value='".$row1['dsr_summary_id'].'|'.$row1['employee_id'].'|'.$row1['dt']."'></td>";
											}
											
										}
										
										echo "</tr>";
										$i++;
									  }
									}
									?>
									</tbody>
								</table>
								<br>
								<?php if(mysqli_num_rows($dsrlist3)==1 && $row15['is_approved']=='Y' ) { } else {?>
								<input type='submit' class='btn btn-info pull-right approveselected' class='approveselected' id='approveselected' value='Send DSR' disabled></input>
								<?php } ?>
							</div>
						</td></tr>
							<?php }} ?>
						</table>
			</form>	
          </div>
			<?php } 
		
			 else {?>
			 
			 
			 
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
                  <th style="width:50px">#</th>
				  <th>Employee ID</th>
				  <th>Employee Name</th>
				  <th>Department</th>	
				  <th>Date</th>	
				  <th>Status</th>
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
					  $leavelist = mysqli_query($db,"select * from leave_request where (employee_id='".$row['employee_id']."' and '".$row['dt']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH') or(employee_id='".$row['employee_id']."' and '".$row['dt']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH')");
					  $list = mysqli_fetch_array($leavelist);	
                                       
                    echo "<tr id=".$row['employee_id'].'|'.$row['dt'].'|'.$row['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row['employee_id']."</td>";
					echo "<td style='width:15%'>".$row['Name']."</td>";
					echo "<td style='width:15%'>".$row['department']."</td>";
					echo "<td style='width:15%'>".$row['datelst']."</td>";
					
					if($list<1){
						echo "<td style='width:15%'>".$row['status']."</td>";
					}

					elseif($list['is_approved']==' ' && $list['number_of_days']>='1'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%">PL REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
					elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%">OD REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';
					}
                     elseif($list['leave_type']=='Maternity'){		
					echo '<td style="width:15%" >ML REQUESTED<a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a></td>';		
					}
					}
					
					elseif($list['is_approved']=='Y' && $list['number_of_days']>='1'){
					if($list['leave_type']=='Sick'){
					echo "<td style='width:15%'>SL</td>";
					}
					elseif($list['leave_type']=='Privilege'){
					echo "<td style='width:15%'>PL</td>";
					}
					elseif($list['leave_type']=='Casual'){
					echo "<td style='width:15%'>CL</td>";
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo "<td style='width:15%'>PL&SL</td>";
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo "<td style='width:15%'>CL&SL</td>";
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo "<td style='width:15%'>C-OFF</td>";
					}
					elseif($list['leave_type']=='On-Duty'){
					echo "<td style='width:15%'>OD</td>";
					}
                     elseif($list['leave_type']=='Maternity'){		
					echo "<td style='width:15%'>ML</td>";		
					}
					}
					elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					}
					elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH] REQUESTED <a href="../LeaveMgmt/LeaveRequest.php" title="info"><i class="fa fa-info-circle" aria-hidden="true" title="info"></i></a>/'.$row['status'].'</td>';
					}
					}
					elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH]/'.$row['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH]/'.$row['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH]/'.$row['status'].'</td>';
					}
					}
					elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH]/'.$row['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH]/'.$row['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH]/'.$row['status'].'</td>';
					}
					}
					
						else{
							echo "<td></td>";
						}
				    echo "<input type='hidden' class='dt' value='".$row['dt']."'></input>";
                    echo "<input type='hidden' class='dl' value='".$row['datelst']."'></input>";
                    echo "<input type='hidden' class='sc' value='".$row['shift_code']."'></input>";
					echo "<input type='hidden' class='emp' value='".$row['employee_id']."'></input>";
					if($row['is_submitted']=='N' && $list['number_of_days']<'1'){
					echo "<td style='width:15%'>
					<a href='#' id='BtnEditdsr' name='BtnEditdsr' class='BtnEditdsr' data-toggle='modal' data-target='#datamodel'>
					<i class='fa fa-edit' aria-hidden='true' id='edit' ></i></a>
	       			</td> ";}
					else{
					echo "<td></td>";
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
			<?php }?>
		  <br><br>
		  
		   <?php if($HasTeam=='Y'){?>
		  <hr style="width:100%;" align="left">
		  <div class="border-class">
		  <!--<h3 style='color:tomato;font-weight:400;'>&emsp;History of DSR</h3>-->
		  <form id="dsrhistform1" method="POST">			
		  <table id="dsrhistlist" class="table" style="font-size:14px;width:100%">
			<thead>
				<tr>
				<th colspan="1" style="text-align:center">History of DSR</th>
				<th colspan="6" style="background-color:white;border-top: 1px solid white ! important;border-right: 1px solid white ! important"></th>
				</tr>
				<tr>
			  <th colspan="7" >Date</th>
				 </tr>
			</thead>
		  <tbody>
		  <?php if(mysqli_num_rows($dsrhistorylist1) < 1){
						}else{
							while($row2 = mysqli_fetch_assoc($dsrhistorylist1)){ ?>
			<tr class="item"><td colspan="7">
				<button type="button" class="accordion" title='<?php echo $row2['date1']?>'><?php echo $row2['dt']?></button>&nbsp;
							<div class="panel">				
							<?php 
							$dsrhistorylist = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join employee_shift es on es.employee_id=d.employee_id where (s.tl_id='$userid' and   is_sent='Y' and '".$row2['date1']."' between start_date and end_date and date='".$row2['date1']."' and s.tl_id<>s.manager_id)or (s.employee_id='$userid'and is_submitted='Y' and '".$row2['date1']."' between start_date and end_date and date='".$row2['date1']."' and s.tl_id<>s.manager_id)
                            union
                            select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,is_approved from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join employee_shift es on es.employee_id=d.employee_id where (s.employee_id='$userid' and is_submitted='Y' and '".$row2['date1']."' between start_date and end_date and date='".$row2['date1']."')or (s.manager_id='$userid' and  is_approved='Y' and  '".$row2['date1']."' between start_date and end_date and date='".$row2['date1']."') order by dt");
							?>
								<table id="dsrhisttable1" class="table dsrhisttable" style="font-size:14px;width:100%">
								<thead>
								  <th style="width: 50px">#</th>
								  <th>Employee ID</th>
								  <th>Employee Name</th>
								  <th>Department</th>	
								  <th>Date</th>	
								  <th>Status</th>	
								  <th>View</th>
								</thead>
							 <tbody>
								<?php
								if(mysqli_num_rows($dsrhistorylist) < 1){
								  //echo "<tr><td cols-span='8'> No Results Found </td></tr>";
								}else{
								  $i = 1;
								  while($row1 = mysqli_fetch_assoc($dsrhistorylist)){
									  $leavelist = mysqli_query($db,"select * from leave_request where (employee_id='".$row1['employee_id']."' and '".$row1['dt']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH') or(employee_id='".$row1['employee_id']."' and '".$row1['dt']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH')");
					                $list = mysqli_fetch_array($leavelist);	
									   
									echo "<tr id=".$row1['employee_id'].'|'.$row1['dt'].'|'.$row1['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
									echo "<td class='EmpId' style='width:5%'>".$row1['employee_id']."</td>";
									echo "<td style='width:15%'>".$row1['Name']."</td>";
									echo "<td style='width:15%'>".$row1['department']."</td>";
									echo "<td style='width:15%'>".$row1['datelst']."</td>";
              						echo "<td style='width:15%'>".$row1['status']."</td>";
									echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
									echo "<input type='hidden' class='emp' value='".$row1['employee_id']."'></input>";
									if($row1['status']=="WFH" || $row1['status']=="WFO" || $row1['status']=="CL[SH]/WFH" || $row1['status']=="CL[FH]/WFH" || $row1['status']=="SL[SH]/WFH" || $row1['status']=="SL[FH]/WFH" || $row1['status']=="C-OFF[SH]/WFH" || $row1['status']=="C-OFF[FH]/WFH"){
									echo "<td><a href='#' id='BtnVwdsr' class='BtnVwdsr' name='BtnVwdsr' data-toggle='modal' data-target='#datashowmodel' data-src=''>
									<i class='fa fa-eye' aria-hidden='true' id='view' ></i></a></td>";	
									}else{
										echo "<td></tr>";
									}
									echo "</tr>";
									$i++;
								  }
								}
								?>
							  </tbody>
							</table>
							</div>
						</td></tr>
						<?php } } ?>
						</tbody>
						</table>
				</form>
          </div>
		   <?php } 
		   
		   else { ?>
		  
		 
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
                  <th style="width: 50px">#</th>
				  <th>Employee ID</th>
				  <th>Employee Name</th>
				  <th>Department</th>	
				  <th>Date</th>	
				  <th>Status</th>
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
					  $leavelist = mysqli_query($db,"select * from leave_request where (employee_id='$userid' and '".$row1['dt']."'between leave_from and leave_to and is_active='N' and is_approved='Y' and reason<>'WFH') or(employee_id='$userid' and '".$row1['dt']."'between leave_from and leave_to and is_active='Y' and is_approved='' and reason<>'WFH')");
					 $list = mysqli_fetch_array($leavelist);	
					  $list1 = mysqli_num_rows($leavelist);
					  
					  $dirmanager = mysqli_query($db,"select tl_id,manager_id from dsr_summary where tl_id=manager_id and date='".$row1['dt']."' and employee_id='$userid' ");
                      $getsameid = mysqli_fetch_array($dirmanager);
                      $tlid= $getsameid['tl_id'];
                      $mngid = $getsameid['manager_id'];
					  
					  
                    echo "<tr id=".$row1['employee_id'].'|'.$row1['dt'].'|'.$row1['dsr_summary_id']."><td style='width:5%'>".$i.".</td>";
					echo "<td class='EmpId' style='width:5%'>".$row1['employee_id']."</td>";
					echo "<td style='width:15%'>".$row1['Name']."</td>";
					echo "<td style='width:15%'>".$row1['department']."</td>";
					echo "<td style='width:15%'>".$row1['datelst']."</td>";
					
					if($list1<1){
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}
					elseif($tlid!='' && $mngid!='' && $row1['is_approved']=='Y' ){
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}
					elseif($tlid=='' && $mngid=='' && $row1['is_sent']=='Y' ){
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}

					elseif($list['is_approved']==' ' && $list['number_of_days']>='1'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%">PL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF REQUESTED </td>';
					}
                     elseif($list['leave_type']=='Maternity'){		
					echo '<td style="width:15%">ML REQUESTED </td>';		
					}
					}
					
					elseif($list['is_approved']=='Y' && $list['number_of_days']>='1'){
					if($list['leave_type']=='Sick'){
					echo "<td style='width:15%'>SL</td>";
					}
					elseif($list['leave_type']=='Privilege'){
					echo "<td style='width:15%'>PL</td>";
					}
					elseif($list['leave_type']=='Casual'){
					echo "<td style='width:15%'>CL</td>";
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo "<td style='width:15%'>PL&SL</td>";
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo "<td style='width:15%'>CL&SL</td>";
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo "<td style='width:15%'>C-OFF</td>";
					}
                    elseif($list['leave_type']=='Maternity'){		
					echo "<td style='width:15%'>ML</td>";		
					}
					}
					elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH] REQUESTED/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH] REQUESTED/'.$row1['status'].' </td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH] REQUESTED/'.$row1['status'].' </td>';
					}
					}
					elseif($list['is_approved']==' ' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH] REQUESTED/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH] REQUESTED/'.$row1['status'].' </td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH] REQUESTED/'.$row1['status'].' </td>';
					}
					}
					 elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (First Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[FH]/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[FH]/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[FH]/'.$row1['status'].'</td>';
					}
					}
                  elseif($list['is_approved']=='Y' && $list['number_of_days']<'1' && $list['leave_for']=='Half-Day (Second Half)'){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL[SH]/'.$row1['status'].'</td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL[SH]/'.$row1['status'].'</td>';
					}
					
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF[SH]/'.$row1['status'].'</td>';
					}
					}					
					
                    echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
					if($row1['status']=="WFH" || $row1['status']=="WFO" || $row1['status']=="CL[SH]/WFH" || $row1['status']=="CL[FH]/WFH" || $row1['status']=="SL[SH]/WFH" || $row1['status']=="SL[FH]/WFH" || $row1['status']=="C-OFF[SH]/WFH" || $row1['status']=="C-OFF[FH]/WFH"){
					echo "<td><a href='#' id='BtnVwdsr' class='BtnVwdsr' name='BtnVwdsr' data-toggle='modal' data-target='#datashowmodel' data-src=''>
					<i class='fa fa-eye' aria-hidden='true' id='view' ></i></a></td>";
					}
					else{
						echo "<td></td>";
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
		   <?php } ?>
		  
		  
		  <div id="datamodel" class="modal" role="dialog" aria-hidden="true">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close"><span aria-hidden="true">×</span></button>
									<h4 class="box-title" id="headerText">Create DSR</h4>
						</div>
						<div class="modal-body" style="">
							<form action="" method="post" role="form" id="form" enctype="multipart/form-data">
							<div class="box-body" >
								<div class="col-md-12" id="formbody">
									<div class="col-md-12">
										<div class="form-group">
											<label for="date">Date<span class="text-red">*</span></label>
											<input type="text" class="form-control" id="currentdate1" name="currentdate1" value="<?php echo $row['datelst']; ?>"  required disabled	 ></input>
											<input type='hidden' id='empid' value=""></input>
											<input type='hidden' id='date1' value=""></input>
											
											<span style="color:tomato"></span>
										</div>
										<div class="form-group">
											<label for="date">Shift<span class="text-red">*</span></label>
											<input type="text" class="form-control" id="EmpShift1" name="EmpShift1" value="<?php echo $curShiftrow['Shift_Code']; ?>"  required disabled	 ></input>
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="date">Work Progress<span class="text-red">*</span></label>
											<a href="#" id="myBtn" title="Click to Add New Entry" data-toggle="modal" data-target="#modal-default-Education"><i class="fa fa-plus" style="color:forestgreen;" aria-hidden="true" ></i></a>
										</div>
										<div id="myModal" class="modal">
											<div class="modal-content">
												<span id="clear" class="close12">&times;</span>
												<p>Add new task </p>
												<input type="text"  class="form-control" id="workpg" name="workpg" placeholder="Enter Work Progress" maxlength="1000" autocomplete="off" value="" /><br>
												<input type="button" id="addnewwork"   name="addnewwork"  class = "btn btn-primary"value = "Add Task" ></input>
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
							<button type="button" class="close btn-close" data-dismiss="modal" data-backdrop="static" data-keyboard="false" aria-label="Close"><span aria-hidden="true">×</span></button>
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
$("#clear").click(function () {
    $("#workpg").val('');

});

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
	   emp = $(this).find('.emp').val();
	   dt = $(this).find('.dt').val();
	   dl = $(this).find('.dl').val();
       sc = $(this).find('.sc').val();
		$('#BtnVwdsr').attr('data-src',Id);
		$('#empid').val(emp);
		$('#date1').val(dt);
		$('#currentdate1').val(dl);
        $('#EmpShift1').val(sc);
  });
});
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
	
	$("#addnewwork").on('click', function(e) {
		var textbox = document.getElementById("workpg");
		if(textbox.value.length < 5){
			alert("Please Enter Atleast 5 Character");
			return false;
		}
		else{
	var strength = $('#workpg').val();
	var employeeid = $('#empid').val();
	var date = $('#date1').val();
	ajaxindicatorstart("Please Wait..");
	e.preventDefault();
 	$.ajax({
        url: "mytasks.php",
		type: "POST",
		data: {
			strength:strength,
			employeeid:employeeid,
			date:date,
			
		},
		success: function(html) {
			// location.reload();
			//$('#myModal').modal('toggle');
			document.getElementById('workpg').value="";
			document.getElementById('myModal').style.display='none';
			$("#display").html(html).show();
        ajaxindicatorstop();
		}
});
}
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
	
	</script>
	
	
	

			<script>
  $(function () {
    //$('#resgnchange').DataTable();
    $('#dsrlisttable').DataTable({
		"pageLength": 5,
		"order": [],
	});
	$('#dsrhistlist').DataTable({
		"pageLength": 3,
		 "order": [],

	});
  });
  
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
	ajaxindicatorstart("Sending selected DSR. This might take a while..");
	//var table = $(this)[0].closest('table');
	var data = $("#dsrapprove").serialize();
	$.ajax({
         data: data,
         type: "post",
         url: "confirmAllTasks.php",
         success: function(data)
		 {
			// debugger;         
			location.reload();
			//ajaxindicatorstop();
         }
});
	});
</script>
	
  <script type="text/javascript" language="javascript">
  $('#closeforrelaod').on('click', function(e) {
	  //ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  });
		
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
	$(".BtnEditdsr").click(function(){
		var tr=$(this).closest("tr");
		var employeeid=tr[0].id.split('|')[0];
		var date=tr[0].id.split('|')[1];
	$.ajax({
	url: "mytasks.php",
	type: "POST",
	data: {
			employeeid:employeeid,
			date:date,
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
