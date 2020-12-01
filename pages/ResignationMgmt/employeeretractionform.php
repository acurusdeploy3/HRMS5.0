<?php   
require_once("queries.php");
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
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
	$certQuery = mysqli_query($db,"SELECT resignation_id, employee_id,date_format(date(date_of_submission_of_resignation),'%d-%b-%Y') as ds , reason_for_resignation, status, Is_Active,date_format(date(date_of_leaving),'%d-%b-%Y') as dl,employee_comments,if(date_add(date(date_of_submission_of_resignation), interval 2 day) >= date(current_date()),'Y','N') as notify,is_notification_sent,hr_comments,allocated_to,process_queue,exit_interview_status,reporting_manager_comments
FROM employee_resignation_information where employee_id = '$userid' and status='Request for Cancellation of Resignation' and (process_queue='Manager_Cancel' or process_queue='HR_Manager_Cancel') and resignation_id='$resignation_id' ");
$detRow = mysqli_fetch_array($certQuery);
$processqueue=$detRow['process_queue'];
if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$uploaddir = '../../uploads/';
$DocType='Resignation Cancellation Letter';
$uploadfile = $uploaddir .basename($_FILES['file-upload']['name']);
//$_POST['file-upload'];
$FileData = pathinfo($uploadfile);
$FileExt=$FileData['extension'];
//$new_name = $uploaddir.$dat.'_'.$DocType.'_'.$userid.'.'.$FileExt ;
$FileNamewithoutextension = pathinfo($uploadfile, PATHINFO_FILENAME);
if(move_uploaded_file($_FILES['file-upload']['tmp_name'], $uploadfile)){
$dat =date("Ymd_Hi");
$old_name = $uploadfile;
$new_name = $uploaddir.$dat.'_'.$DocType.'_'.$userid.'.'.$FileExt ;
rename( $old_name, $new_name);
$namewithdir = $dat.'_'.$DocType.'_'.$userid.'.'.$FileExt ;
$InsertResume = mysqli_query($db,"insert into employee_documents_tracker (employee_id,document_type,created_Date_and_time,created_by,is_active,document_module)
values	('$userid','Resignation Cancellation Letter',now(),'$createdby','Y','Employee_Resignation')");
$getDocId = mysqli_query($db,"select distinct(doc_id) as doc_id from employee_documents_tracker where document_type='Resignation Cancellation Letter' and employee_id='$userid' and is_active='Y'");
$DocIDRow  = mysqli_fetch_array($getDocId);
$DocId = $DocIDRow['doc_id'];
$InsertdOC = mysqli_query($db,"insert into employee_documents (doc_id,document_name,created_date_and_Time,created_by,is_active)
values	('$DocId','$namewithdir',now(),'$createdby','Y')");
}
$cancellation_comments=mysqli_real_escape_string($db,$_POST["cancellation_comments"]);	
if($processqueue == 'Manager_Cancel')
{
$query2 ="Update employee_resignation_information set date_of_cancellation_of_resignation='".$date."' , employee_cancellation_comments = '$cancellation_comments', date_of_leaving='0001-01-01',
status = 'Cancel Resignation' , process_queue ='Manager_Cancelled',modified_date_and_time=now()
 where employee_id=$userid and resignation_id='$resignation_id' and is_active='Y'
and status='Request for Cancellation of Resignation' and process_queue='Manager_Cancel' 
order by resignation_id desc limit 1 ";
$result2=mysqli_query($db,$query2);
$result = mysqli_query($db,"UPDATE employee_resignation_information set is_active='N' WHERE employee_id=$userid and resignation_id=$resignation_id");
storeDataInHistory($resignation_id , "employee_resignation_information","resignation_id");
	 if(!$result2){
			$message="Problem adding to database . Please Retry";			
	} else {
		header("Location:sendcancellationaccemail.php?rid=$resignation_id");
	}
}
else if($processqueue == 'HR_Manager_Cancel')
{
	$query2 ="Update employee_resignation_information set date_of_cancellation_of_resignation='".$date."' , employee_cancellation_comments = '$cancellation_comments', date_of_leaving='0001-01-01',
status = 'Cancel Resignation' , process_queue ='HR_Manager_Cancelled',modified_date_and_time=now()
 where employee_id=$userid and resignation_id='$resignation_id' and is_active='Y'
and status='Request for Cancellation of Resignation' and process_queue='HR_Manager_Cancel' 
order by resignation_id desc limit 1 ";
$result2=mysqli_query($db,$query2);
$result = mysqli_query($db,"UPDATE employee_resignation_information set is_active='N' WHERE employee_id=$userid and resignation_id=$resignation_id");
storeDataInHistory($resignation_id , "employee_resignation_information","resignation_id");
	 if(!$result2){
			$message="Problem adding to database . Please Retry";			
	} else {
		header("Location:sendcancellationaccemail.php?rid=$resignation_id");
	}
}
else{
	
}
}

$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$certQuery = mysqli_query($db,"SELECT resignation_id, employee_id,date_format(date(date_of_submission_of_resignation),'%Y-%m-%d') as ds , reason_for_resignation, status, Is_Active,date_format(date(date_of_leaving),'%d-%b-%Y') as dl,employee_comments,if(date_add(date(date_of_submission_of_resignation), interval 2 day) >= date(current_date()),'Y','N') as notify,is_notification_sent,hr_comments,allocated_to,process_queue,exit_interview_status,reporting_manager_comments
FROM employee_resignation_information where employee_id = '$userid' and status='Request for Cancellation of Resignation' and (process_queue='Manager_Cancel' or process_queue='HR_Manager_Cancel') and resignation_id='$resignation_id' ");
$detRow = mysqli_fetch_array($certQuery);
$processqueue=$detRow['process_queue'];




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
.custom-file-upload {
  border: 1px solid #ccc;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer; 
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
#myOverlay{position:absolute;height:100%;width:100%;z-index: 1800;}
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
                  <label for="inputcurrentdate" class="col-sm-4 control-label">Date Of Submission of Resignation</label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="currentdate" name="currentdate" value="<?php echo $detRow[ds]; ?>"  disabled>
					</input>
					</div>	
					</div>
					<div class="form-group">
				<label for="inputresgreason" class="col-sm-4 control-label">Reason for Resignation</label>
				<div class="col-sm-6">
                    <input type="text" class="form-control" id="reason_for_resignation"  name="reason_for_resignation" value="<?php echo $detRow['reason_for_resignation']; ?>" disabled>
					</input>
					</div>					
				  </div>
				  <div class="form-group">
				<label for="inputresgstatus" class="col-sm-4 control-label">Status</label>
				<div class="col-sm-6">
                    <input type="text" class="form-control" id="employee_comments"  name="employee_comments" value="<?php echo $detRow['status']; ?>" disabled>
					</input>
					</div>					
				  </div>
				  <div class="form-group">
                  <label for="inputcurrentdate" class="col-sm-4 control-label">Date Of Cancellation of Resignation<span class="error">*  </span></label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" id="currentdate" name="currentdate" value="<?php echo $date; ?>"  required disabled	 >
					</input>
					</div>	
					</div>
				  <div class="form-group">
				<label for="inputresgcomments" class="col-sm-4 control-label">Comments<span class="error">*  </span></label>
				<div class="col-sm-6">
                    <input type="text" class="form-control" id="cancellation_comments"  name="cancellation_comments" required>
					</input>
					</div>					
				  </div>
				<!--  <div class="form-group">
				<label for="inputresgcomments" class="col-sm-4 control-label">Upload Signed Cancellation Document<span class="error">*  </span></label>				
					<div class="col-sm-4">
					<label for="file-upload" class="custom-file-upload">
					<i class="fa fa-upload" style="font-size: 20px;" aria-hidden="true"></i>
					</label>
					<input id="file-upload" name='file-upload' type="file" style="display:none;" required></input>
					<label id="file-name" ></label>
				  </div>
				</div>-->
			 <div class="box-footer">
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='employeeresignationform.php';" type="button" value="Back" id="goprevious"/>                
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
					<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">

					
              </div>
              <!-- /.box-footer -->			  		  
          
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<!-- Page script -->

<script>
document.querySelector("#file-upload").onchange = function(){
  document.querySelector("#file-name").textContent = this.files[0].name;
  document.getElementById('file-name').style.color = "green";
}
</script>
	<script>
	$("#savefields").click(function() {
		//if(document.querySelector("#file-name").textContent == '' || document.querySelector("#file-name").textContent == 'Kindly Upload Document')
		//{
		//	document.querySelector("#file-name").textContent = 'Kindly Upload Document';
		//	document.getElementById('file-name').style.color = "red";
		//}
		//else
		//{
			//document.getElementById('file-name').style.color = "green";
			
			//ajaxindicatorstart("Processing..Please Wait..");
		//}
		if(document.getElementById('cancellation_comments').value==''){
			
		}
		else{
			ajaxindicatorstart("Processing..Please Wait..");
		}
});
	</script>
<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
