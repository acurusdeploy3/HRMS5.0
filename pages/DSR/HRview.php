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
#GenerateButton{
	background-color: #4CAF50;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    position: absolute;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#dsrapprove{
	padding-left: 2%;
    padding-right: 2%;
}
#send{
	background-color: #286090;
	border-color:#0000FF;
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
		     <h1>Employee Daily Status Report</h1> 
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
         
		      <div class="box-header">
              <h3 class="box-title"><strong>Generate DSR</strong>
			 
			  </h3>

              <div class="box-tools">
              </div>
              </div>
                     <div class="border-class">
                   <form  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" style="text-align:center;">
                   <div class="form-group">	
					<label for="frmdt" id="frmdt" style="text-align:right" class="col-sm-2 control-label">From Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-left" name="datepicker" id="datepicker"  autocomplete="off" required></input>
                    </div><br><br>
					<label for="todate" id="todate" style="text-align:right" class="col-sm-2 control-label" >To Date<span class="error">*  </span></label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" name="datepicker1" id="datepicker1" autocomplete="off" required></input>
                   </div>
					</div><br>
                   <div class="buttons btn-group col-md-10">
					<input type="Submit" id="GenerateButton" value="Generate" href="hrview.php" name="GenerateButton" class="btn btn-info"></input><br><br><br> 
				 </div>
                    </form>
					
					<?php
					 if ($_SERVER["REQUEST_METHOD"] == "POST") {
						 $fromdate = date('Y-m-d', strtotime($_POST['datepicker']));
						 $todate = date('Y-m-d', strtotime($_POST['datepicker1']));
						 
					 }
					 ?>
                      <?php if($fromdate=='' && $todate==''){?>
					  <form id="dsrapprove" method="POST">	
						  <table id="dsrlisttable" class="table" style="font-size:14px;width:100%">
						  <thead>
							<tr>
							<th colspan="1" style="text-align:center">Employee DSR</th>
							<th colspan="6" style="background-color:white;border-top: 1px solid white ! important;border-right: 1px solid white ! important"></th>
							</tr>
							<tr>
							  <!--<th colspan="7" onclick="w3.sortHTML('#dsrlisttable', '.item', 'td:nth-child(1)')" style="cursor:pointer" >Date<a href="#" ><i class="fa fa-sort pull-right" aria-hidden="true" ></i></a></th>-->
							  <th colspan="7">Date</th>
							 </tr>
							</thead>
						  <tbody>
						  <?php 
						  $dsrlist1= mysqli_query($db,"select date, date_format(s.date,'%d %b %Y') as dt from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where date=curdate() or  date >= (CURDATE() - INTERVAL 10 DAY)   group by date order by date desc");
						
						  
						  if(mysqli_num_rows($dsrlist1) < 1){
										}else{ 
											while($row = mysqli_fetch_assoc($dsrlist1)){ ?>
							<tr class="item"><td colspan="7">
							<button type="button" class="accordion" title='<?php echo $row['date']?>'><?php echo $row['dt']?>
							<?php 
							$dsrlist3 = mysqli_query($db,"select date from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where is_approved='Y' and date='".$row['date']."' group by date");
							$row15 = mysqli_fetch_assoc($dsrlist3);
							if(mysqli_num_rows($dsrlist3)==1 ){?>
							<a href='excel.php?dateval=<?php echo $row['date'];?> ' id='send' class='btn btn-info pull-right send'>Export DSR</a>
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
									  <th>View</th>
									</thead>
									<tbody>
									<?php $dsrlist = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,s.is_submitted,s.is_sent,is_approved,is_expired from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join employee_shift es on es.employee_id=d.employee_id where '".$row['date']."' between start_date and end_date and date='".$row['date']."' order by d.employee_id ");
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
								if($list < 1){		
				    if($row1['is_submitted']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in employee</td>";
					}
					elseif($row1['is_sent']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in Tl</td>";
					}
					elseif($row1['is_approved']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in Manager</td>";
					}
					 elseif($row1['is_submitted']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in employee</td>";
					}
					elseif($row1['is_sent']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in Tl</td>";
					}
					elseif($row1['is_approved']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in Manager</td>";
					}
					 else{
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}					
					
					}	
					else if($list>0){			
					if($list['is_approved']==' ' && $list['number_of_days']>='1'){
						
						if($list['leave_type']=='Sick' ){
					echo '<td style="width:15%">SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%">PL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF REQUESTED </td>';
					}
				    elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%">OD REQUESTED </td>';
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
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL</td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL</td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo "<td style='width:15%'>C-OFF</td>";
					}
					elseif($list['leave_type']=='On-Duty'){
					echo "<td style='width:15%'>OD</td>";
					}
                    elseif($list['leave_type']=='Maternity'){
					echo '<td style="width:15%">ML</td>';
					}
					}
                  elseif( $list['number_of_days']<'1'){
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}					
					}
                   					
										echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
										echo "<input type='hidden' class='sc' value='".$row1['shift_code']."'></input>";
										echo "<input type='hidden' class='emp' value='".$row1['employee_id']."'></input>";
										if($row1['status']=="WFH" || $row1['status']=="CL[SH]/WFH" || $row1['status']=="CL[FH]/WFH" || $row1['status']=="SL[SH]/WFH" || $row1['status']=="SL[FH]/WFH" || $row1['status']=="C-OFF[SH]/WFH" || $row1['status']=="C-OFF[FH]/WFH"){
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
								<br>
							</div>
						</td></tr>
							<?php }} ?>
						</table>
					  </form>
					  <?php } else{?>
						<form id="dsrapprove" method="POST">	
						  <table id="dsrlisttable" class="table" style="font-size:14px;width:100%">
						  <thead>
							<tr>
							<th colspan="1" style="text-align:center">Employee DSR</th>
							<th colspan="6" style="background-color:white;border-top: 1px solid white ! important;border-right: 1px solid white ! important"></th>
							</tr>
							<tr>
							  <!--<th colspan="7" onclick="w3.sortHTML('#dsrlisttable', '.item', 'td:nth-child(1)')" style="cursor:pointer" >Date<a href="#" ><i class="fa fa-sort pull-right" aria-hidden="true" ></i></a></th>-->
							  <th colspan="7">Date</th>
							 </tr>
							</thead>
						  <tbody>
						  <?php 
						  $dsrlist1= mysqli_query($db,"select date, date_format(s.date,'%d %b %Y') as dt from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where date between '$fromdate' and '$todate' and is_approved='Y'  group by date");
						
						  
						  if(mysqli_num_rows($dsrlist1) < 1){
										}else{ 
											while($row = mysqli_fetch_assoc($dsrlist1)){ ?>
							<tr class="item"><td colspan="7">
							<button type="button" class="accordion" title='<?php echo $row['date']?>'><?php echo $row['dt']?>
							<?php 
							$dsrlist3 = mysqli_query($db,"select date from dsr_summary s left join dsr_comments c on s.dsr_summary_id=c.summary_id where is_approved='Y' and date='".$row['date']."' group by date");
							$row15 = mysqli_fetch_assoc($dsrlist3);
							if(mysqli_num_rows($dsrlist3)==1 ){?>
							<a href='excel.php?dateval=<?php echo $row['date'];?> ' id='send' class='btn btn-info pull-right send'>Export DSR</a>
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
									  <th>View</th>
									</thead>
									<tbody>
									<?php $dsrlist = mysqli_query($db,"select s.employee_id,s.dsr_summary_id,concat(First_name,' ',MI,' ',Last_Name) as Name,s.department,date_format(date,'%d %b %Y') as datelst,date as dt,es.shift_code,s.status,s.is_submitted,s.is_sent,s.is_approved,is_expired from dsr_summary s inner join employee_details d on s.employee_id=d.employee_id left join employee_shift es on es.employee_id=d.employee_id where  '".$row['date']."' between start_date and end_date and date='".$row['date']."' order by d.employee_id ");
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
										
										if($list < 1){		
				    if($row1['is_submitted']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in employee</td>";
					}
					elseif($row1['is_sent']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in Tl</td>";
					}
					elseif($row1['is_approved']=='N' && $row1['is_expired']=='N'){
					 echo "<td style='width:15%'>Pending in Manager</td>";
					}
					 elseif($row1['is_submitted']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in employee</td>";
					}
					elseif($row1['is_sent']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in Tl</td>";
					}
					elseif($row1['is_approved']=='N' && $row1['is_expired']=='Y'){
					 echo "<td style='width:15%'>Expired in Manager</td>";
					}
					 else{
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}					
					
					}	
					else if($list>0){			
					if($list['is_approved']==' ' ){
						
						if($list['leave_type']=='Sick'){
					echo '<td style="width:15%">SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege'){
					echo '<td style="width:15%">PL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual'){
					echo '<td style="width:15%">CL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Casual & Sick'){
					echo '<td style="width:15%">CL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Privilege & Sick'){
					echo '<td style="width:15%">PL&SL REQUESTED </td>';
					}
					elseif($list['leave_type']=='Compensatory-Off'){
					echo '<td style="width:15%">C-OFF REQUESTED </td>';
					}
				    elseif($list['leave_type']=='On-Duty'){
					echo '<td style="width:15%">OD REQUESTED </td>';
					}
                    elseif($list['leave_type']=='Maternity'){
					echo '<td style="width:15%">ML REQUESTED </td>';
					}
					}
					
					elseif($list['is_approved']=='Y'){
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
					echo "<td style='width:15%'>C-OFF</td>";
					}
					elseif($list['leave_type']=='On-Duty'){
					echo "<td style='width:15%'>OD</td>";
					}
					}	
                    elseif( $list['number_of_days']<'1'){
						echo "<td style='width:15%'>".$row1['status']."</td>";
					}	
                    elseif($list['leave_type']=='Maternity'){
					echo '<td style="width:15%">ML </td>';
					}
					}
                    
										echo "<input type='hidden' class='dl' value='".$row1['datelst']."'></input>";
										echo "<input type='hidden' class='sc' value='".$row1['shift_code']."'></input>";
										echo "<input type='hidden' class='emp' value='".$row1['employee_id']."'></input>";
										if($row1['status']=="WFH" || $row1['status']=="CL[SH]/WFH" || $row1['status']=="CL[FH]/WFH" || $row1['status']=="SL[SH]/WFH" || $row1['status']=="SL[FH]/WFH" || $row1['status']=="C-OFF[SH]/WFH" || $row1['status']=="C-OFF[FH]/WFH"){
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
								<br>
							</div>
						</td></tr>
							<?php }} ?>
						</table>
			</form>	
					  <?php }?>
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
<!-- Page script -->

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
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 


// When the user clicks on <span> (x), close the modal


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

  <script type="text/javascript" language="javascript">
  $('#closeforrelaod').on('click', function(e) {
	  ajaxindicatorstart('Please Wait....');
	  location.reload();
	  //ajaxindicatorstop();
  }); }
	</script>

	<script>
$('.BtnVwdsr').click(function(){
ajaxindicatorstart("Please Wait..");
 var modalleave=document.getElementById('BtnVwdsr').dataset.target;
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
	//ajaxindicatorstart("Please Wait..");
	e.preventDefault();
 	$.ajax({
        url: "mytasks.php",
		type: "POST",
		data: {
			strength:strength,
			employeeid:employeeid,
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
<script>
	/* $(".send").click(function(){
		var dateval=$(this).closest('.accordion')[0].title;
		$.ajax({
			url: "excel.php",
			type: "GET",
			data: {
				dateval:dateval,
			},
			
});
	}); */
	</script>

<script>
</body>
</html>
				
				
				
				
				
				
				
			