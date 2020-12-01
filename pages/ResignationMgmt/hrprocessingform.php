<?php   
session_start();  
$userid=$_SESSION['login_user'];
$res_id = $_GET['res_id'];
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

require_once("config.php");
require_once("queries.php");
$date1 = date("Y-m-d");
	if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
$empidval=$detRow99['employee_id'];
if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$hrcomments=mysqli_real_escape_string($db,$_POST["hr_comments"]);
if($_POST['status']== 'Request for Cancellation of Resignation' ){
$query28 ="Update employee_resignation_information r set hr_reason_update='".$_POST["hr_reason_update"]."',modified_by=$userid,hr_comments=concat('||','$hrcomments'),process_queue='HR_Manager_Cancel',status='".$_POST["status"]."',date_of_leaving='0001-01-01',modified_date_and_time=now(),pending_queue_id=concat('$empidval','-emp')
where resignation_id=$resignation_id and is_active='Y'";
$result28=mysqli_query($db,$query28);
	$query21 ="Insert into employee_resignation_transaction (resignation_id,employee_id,Process,Assigned_To,description,
	created_by,created_date_and_time) values ('".$temp."','".$id."','Process Resignation Request',
	(SELECT employee_id FROM `employee_details` where job_role='HR MANAGER' and is_active='Y'),'HR Manager','".$name."','".$date."')";
	$result21=mysqli_query($db,$query21);
}
else if($_POST['status']== 'Hold Resignation' ){
	$query19 ="Update employee_resignation_information set hr_reason_update='".$_POST["hr_reason_update"]."',modified_by=$userid,hr_comments='$hrcomments',process_queue='HR_Manager_Hold',status='".$_POST["status"]."',modified_date_and_time=now()
where resignation_id=$resignation_id and is_active='Y'";
$result19=mysqli_query($db,$query19);
}
else if($_POST['status']== 'Process Resignation'){
$query2 ="Update employee_resignation_information set hr_reason_update='".$_POST["hr_reason_update"]."',hr_comments=concat('||',$userid,'--','$hrcomments'),
modified_date_and_time=now(),
allocated_to=(select concat(First_Name,'',MI,'',Last_Name,'-',employee_id) from employee_details where employee_id in
(SELECT value FROM `application_configuration` where module='RESIGNATION'
and parameter='HR_NOTIFY' and config_type='EMAIL')),process_queue='HR_Manager_Approved',modified_by=$userid,status='".$_POST["status"]."',exit_interview_status='N',
no_due_sysadmin_allocated_to=(SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details`
where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='ADMIN_ID')),
no_due_acc_allocated_to=(SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details`
where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='ACC_ID')),
no_due_admin_allocated_to=(SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details`
where employee_id in (SELECT value FROM `application_configuration` where module='RESIGNATION' and parameter='ADMINISTRATION_ID'))
where resignation_id=$resignation_id and is_active='Y'";
$result2=mysqli_query($db,$query2);
//echo $query2;

$query81 ="Update employee_resignation_information set pending_queue_id= concat(SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1),'-acc,',SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1),'-adm,',
employee_id,'-emp,',reporting_manager_id,'-rep,',concat(SUBSTRING_INDEX($userid,'-',-1),'-hrm,'),concat(SUBSTRING_INDEX(allocated_to,'-',-1),'-hr,'),
SUBSTRING_INDEX(no_due_admin_allocated_to, '-', -1),'-hdm')
where resignation_id=$resignation_id and is_active='Y'";
$result81=mysqli_query($db,$query81);

//echo $query81;

$query82 ="Update employee_resignation_information r set no_due_sysadmin_allocated_to = (SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as admname FROM `employee_details` d where d.employee_id=r.reporting_manager_id and d.is_active='Y') where r.employee_id=SUBSTRING_INDEX(no_due_sysadmin_allocated_to,'-',-1) and r.resignation_id=$resignation_id and r.is_active='Y'";
$result82=mysqli_query($db,$query82);
//echo $query82;

$query89 = "Update employee_resignation_information set pending_queue_id= concat(SUBSTRING_INDEX(no_due_acc_allocated_to, '-', -1),'-acc,',SUBSTRING_INDEX(no_due_sysadmin_allocated_to, '-', -1),'-adm,',
employee_id,'-emp,',reporting_manager_id,'-rep,',if(SUBSTRING_INDEX(allocated_to,'-',-1)=$userid,(SUBSTRING_INDEX(allocated_to,'-',-1),'-hrm,'),(SUBSTRING_INDEX(allocated_to,'-',-1),'-hr,'),
SUBSTRING_INDEX(no_due_admin_allocated_to, '-', -1),'-hdm')  
where resignation_id=$resignation_id and is_active='Y'";
$result89=mysqli_query($db,$query89);

//echo $query89;

$query3="select * from nodueformentries where resignation_id=$resignation_id";
$result3=mysqli_query($db,$query3);
if(mysqli_num_rows($result3) < 1){
$query13="select employee_id from employee_resignation_information where resignation_id=$resignation_id";
$result13=mysqli_query($db,$query13);
$valuerow13 = mysqli_fetch_array($result13);
$epid8=$valuerow13['employee_id'];
$dateleaving = $valuerow13['date_of_leaving'];

$query31 = mysqli_query($db,"Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
select resignation_id,(select department from employee_details where employee_id =r.employee_id),'Knowledge Transfer :','No','KnowledgeTransfer:',date(now()),'Acurus'
FROM employee_resignation_information r where date_of_leaving = date(now()) and is_active='Y'");

$query32 = mysqli_query($db,"Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
SELECT resignation_id,Description,value,'No',replace(substring_index(value,'.',-1),' ',''),date(now()),'Acurus' FROM all_fields a,employee_resignation_information r
where field_name='Details_1' and date_of_leaving = date(now()) and is_active='Y' and category='No Due Form' order by sort_order");

$query33 = mysqli_query($db,"Insert into exitinterviewformenteries (resignation_id,value,comments,status,value_data,created_date_and_time,created_by)
SELECT  resignation_id,value,'' as comments,'N' as status,concat(description,sort_order),date(now()),'Acurus'
FROM `all_fields` a ,employee_resignation_information r where category='Exit Interview Form' and date_of_leaving = date(now()) and is_active='Y'");

$query34 = mysqli_query($db,"update `employee_resignation_information` set exit_interview_status='N' , process_queue='HR_Manager_Process'
where date_of_leaving = date(now()) and is_active='Y' and process_queue='HR_Manager_Approved' and employee_id=$epid8");

/* if($date1 == $dateleaving){	
$query11="Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
select '".$resignation_id."',(select department from employee_details where employee_id =$epid8),'Knowledge Transfer :','No','KnowledgeTransfer:','$date','$userid'";
$result11=mysqli_query($db,$query11);

echo $query11;
$query5="Insert into nodueformentries (resignation_id,Department,Details,status,details_id,created_date_and_time,created_by)
SELECT '".$resignation_id."',Description,value,'No',replace(substring_index(value,'.',-1),' ',''),'$date','$userid' FROM all_fields a where field_name='Details_1' and category='No Due Form' order by sort_order";
$result5=mysqli_query($db,$query5);
echo $query5;
$query67="Insert into exitinterviewformenteries (resignation_id,value,comments,status,value_data,created_date_and_time,created_by)
SELECT '".$resignation_id."'  as resignation_id,value,'' as comments,'N' as status,concat(description,sort_order),'$date','$userid'
FROM `all_fields` where category='Exit Interview Form'";
$result67=mysqli_query($db,$query67); 
echo $query67;
$query68="Insert into exitinterviewformenteries (resignation_id,value,comments,status,value_data)
//SELECT '".$resignation_id."' as resignation_id,'hr_comments','' as comments,'N' as status,'Comments_value1'
//FROM `all_fields` where category='Exit Interview Form' and description like 'HR_Comments%'";
$result68=mysqli_query($db,$query68);
} */
}
}
$temp = $_GET['resignation_id'];
	if ($_POST['status']== 'Hold Resignation'){
	//$query6="select employee_id from employee_resignation_information where resignation_id=$resignation_id";
	//$result6=mysqli_query($db,$query6);
	//$valuerow = mysqli_fetch_array($result6);
	//$eid=$valuerow['employee_id'];
	//$message=$query2;
	header("Location: sendhrstatusmail.php?rid=$resignation_id");
	}
	else if ($_POST['status']== 'Request for Cancellation of Resignation'){
	header("Location: sendcancellationreqemail.php?rid=$resignation_id");	
	}
	else
	{
	$query8="select employee_id from employee_resignation_information where resignation_id=$resignation_id";
	$result8=mysqli_query($db,$query8);
	$valuerow1 = mysqli_fetch_array($result8);
	$epid=$valuerow1['employee_id'];
	header("Location: sendhrstatusmailaccept.php?id=$epid");	
	}
	//header("Location: nodueform.php?resignation_id=".$resignation_id."");
}
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$certQuery = mysqli_query($db,"SELECT  resignation_id,ei.employee_id as employee_id,concat(ed.First_name,' ',ed.MI,' ',ed.Last_Name) as NameOfEmployee,
date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds,
reason_for_resignation, status,hr_reason_update,hr_comments, allocated_to,
date_format(date(date_of_leaving),'%Y-%m-%d') as dl,ed.department,
ei.reporting_manager_id as manger_id,
employee_comments,reporting_manager_comments,process_queue,
concat(ed1.employee_id,' - ',ed1.First_name,' ',ed1.MI,' ',ed1.Last_Name) as manager_name
FROM `employee_resignation_information` ei
inner join employee_details ed on ei.employee_id=ed.employee_id
inner join employee_details ed1 on ei.reporting_manager_id=ed1.employee_id where resignation_id = '".$resignation_id."' and ei.is_active='Y'");

$detRow = mysqli_fetch_array($certQuery);
$employeeid=$detRow['employee_id'];
$hrdropdown =  mysqli_query($db,"SELECT concat(First_name,' ',MI,' ',Last_Name,'-',employee_id) as hrname FROM `employee_details` where job_role like 'HR' and is_active='Y' and employee_id <> $employeeid");
$hrnme=$detRow['allocated_to'];
$reasonupdate=$detRow['hr_reason_update'];
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
$reasonsdropdown =  mysqli_query($db,"SELECT resignation_reason FROM `all_reasons` where category='No Due Form' and resignation_reason <> '$reasonupdate'");

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
#clearfields{
	background-color: #da3010;
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
	border-color:#da3010;
	color:white;
	border: 1px solid transparent;
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
th {
  background-color: #31607c;
  color:white;
}
textarea {
  resize: none;
}
#gonext{
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
#myOverlay{position:absolute;height:100%;width:100%;}
#myOverlay{background:black;opacity:.7;z-index:2;display:none;}

#loadingGIF{position:absolute;top:50%;left:50%;z-index:3;display:none;}
/* The Close Button   */

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
/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
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
          <div class="box box-info">
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
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
              <div class="box-body">
			  <div class="form-group">
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Employee ID</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="employee_id" name="employee_id" value="<?php echo $detRow['employee_id']; ?>"  required disabled	 >
					</input>
					</div>	
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Employee Name</label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="NameOfEmployee" name="NameOfEmployee" value="<?php echo $detRow['NameOfEmployee']; ?>"  required disabled	 >
					</input>
					</div>	
					</div>
					<div class="form-group">
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Department</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="department" name="department" value="<?php echo $detRow['department']; ?>"  required disabled	 >
					</input>
					</div>	
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Manager </label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="manager_id" name="manager_id" value="<?php echo $detRow['manager_name']; ?>"  required disabled	 >
					</input>
					</div>	
					</div>
				 <div class="form-group">
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Date Of Submission of Resignation</label>
				<div class="col-sm-4">
                    <input type="text" placeholder = "yyyy-mm-dd" class="form-control" id="currentdate" name="currentdate" value="<?php echo $detRow['ds']; ?>"  required disabled	 >
					</input>
					</div>	
					<label for="inputresgreason" class="col-sm-2 control-label">Status<span class="error">*  </span></label>
				<div class="col-sm-4">
					<?php 
					$stats =  $detRow['status'];
					if ($stats == 'Process Resignation')
					{
				   ?>
                    <select class="form-control" name="status" id ="status" required >
					<option value="<?php echo $stats ?>"><?php echo $stats ?></option>
                      <option value="Request for Cancellation of Resignation">Request for Cancellation of Resignation</option>
					  <!--<option value="Hold Resignation">Hold Resignation</option>  -->     
                    </select>
					<?php
					}
					else if ($stats == 'Request for Cancellation of Resignation')
					{
					?>
					<select class="form-control" name="status" id ="status" required >
					<option value="<?php echo $stats ?>"><?php echo $stats ?></option>
                      <option value="Process Resignation">Process Resignation</option>
					  <!-- <option value="Hold Resignation">Hold Resignation</option>      --> 
                    </select>
					<?php
					} else if($stats == 'Hold Resignation'){?>
					<select class="form-control" name="status" id ="status" required >
					<option value="<?php echo $stats ?>"><?php echo $stats ?></option>
                      <option value="Process Resignation">Process Resignation</option>
					  <option value="Request for Cancellation of Resignation">Request for Cancellation of Resignation</option>      
                    </select>
					<?php }
					else {?>
					<select class="form-control" name="status" id ="status" required >
                      <option value="Process Resignation">Process Resignation</option>
					  <option value="Request for Cancellation of Resignation">Request for Cancellation of Resignation</option> 
					  <!-- <option value="Hold Resignation">Hold Resignation</option>-->     </select>
					<?php } ?>
					</input>
					</div>	
					</div>
					<div class="form-group">
				<label for="inputresgreason" class="col-sm-2 control-label">Employee's Reason for Resignation</label>
				<div class="col-sm-10">
					<textarea style="height:10%" class="form-control" id="reason_for_resignation"  name="reason_for_resignation" required disabled><?php echo $detRow['reason_for_resignation']; ?> </textarea>
					</div>					
				  </div>
				  <div class="form-group">
				<label for="inputresgreason" class="col-sm-2 control-label">Employee Comments</label>
				<div class="col-sm-10">
                    <textarea style="height:10%" class="form-control" id="employee_comments"  name="employee_comments" required disabled><?php echo $detRow['employee_comments']; ?> 
					</textarea>
					</div>					
				  </div>
				  <div class="form-group">
				<label for="inputresgreason" class="col-sm-2 control-label">Manager Comments</label>
				<div class="col-sm-10">
                    <textarea style="height:10%" class="form-control" id="reporting_manager_comments"  name="reporting_manager_comments" required disabled><?php echo $detRow['reporting_manager_comments']; ?> 
					</textarea>
					</div>					
				  </div>
				  <div class="form-group">
				  <?php 
					$stats =  $detRow['status'];
					if ($stats == 'Process Resignation')
					{
				   ?>
                  <label for="inputcurrentdate" class="col-sm-2 control-label">Date Of leaving<span class="error">*  </span></label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="Date_Of_Leaving" name="Date_Of_Leaving" value="<?php echo $detRow['dl']; ?>"  required disabled >
					</input>
					</div>	
					<?php } else {?>
					<label for="inputcurrentdate" class="col-sm-2 control-label">Date Of leaving<span class="error">*  </span></label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="Date_Of_Leaving" name="Date_Of_Leaving" value="<?php echo $detRow['dl']; ?>" disabled>
					</input>
					</div>	
					<?php } ?>
				<label for="inputresgreason" class="col-sm-1 control-label">Reason</label>
				<div class="col-sm-4">
		 <select class="form-control" name="hr_reason_update" id="hr_reason_update">
                     <option value="<?php echo $reasonupdate ?>"><?php echo $reasonupdate ?></option>
                    <?php
                    while($row5 = mysqli_fetch_assoc($reasonsdropdown))
					{
					?>
					
			<option value="<?php echo $row5['resignation_reason']; ?>"><?php echo $row5['resignation_reason']; ?></option>
					<?php
                    }
                    ?>
                                                                                 
                    </select>
					</div>	
					<div class = "col-sm-1">
				  <a href="#" id="myBtn1" title="Click to Add another Reason" data-toggle="modal" data-target="#modal-default-Reason"><i class="fa fa-fw fa-plus"></i></a>	
				  </div>	
				<div id="myModal1" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close12">&times;</span>
						<p>Add New Reason:</p>
								<input type="text"  class="form-control" id="inputReason" name="inputReason" placeholder="Enter Reason" /><br>
					<input type="button" id="addReasonbtn"  name="AddResBtn"  class = "btn btn-primary"value = "Add Reason"></input>
						</div>
			</div> 				  
				  </div>
				  <div class="form-group">
				<label for="inputresgreason" class="col-sm-2 control-label">Comments <span class="error">*  </span></label>
				<div class="col-sm-10">
                    <input type="text" class="form-control" id="hr_comments"  name="hr_comments" value="<?php echo $detRow['hr_comments']; ?>" required>
					</input>
					</div>					
				  </div>
				  
				</div>
			
			 <div class="box-footer">
			 <?php if($usergrp == 'HR Manager') {?>
			 <input action="action" class="btn btn-info pull-left" onclick="window.location='resignationprocessingform.php';" type="button" value="Back" id="goprevious"/>      <?php } else {?>
			  <input action="action" class="btn btn-info pull-left" onclick="window.location='resignationapprovalform.php';" type="button" value="Back" id="goprevious"/>
			 <?php } ?>	
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
					
					<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields"/>
					
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
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


	<script>
	function clearfields()
	{
		document.getElementById("reason_for_resignation").value="";
	}
	</script>
	 <script>
   $(function() {
	  var str = $('#currentdate').val();
	  var res = str.split("-");
	  var monthNames = [ "","Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
	  var monthval = monthNames.indexOf(res[1]);
	  var mindate = res[2]+"-"+monthval+"-"+res[0];
	 // var mindate =new Date(document.getElementById('currentdate').value);
  $("#datepicker,#Date_Of_Leaving").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true,
	startDate :mindate,
  });
});
$("#savefields").click(function() {
if(document.getElementById("hr_comments").value=="")
{
}
else{
 ajaxindicatorstart("Processing..Please Wait..");
}
});
</script>
<script>

	$('select[name=status]').change(function () {
        if ($(this).val() == 'Request for Cancellation of Resignation') {
            $('#allocated_to').prop('required',false);
			$('#allocated_to').prop('disabled',true);
			$('#Date_Of_Leaving').prop('required',false);
			$('#Date_Of_Leaving').prop('disabled',true);
        } else if($(this).val() == 'Process Resignation') {
            $('#allocated_to').prop('required',true);
			$('#allocated_to').prop('disabled',false);
			$('#Date_Of_Leaving').prop('required',true);
			$('#Date_Of_Leaving').prop('disabled',false);
        }
		else
		{ $('#allocated_to').prop('required',false);
		  $('#allocated_to').prop('disabled',true);
			$('#Date_Of_Leaving').prop('required',false);
			$('#Date_Of_Leaving').prop('disabled',true);
		}
    });
</script>
 <script type="text/javascript">
       $(document).on('click','#addReasonbtn',function(e) {
		   var data = $("#inputReason").serialize();
			e.preventDefault();
  $.ajax({
         data: data,
         type: "post",
         url: "addingnewReason.php",
         success: function(data){
			AddingReason();
         }
});
 });
    </script>
 <!-- JS-->
<script type="text/javascript">
       function AddingReason() {
			debugger;
			var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("hr_reason_update");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputReason").value;
            option.value = document.getElementById("inputReason").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputReason").value="";      
        }
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
<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
