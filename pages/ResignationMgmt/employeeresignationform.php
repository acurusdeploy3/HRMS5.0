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
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
	if(isset($_GET['resignation_id']) && $_GET['resignation_id'] != ''){
	$resignation_id = $_GET['resignation_id'];}
if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$reason_for_resignation=mysqli_real_escape_string($db,$_POST["reason_for_resignation"]);
$employee_comments=mysqli_real_escape_string($db,$_POST["employee_comments"]);
$query1="INSERT INTO employee_resignation_information( employee_id, date_of_submission_of_resignation, reason_for_resignation, created_by, modified_by, created_date_and_time,status,is_active,Date_Of_Leaving,actual_date_of_leaving,employee_comments,is_notification_sent,hr_comments,process_queue,reporting_manager_comments,hr_reason_update,res_id_value) 
	VALUES('".$userid."','".$date."','$reason_for_resignation',
'".$userid."','".$userid."','".$date."','Resignation Request Sent','Y',date_add((SELECT if(is_services_confirmed='N',date_add(date(current_date()), interval 1 month),date_add(date(current_date()), interval 2 month)) FROM `employee_details` where employee_id=$userid), interval -1 day),date_add((SELECT if(is_services_confirmed='N',date_add(date(current_date()), interval 1 month),date_add(date(current_date()), interval 2 month)) FROM `employee_details` where employee_id=$userid), interval -1 day),'$employee_comments','N','','Employee_Process','','',SUBSTRING(MD5(RAND()) FROM 1 FOR 15))";
	$result = mysqli_query($db,$query1);
$query2 ="Update employee_resignation_information set reporting_manager_id=(select manager_id from pms_manager_lookup
where employee_id=$userid),pending_queue_id=(select concat(manager_id,'-rep') from pms_manager_lookup
where employee_id=$userid) where employee_id=$userid and is_active='Y' order by resignation_id desc limit 1 ";
$result2=mysqli_query($db,$query2);
$query21 ="Insert into employee_resignation_transaction (resignation_id,employee_id,Process,Assigned_To,description,
created_by,created_date_and_time,is_active) values ((select resignation_id from employee_resignation_information where employee_id=$userid and is_active='Y'),'".$userid."','Resignation Request',
(select manager_id from pms_manager_lookup where employee_id=$userid),'Manager','".$userid."','".$date."','Y')";
$result21=mysqli_query($db,$query21);


	 if(!$result){
			$message="Problem adding to database . Please Retry";
			
			
	} else {
		header("Location:sendemail.php");
	}
$updatevalue = mysqli_query ($db,"select if((select employee_id from all_hods where department in (select department from employee_details ei where employee_id=$userid and is_active='Y')and location in(select business_unit from employee_details ei where employee_id=$userid and is_active='Y'))=reporting_manager_id,concat(reporting_manager_id,'-hod'),
concat(reporting_manager_id,'-rep')) as value from employee_resignation_information
where employee_id=$userid and is_active='Y'");
$updt=mysqli_fetch_assoc($updatevalue);
$value= $updt['value'];

$hodupdate = mysqli_query($db,"update employee_resignation_information set pending_queue_id='$value',process_queue='Manager_Process' where employee_id=$userid and is_active='Y'");	

$repudate = mysqli_query($db,"update employee_resignation_information set process_queue='Employee_Process' where employee_id=$userid and is_active='Y' and substring_index(Pending_queue_id,'-',-1) = 'rep'");	

}

$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$certQuery = mysqli_query($db,"SELECT resignation_id,res_id_value,if(Process_Queue='Employee_Process','Waiting for Manager Approval',
if(Process_Queue='Manager_Process','Waiting for HOD Approval',
if(process_queue='HOD_Process','Waiting in HR Manager Queue',
if(process_Queue='HR_Manager_Approved','Resignation Approved',
if(process_Queue='Manager_Cancel' || process_Queue='HR_Manager_Cancel','Cancellation Requested',
if(process_Queue='Manager_Cancelled' || process_Queue='HR_Manager_Cancelled' || process_Queue='HOD_Cancelled','Resignation Request Cancelled',
if(process_queue='HR_Manager_Process','Exit Process Initiated',''))))))) as queue, employee_id,date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds , reason_for_resignation, status, Is_Active,date_format(date(date_of_leaving),'%d %b %Y') as dl,employee_comments,if(date_add(date(date_of_submission_of_resignation), interval 2 day) >= date(current_date()),'Y','N') as notify,is_notification_sent,hr_comments,allocated_to,process_queue,exit_interview_status
 FROM employee_resignation_information where employee_id = '".$userid."'  ");
