<?php   
session_start();  

$userid=$_SESSION['login_user'];
$res_id = $_GET['res_id'];
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
if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
$exitvalue=$detRow99['exit_interview_status'];
$no_due_acc_status=$detRow99['no_due_acc_status'];
$no_due_sysadmin_status=$detRow99['no_due_sysadmin_status'];
$no_due_admin_status=$detRow99['no_due_admin_status'];
$no_due_manager_status=$detRow99['no_due_manager_status'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$getresigneduser = mysqli_query($db,"select ed.employee_id,concat(ed.First_name,' ',ed.MI,' ',ed.Last_Name) as Name,ed.Job_Role,ed.Employee_image,ed.employee_designation,ed.department,concat(ed1.First_name,' ',ed1.MI,' ',ed1.Last_Name) as ManagerName,
ei.reporting_manager_id,date_format(date_of_leaving,'%d %b %Y') as date_of_leaving
from employee_details ed 
inner join  employee_resignation_information ei on ed.employee_id=ei.employee_id 
left join employee_details ed1 on ei.reporting_manager_id=ed1.employee_id
where resignation_id=$resignation_id");
$getresigneduserrow = mysqli_fetch_assoc($getresigneduser);
$usernamevalue = $getresigneduserrow['Name'];
$usermngrnamevalue = $getresigneduserrow['ManagerName'];
$userRolevalue = $getresigneduserrow['Job_Role'];
$userImagevalue = $getresigneduserrow['Employee_image'];
$userempvalue = $getresigneduserrow['employee_id'];
$usermngrempvalue = $getresigneduserrow['reporting_manager_id'];
$profPicPath = '../../uploads/'.$userImagevalue;
$userDesgvalue =  $getresigneduserrow['employee_designation'];
$userDeptvalue =  $getresigneduserrow['department'];
$userDtvalue =  $getresigneduserrow['date_of_leaving'];

if(isset($_POST['Submit'])) {
 $query1 = mysqli_query($db,"select * from nodueformentries where department='Human Resources' and resignation_id=$resignation_id");
 while($row11 = mysqli_fetch_assoc($query1))
 {
	$comm=$row11['details_id'].'Comments';
	$PostVal = $row11['details_id'];
	$update1="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = '".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id";
	
	$updatequery = mysqli_query($db,$update1);
	$datafill1="update nodueformentries set is_data_updated = 'Y',modified_by='$userid',modified_date_and_time='$date' where resignation_id=$resignation_id and department = 'Human Resources'";
	$datafill=mysqli_query($db,$datafill1);
 }
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_hr_status='C',modified_by=$userid where resignation_id=$resignation_id");
  
if($no_due_admin_status == 'F' ){ 
$query1 = mysqli_query($db,"select * from nodueformentries where department='Administration' and resignation_id=$resignation_id");
 while($row12 = mysqli_fetch_assoc($query1))
 {
	 $comm=$row12['details_id'].'Comments';
	 $PostVal = $row12['details_id'];
	 $update2="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = 
	'".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id";
	$updatequery = mysqli_query($db,$update2);
	$datafill2="update nodueformentries set is_data_updated = 'Y',modified_by=$userid,modified_date_and_time='$date' where resignation_id=$resignation_id and department = 'Administration'";
	$datafill21=mysqli_query($db,$datafill2);
 }
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_admin_status='C',modified_by=$userid where
 resignation_id=$resignation_id and no_due_admin_status='F'");
} 


if($no_due_sysadmin_status == 'F' ){ 
$query11 = mysqli_query($db,"select * from nodueformentries where department='System Administration' and resignation_id=$resignation_id");
 while($row13 = mysqli_fetch_assoc($query11))
 {
	 $comm=$row13['details_id'].'Comments';
	 $PostVal = $row13['details_id'];
	 $update1="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = 
	'".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id";
	$updatequery = mysqli_query($db,$update1);
	$datafill1="update nodueformentries set is_data_updated = 'Y',modified_by='$userid',modified_date_and_time='$date' where resignation_id=$resignation_id and department = 'System Administration'";
	$datafill=mysqli_query($db,$datafill1);
 }
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_sysadmin_status='C',modified_by=$userid where
 resignation_id=$resignation_id and no_due_sysadmin_status='F'");
}
if($no_due_manager_status == 'F' ){
 $query5 = mysqli_query($db,"SELECT * FROM `nodueformentries` where resignation_id=$resignation_id and department=(select department from all_departments where department=(select department from employee_details where employee_id=(select employee_id from employee_resignation_information where resignation_id=$resignation_id)))");
 while($row17 = mysqli_fetch_assoc($query5))
 {
	 $PostVal = $row17['details_id'];
	 $comm=$row17['details_id'].'Comments';
	 $deptname= $row17['department'];
	 $update1="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = 
	'".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id ";
	$updatequery = mysqli_query($db,$update1);
	 $datafill=mysqli_query($db,"update nodueformentries set is_data_updated = 'Y',modified_by='$userid',modified_date_and_time='$date' where resignation_id=$resignation_id and department = '$deptname'");
 }
 //$message=$depname;
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_manager_status='C',modified_by=$userid where
 resignation_id=$resignation_id and no_due_manager_status='F'");
}
if($no_due_acc_status == 'F' ){
	$query19 = mysqli_query($db,"select * from nodueformentries where department='Accounts' and resignation_id=$resignation_id");
 while($row19 = mysqli_fetch_assoc($query19))
 {
	 $comm=$row19['details_id'].'Comments';
	 $PostVal = $row19['details_id'];
	 $update1="update nodueformentries set comments='".mysqli_real_escape_string($db,$_POST[$comm])."',status = 
	'".$_POST[$PostVal]."' where details_id = '$PostVal' and resignation_id=$resignation_id";
	$updatequery = mysqli_query($db,$update1);
	$datafill1="update nodueformentries set is_data_updated = 'Y',modified_by='$userid',modified_date_and_time='$date' where resignation_id=$resignation_id and department = 'Accounts'";
	$datafill=mysqli_query($db,$datafill1);
 }
 $update3=mysqli_query($db,"update employee_resignation_information set no_due_acc_status='C',modified_by=$userid where
 resignation_id=$resignation_id and no_due_acc_status='F'");
}

 //$message=$datafill1;
 header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['Submit_Value']))  {
   $query34 = mysqli_query($db,"update `employee_resignation_information` set exit_interview_status='Y' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
   header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['Submit_ValSys']))  {
   $query34 = mysqli_query($db,"update `employee_resignation_information` set no_due_sysadmin_status='Y' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
   header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['Submit_ValAcc']))  {
   $query34 = mysqli_query($db,"update `employee_resignation_information` set no_due_acc_status='Y' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
   header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['Submit_ValDept']))  {
   $query34 = mysqli_query($db,"update `employee_resignation_information` set no_due_manager_status='Y' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
   header("Location: hrnodueprocessingform.php?res_id=$res_id");
}

else if (isset($_POST['Submit_ValAdmn']))  {
   $query34 = mysqli_query($db,"update `employee_resignation_information` set no_due_admin_status='Y' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
   header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['AutoSubmit_ValDept'])){
	$query35 = mysqli_query($db,"update `employee_resignation_information` set no_due_manager_status='F' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
	header("Location: hrnodueprocessingform.php?res_id=$res_id");
	
}
else if (isset($_POST['AutoSubmit_ValAcc'])){
	$query35 = mysqli_query($db,"update `employee_resignation_information` set no_due_acc_status='F' where  is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
	header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
else if (isset($_POST['AutoSubmit_ValSys'])){
	$query35 = mysqli_query($db,"update `employee_resignation_information` set no_due_sysadmin_status='F' where is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
	header("Location: hrnodueprocessingform.php?res_id=$res_id");
}

else if (isset($_POST['AutoSubmit_ValAdmn'])){
	$query35 = mysqli_query($db,"update `employee_resignation_information` set no_due_admin_status='F' where is_active='Y' and process_queue='HR_Manager_Process' and resignation_id=$resignation_id");
	header("Location: hrnodueprocessingform.php?res_id=$res_id");
}
 else {
}
$certQuery = mysqli_query($db,"select * from nodueformentries where department='Human Resources' and resignation_id=$resignation_id");
$adminQuery = mysqli_query($db,"select * from nodueformentries where department='Administration' and resignation_id=$resignation_id");
$sysadminQuery = mysqli_query($db,"select * from nodueformentries where department='System Administration' and resignation_id=$resignation_id");
$accQuery = mysqli_query($db,"select * from nodueformentries where department='Accounts' and resignation_id=$resignation_id");
$deptQuery = mysqli_query($db,"select * from nodueformentries where department not in ('Accounts','Human Resources','Administration','System Administration') and resignation_id=$resignation_id");
$accQuery1 = mysqli_query($db,"select distinct is_data_updated from nodueformentries where department='Accounts' and resignation_id=$resignation_id");
$checkq-mysqli_query($db,"SELECT distinct department as dept FROM `nodueformentries`
where resignation_id=$resignation_id and is_data_updated='Y' and department='Human Resources'");
$sysadminQuery1 = mysqli_query($db,"select distinct is_data_updated from nodueformentries where department='System Administration' and resignation_id=$resignation_id");
$certQuery1 = mysqli_query($db,"select distinct is_data_updated from nodueformentries where department='Human Resources' and resignation_id=$resignation_id");
$adminQuery1 = mysqli_query($db,"select distinct is_data_updated from nodueformentries where department='Administration' and resignation_id=$resignation_id");
$deptQuery1 = mysqli_query($db,"select distinct is_data_updated from nodueformentries where department not in ('Accounts','Human Resources','Administration','System Administration') and resignation_id=$resignation_id");
$nothingcnt=mysqli_query($db,"select * from nodueformentries where  resignation_id=$resignation_id and is_data_updated<>'Y'");
$nothingcnt1=mysqli_query($db,"select * from exitinterviewformenteries where  resignation_id=$resignation_id and status='Y'");
$nothingcnt2=mysqli_query($db,"select * from exitinterviewformenteries where  resignation_id=$resignation_id and is_data_updated='Y'");




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
#savevalues,#saveadmvalues,#savedepvalues,#saveaccvalues,#savehadmvalues{
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
#autosavedep,#autosaveacc,#autosavesys,#autosaveadmn{
	background-color: #529abd;
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
	border-color:#529abd;
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
#goprevious1{
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
#myOverlay{position:absolute;height:100%;width:100%;}
#myOverlay{background:black;opacity:.7;z-index:2;display:none;}

#loadingGIF{position:absolute;top:50%;left:50%;z-index:3;display:none;}

 .tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
  }
  
  .tooltip .tooltiptext {
    visibility: hidden;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    /* Position the tooltip */
    position: absolute;
    display: block;
    z-index: 1;
  }
  
  .tooltip:hover .tooltiptext {
    visibility: visible;
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
			 <?php if($userRole == 'HR') {?>
			 <input action="action" class="btn btn-info pull-left" onclick="window.location='noduehrform.php';" type="button" value="Back" id="goprevious"/> <?php }?>
			<?php if($userRole == 'HR Manager') {?>
			 <input action="action" class="btn btn-info pull-left" onclick="window.location='resignationprocessingform.php';" type="button" value="Back" id="goprevious"/> <?php }?>
			
				   
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
          
           <div class="border-class">
			<br>
          <div class="box box-widget widget-user-2">
            
          
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-olive-active">
              <h3 class="widget-user-username" style="font-weight:400;padding-left:1%"><?php echo $userempvalue ." - ".$usernamevalue; ?></h3>
              <h5 class="widget-user-desc" style="padding-left:1%;font-size: 20px ! important"><?php echo $userDesgvalue ." - ".$userDeptvalue ; ?></h5>
            </div>
            <div class="widget-user-image" style="top: 60px ! important;">
              <img class="img-circle" style="height:110px;width:110px" src="<?php echo $profPicPath; ?>" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $usermngrempvalue ." - ".$usermngrnamevalue; ?></h5>
                    <span class="description-text">Manager</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $userDtvalue; ?></h5>
                    <span class="description-text">Date of leaving</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          </div>
              <!-- /.box-footer -->			  		  
          <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		   <div class="border-class">
           <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
              <div class="box-body">
			  <label style="font-size: 20px;"> HUMAN RESOURCES </label>
			  <?php if($exitvalue =='N'){ ?>
			   <input type= "submit" name= "Submit_Value"  class="btn btn-info pull-right" value= "Begin Exit Process" style = "margin-right: 7px;" id="savevalues" />
			 <?php } ?>			   
			  <?php while($row39 = mysqli_fetch_assoc($certQuery1))
				{if($row39['is_data_updated']=='Y')
				{
					 echo "<i class='fa fa-check-square-o' style='color:green;font-size:17px;' aria-hidden='true'></i>";
				}
				else
				{
					echo "<i class='fa fa-times-rectangle-o' style='color:red;font-size:17px;' aria-hidden='true'></i>";
				}}?>
			  <?php 
			  if(mysqli_num_rows($certQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
				  echo "<div class='form-group'>";
                while($row = mysqli_fetch_assoc($certQuery)){
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px'>";
				if($row['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value=".$row['status'].">".$row['status']."</option>
				<option value='No'>No</option></select>";
				}
				else if($row['status']=='No')
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value=".$row['status'].">".$row['status']."</option>
				<option value='Yes'>Yes</option></select>";
				}
				else
				{
				echo "<select class='form-control' id=".$row['details_id']." name=".$row['details_id'].">
				<option value='No'>No</option>
				<option value='Yes'>Yes</option></select>";
					
				}
				echo "<textarea class='form-control' type='text' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll' id=".$row['details_id'].'Comments'." name=".$row['details_id'].'Comments'." placeholder='Comments if any' autocomplete='off'>".$row['comments']."</textarea>";
				echo "</div>";
				
                    $i++;
					}
				  echo "</div>";
				}
                ?>
				<hr style="width:100%;border-top:1px solid #00c0ef !important;"align="left">
				<label style="font-size: 20px;"> ADMINISTRATION </label>				
				<?php if($no_due_admin_status =='N' || $no_due_admin_status =='C'){ ?>
			   <input type= "submit" name= "Submit_ValAdmn"  class="btn btn-info pull-right" value= "Begin Admin No Due" style = "margin-right: 7px;" id="savehadmvalues" />
			   <input type= "submit" name= "AutoSubmit_ValAdmn"  class="btn btn-info pull-right" value= "Auto Submit" style = "margin-right: 7px;" id="autosaveadmn" />
			 <?php } ?>		
				
				<label></label>
				<?php while($row33 = mysqli_fetch_assoc($adminQuery1))
				{if($row33['is_data_updated']=='Y')
				{
					 echo "<i class='fa fa-check-square-o' style='color:green;font-size:17px;' aria-hidden='true'></i>";
				}
				else
				{
					echo "<i class='fa fa-times-rectangle-o' style='color:red;font-size:17px;' aria-hidden='true'></i>";
				}}?>
				 <?php 
			  if(mysqli_num_rows($adminQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;				  
				  echo "<br><br><div class='form-group' id='admid'>";
				  echo "<input type='hidden' id='adminstatus' value='$no_due_admin_status'></input>";
                while($row1 = mysqli_fetch_assoc($adminQuery)){
				
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row1['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px;'>";
				if($row1['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row1['details_id']." name=".$row1['details_id']." disabled>
				<option value=".$row1['status'].">".$row1['status']."</option>
				<option value='No'>No</option></select>";
				}
				else if($row1['status']=='No')
				{
				echo "<select class='form-control' id=".$row1['details_id']." name=".$row1['details_id']." disabled>
				<option value=".$row1['status'].">".$row1['status']."</option>
				<option value='Yes'>Yes</option></select>";
				}
				else
				{
				echo "<select class='form-control' id=".$row1['details_id']." name=".$row1['details_id']." disabled>
				<option value='No'>No</option>
				<option value='Yes'>Yes</option></select>";
					
				}
				echo "<textarea class='form-control' type='text' placeholder='Comments if any' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll' title='".$row1['comments']."' id='testinput' disabled>".$row1['comments']."</textarea>";
				echo "</div>";
                    $i++;
                  }
				  echo "</div>";
				}
                ?>
				<hr style="width:100%;border-top:1px solid #00c0ef !important;"align="left">
				<label style="font-size: 20px;"> SYSTEM ADMINISTRATION </label>
				<?php if($no_due_sysadmin_status =='N' || $no_due_sysadmin_status =='C'){ ?>
			   <input type= "submit" name= "Submit_ValSys"  class="btn btn-info pull-right" value= "Begin System Admin No Due" style = "margin-right: 7px;" id= "saveadmvalues" />
			   <input type= "submit" name= "AutoSubmit_ValSys"  class="btn btn-info pull-right" value= "Auto Submit" style = "margin-right: 7px;" id="autosavesys" />
			 <?php } ?>		
				<label></label>
				<?php while($row34 = mysqli_fetch_assoc($sysadminQuery1))
				{if($row34['is_data_updated']=='Y')
				{
					 echo "<i class='fa fa-check-square-o' style='color:green;font-size:17px;' aria-hidden='true'></i>";
				}
				else
				{
					echo "<i class='fa fa-times-rectangle-o' style='color:red;font-size:17px;' aria-hidden='true'></i>";
				}}?>
				 <?php 
			  if(mysqli_num_rows($sysadminQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
				  echo "<br><br><div class='form-group' id='sysid'>";
				  echo "<input type='hidden' id='sysstatus' value='$no_due_sysadmin_status'></input>";
                while($row43 = mysqli_fetch_assoc($sysadminQuery)){
				
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row43['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px;'>";
				if($row43['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row43['details_id']." name=".$row43['details_id']." disabled>
				<option value=".$row43['status'].">".$row43['status']."</option>
				<option value='No'>No</option></select>";
				}
				else if($row43['status']=='No')
				{
				echo "<select class='form-control' id=".$row43['details_id']." name=".$row43['details_id']." disabled>
				<option value=".$row43['status'].">".$row43['status']."</option>
				<option value='Yes'>Yes</option></select>";
				}
				else
				{
				echo "<select class='form-control' id=".$row43['details_id']." name=".$row43['details_id']." disabled>
				<option value='No'>No</option>
				<option value='Yes'>Yes</option></select>";	
				}
				echo "<textarea class='form-control' type='text' placeholder='Comments if any' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll' title='".$row43['comments']."' id='testinput' disabled>".$row43['comments']."</textarea>";
				echo "</div>";
                    $i++;
                  }
				  echo "</div>";
				}
                ?>
				<hr style="width:100%;border-top:1px solid #00c0ef !important;"align="left">
				<label style="font-size: 20px;"> ACCOUNTS </label>
				<?php if($no_due_acc_status =='N' || $no_due_acc_status =='C'){ ?>
			   <input type= "submit" name= "Submit_ValAcc"  class="btn btn-info pull-right" value= "Begin Accounts No Due" style = "margin-right: 7px;" id="saveaccvalues" />
			   <input type= "submit" name= "AutoSubmit_ValAcc"  class="btn btn-info pull-right" value= "Auto Submit" style = "margin-right: 7px;" id="autosaveacc" />
			 <?php } ?>		
				<label></label>
				<?php while($row45 = mysqli_fetch_assoc($accQuery1))
				{if($row45['is_data_updated']=='Y')
				{
					 echo "<i class='fa fa-check-square-o' style='color:green;font-size:17px;' aria-hidden='true'></i>";
				}
				else
				{
					echo "<i class='fa fa-times-rectangle-o' style='color:red;font-size:17px;' aria-hidden='true'></i>";
				}}?>
				 <?php 
			  if(mysqli_num_rows($accQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
				  echo "<div class='form-group' id='accid'>";
				  echo "<input type='hidden' id='accstatus' value='$no_due_acc_status'></input>";
                while($row16 = mysqli_fetch_assoc($accQuery)){
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row16['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px;'>";
				if($row16['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row16['details_id']." name=".$row16['details_id']." disabled>
				<option value=".$row16['status'].">Completed</option>
				<option value='No'>Not-Applicable</option></select>";
				}
				else if($row16['status']=='No')
				{
				echo "<select class='form-control' id=".$row16['details_id']." name=".$row16['details_id']." disabled>
				<option value=".$row16['status'].">Not-Applicable</option>
				<option value='Yes'>Completed</option></select>";
				}
				else
				{
				echo "<select class='form-control' id=".$row16['details_id']." name=".$row16['details_id']." disabled>
				<option value='No'>Not-Applicable</option>
				<option value='Yes'>Completed</option></select>";	
				}
				
				echo "<textarea class='form-control' type='text' placeholder='Comments if any' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll' title='".$row16['comments']."' id='testinput' disabled>".$row16['comments']."</textarea>"; 
				echo "</div>";
                    $i++;
				
                  }
				  
				  echo "</div>";
				}
                ?>
				
				<hr style="width:100%;border-top:1px solid #00c0ef !important;"align="left">
				<label style="font-size: 20px;"> DEPARTMENT </label>
				<?php if($no_due_manager_status =='N' || $no_due_manager_status =='C'){ ?>
			   <input type= "submit" name= "Submit_ValDept"  class="btn btn-info pull-right" value= "Begin Department No Due" style = "margin-right: 7px;" id="savedepvalues" />
			    <input type= "submit" name= "AutoSubmit_ValDept"  class="btn btn-info pull-right" value= "Auto Submit" style = "margin-right: 7px;" id="autosavedep" />
			 <?php } ?>		
				<label></label>
				<?php while($row49 = mysqli_fetch_assoc($deptQuery1))
				{if($row49['is_data_updated']=='Y')
				{
					 echo "<i class='fa fa-check-square-o' style='color:green;font-size:17px;' aria-hidden='true'></i>";
				}
				else
				{
					echo "<i class='fa fa-times-rectangle-o' style='color:red;font-size:17px;' aria-hidden='true'></i>";
				}}?>
				 <?php 
			  if(mysqli_num_rows($deptQuery) < 1){
                  echo "No Results Found";
                }else{
                  $i = 1;
				  
				  echo "<div class='form-group' id='deptid'>";
				  echo "<input type='hidden' id='deptstatus' value='$no_due_manager_status'></input>";
               while($row77 = mysqli_fetch_assoc($deptQuery)){
				echo "<label for='inputforform' class='col-sm-3 control-label'>".$row77['details']."</label>";
				echo "<div class='col-sm-2' style='margin-bottom:20px;'>";
				if($row77['status']=='Yes')
				{
				echo "<select class='form-control' id=".$row77['details_id']." name=".$row77['details_id']." disabled>
				<option value=".$row77['status'].">Completed</option>
				<option value='No'>Not-Completed</option></select>";
				}
				else if($row77['status']=='No')
				{
				echo "<select class='form-control' id=".$row77['details_id']." name=".$row77['details_id']." disabled>
				<option value=".$row77['status'].">Not-Completed</option>
				<option value='Yes'>Completed</option></select>";
				}
			      
				else
				{
				echo "<select class='form-control' id=".$row77['details_id']." name=".$row77['details_id']." disabled>
				<option value='No'>Not-Completed</option>
				<option value='Yes'>Completed</option></select>";	
				}
				echo"<textarea class='form-control' class='tooltiptext' type='text' placeholder='Comments if any' maxlength=1000 rows=4 style='margin-top: 7%;width:275px;resize:none;overflow-y:scroll' title='".$row77['comments']."' id='testinput' disabled>".$row77['comments']." </textarea>";
				echo "</div>";
                    $i++;
                  }
				  echo "</div>";
				  echo "</div>";
				}
                ?>
			  </div>
			  
			  <div class="box-footer"> 
			<?php 
			  if(mysqli_num_rows($nothingcnt) < 1){
                  echo "<a href='nodueform.php?res_id=$res_id'><input type= 'button' name= 'Generate' id='goprevious1' class='btn btn-info pull-right' value= 'Generate NO DUE' style = 'margin-right: 7px;' id='generate'/></a>";
                }?>		
				<?php 
			  if(mysqli_num_rows($nothingcnt1) > 1){
                  echo "<a href='exitinterviewhrform.php?res_id=$res_id'><input type= 'button' id='goprevious' name= 'Generate' class='btn btn-info pull-right' value= 'Generate Exit Interview' style = 'margin-right: 7px;' id='generate'/></a>";
                }?>	
				<?php 
			  if(mysqli_num_rows($nothingcnt2) > 1){
                  echo "<a href='generatepdf/DownloadRL.php?id=$resignation_id'><input type= 'button' id='goprevious' name= 'Generate' class='btn btn-info pull-right' value= 'Generate Relieving Letter' style = 'margin-right: 7px;' id='generate'/></a>";
                }?>					
				
			  			   
				<input type= "submit" name= "Submit"  class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields" />			   
              </div>  	
			  </form>
          </div>	
		 
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
<script>
  $(function() {
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3,#currentdate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});
$(document).ready(function(){
if(document.getElementById('adminstatus').value=='F')
{
  $('#admid').find(':input').prop('disabled', false);
  //document.getElementById("autosaveadmn").style.display='none';
  //document.getElementById("saveadmvalues").style.display='none';
  $( "#autosaveadmn" ).prop( "disabled", true );
  $( "#savehadmvalues" ).prop( "disabled", true );
}
if(document.getElementById('deptstatus').value=='F')
{
  $('#deptid').find(':input').prop('disabled', false);
  //document.getElementById("autosavedep").style.display='none';
  //document.getElementById("savedepvalues").style.display='none';
  $( "#autosavedep" ).prop( "disabled", true );
  $( "#savedepvalues" ).prop( "disabled", true );
}
if(document.getElementById('accstatus').value=='F')
{
  $('#accid').find(':input').prop('disabled', false);
  //document.getElementById("autosaveacc").style.display='none';
  //document.getElementById("saveaccvalues").style.display='none';
  $( "#autosaveacc" ).prop( "disabled", true );
  $( "#saveaccvalues" ).prop( "disabled", true );
}
if(document.getElementById('sysstatus').value=='F')
{
  $('#sysid').find(':input').prop('disabled', false);
  //document.getElementById("autosavesys").style.display='none';
  //document.getElementById("saveadmvalues").style.display='none';
  $( "#autosavesys" ).prop( "disabled", true );
  $( "#saveadmvalues" ).prop( "disabled", true );
}
});
	</script>
<script>

	$(function() {
  var bid, trid;
  $('#resgnchange tr').click(function() {
       Id = $(this).find('.EmpId').text();
		$('#EmpIdvalue').val(Id);
  });
});
$("#savefields").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
});
$("#savevalues").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
  alert ("Exit Interview has been intialized for the Employee");
 location.reload();
});

$("#savehadmvalues").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
  alert ("Administration No Due has been intialized for the Employee");
 location.reload();
});
$("#saveadmvalues").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
  alert ("System Admin No Due been intialized for the Employee");
 location.reload();
});
$("#saveaccvalues").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
  alert ("Accounts No Due has been intialized for the Employee");
 location.reload();
});
$("#savedepvalues").click(function() {
  ajaxindicatorstart("Processing..Please Wait..");
  alert ("Department No Due has been intialized for the Employee");
 location.reload();
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
	$(document).ready(function(){
	$('#testinput'.keyup(function(){
		$(this).attr('title',$(this).val())
	})
	})
	
</body>
</html>