$activequery = mysqli_query($db,"SELECT employee_id,is_active from employee_resignation_information where employee_id = '".$userid."' and Is_Active='Y' order by date(date_of_leaving) asc");
$active=mysqli_fetch_assoc($activequery);
$isactive= $active['is_active'];
$formenteries2 = mysqli_query($db,"SELECT distinct resignation_id,e.status as stats FROM `exitinterviewformenteries` e left join employee_resignation_information r on e.resignation_id=r.resignation_id  where employee_id=$userid and r.is_Active='Y'");
$detRow1 = mysqli_fetch_array($formenteries2);
$statval = $detRow1['stats'];
$resval=$detRow1['resignation_id'];

$reasonsdropdown =  mysqli_query($db,"SELECT resignation_reason FROM `all_reasons` where category='No Due Form'");

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
        <div class="col-md-11" style="width:100% ! important;">
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
					<label for="inputcurrentdate" class="col-sm-4 control-label">Date Of Submission of Resignation<span class="error">*  </span></label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="currentdate" name="currentdate" value="<?php echo $date; ?>"  required disabled	 ></input>
					</div>	
				</div>
				<div class="form-group">
					<label for="inputresgreason" class="col-sm-4 control-label">Reason for Resignation<span class="error">*  </span></label>
					<div class="col-sm-4">
						<select class="form-control" name="reason_for_resignation" id="reason_for_resignation" required>
							<option value=""></option>
							<?php
							while($row5 = mysqli_fetch_assoc($reasonsdropdown))
							{?>
							<option value="<?php echo $row5['resignation_reason']; ?>"><?php echo $row5['resignation_reason']; ?></option>
							<?php }  ?>                                               
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="inputresgcomments" id="commlabel" class="col-sm-4 control-label">Comments<span class="error" id ="errorval" >*  </span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="employee_comments"  name="employee_comments" required>
						</input>
					</div>					
				</div>
				</div>
				<div class="box-footer">
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>                
					<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
					<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
					<?php
					if($isactive=='N'){					
					?>
					<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
					<?php }
					else if($isactive=='Y'){
					?>
					<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields" disabled>
					<?php } else {?>
					<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
					<?php } ?>
					
				</div>
              <!-- /.box-footer -->			  		   
		  <div class="border-class">
            <table class="table" style="font-size:14px;">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Date Of Submission Of Resignation</th>
					<th>Reason</th>          
					<th>Status</th>
					<th>Date Of Leaving </th>
					<th>Comments</th>
					<th>Action</th>
                </tr>
                <?php
                if(mysqli_num_rows($certQuery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($certQuery)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
                    echo "<td style='width:15%'>".$row['ds']."</td>";
                    echo "<td style='width:20%'>".$row['reason_for_resignation']."</td>";
                    echo "<td style='width:17%'>".$row['queue']."</td>";
					if($row['dl']=='01-Jan-0001')
					{
						echo "<td></td>";
					}
					else
					{
					echo "<td style='width:10%'>".$row['dl']."</td>";
					}
					echo "<td style='width:40%'>".$row['employee_comments']."</td>";
					if($row['exit_interview_status']=='Y') 
					{
						echo "<td><a href='exitinterviewform.php?res_id=".$row['res_id_value']."'>
						<input type='button' id='gonext' value='Exit Interview'></input> </a></td>";
					}
					else if($row['status']=='Request for Cancellation of Resignation' && $row['process_queue']=='Manager_Cancel' || $row['status']=='Request for Cancellation of Resignation' && $row['process_queue']=='HR_Manager_Cancel')
					{
						echo "<td><a href='employeeretractionform.php?res_id=".$row['res_id_value']."'>
						<input type='button' id='gonext' value='Cancel'></input> </a></td>";
					}
					else
					{
						echo "<td></td>";
					}
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<!-- Page script -->


	<script>
	/* $("#reason_for_resignation").change(function() {
		if($("#reason_for_resignation option:selected").val()=='Others'){
				document.getElementById('errorval').style.display = "inline";
		}
		else{
			document.getElementById('errorval').style.display = "none";
		}
    }); */
	function clearfields()
	{
		document.getElementById("reason_for_resignation").value="";
	}
	$("#savefields").click(function() {
		/* if($("#reason_for_resignation option:selected").val()=='Others'){
			$("#employee_comments").prop('required',true);	
			if(document.getElementById('employee_comments').value != '')
			{
				ajaxindicatorstart("Processing..Please Wait..");
			}
			else
			{
				
			}
		}
		else if(document.getElementById('reason_for_resignation').value !=''){
			ajaxindicatorstart("Processing..Please Wait..");
			$("#employee_comments").prop('required',false);	
		}
		else{
			$("#employee_comments").prop('required',false);	
		}*/
    if(document.getElementById('employee_comments').value != '')
			{
				ajaxindicatorstart("Processing..Please Wait..");
			}
			else
			{
				
			}
		});
	</script>
<?php
require_once('layouts/documentModals.php');
?>
</body>
</html>
