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
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
if(isset($_GET['res_id']) && $_GET['res_id'] != ''){
	$res_id = $_GET['res_id'];}
$result99=mysqli_query($db,"SELECT * FROM `employee_resignation_information` where res_id_value = '".$res_id."'");
$detRow99 = mysqli_fetch_array($result99);
$resignation_id=$detRow99['resignation_id'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
if(isset($_POST['Submit'])){
	$hr_add_comments=mysqli_real_escape_string($db,$_POST['hr_add_comments']);
	$iquery ="update exitinterviewformenteries set comments='$hr_add_comments',status='Y' where value_data = 'HR_Comments2' and resignation_id=$resignation_id";
	$insrtrow=mysqli_query($db,$iquery);
	$updtquery = "Update exitinterviewformenteries set is_data_updated='Y' where resignation_id=$resignation_id";
	$updtrow=mysqli_query($db,$updtquery);
	$updtquery1 = "Update employee_resignation_information set exit_interview_status='F',modified_by=$userid where resignation_id=$resignation_id";
	$updtrow1=mysqli_query($db,$updtquery1);
	header("Location:exitinterviewhrform.php?res_id=$res_id");
}
$query1=mysqli_query($db,"select  resignation_id,concat(First_name,' ',MI,' ',Last_Name) as EmpName,d.employee_id,date_format(Date_Joined,'%d %b %Y') as Date_Joined,
date_format(date(date_of_leaving),'%d %b %Y') as dl,date_format(date(date_of_submission_of_resignation),'%d %b %Y') as ds,Employee_Designation,Department,r.process_queue 
from employee_resignation_information r inner join employee_details d on r.employee_id=d.employee_id where (r.process_queue='HR_Manager_Process' or r.process_queue='HR_Completed' or r.process_queue='No_Due_Completed') and resignation_id=$resignation_id");
$detRow=mysqli_fetch_array($query1);
$formenteries = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Option_value%' and resignation_id=$resignation_id");
$formenteries1 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Text_value%' and resignation_id=$resignation_id");
$formenteries2 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Text_Comments%' and resignation_id=$resignation_id");
$formenteries3 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Drop_Value%' and resignation_id=$resignation_id");
$formenteries4 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'HR_Comments%' and resignation_id=$resignation_id");
$nothingcnt1=mysqli_query($db,"select * from exitinterviewformenteries where  resignation_id=$resignation_id and is_data_updated='Y'");
$detRow1 = mysqli_fetch_array($formenteries2);
$detRow2 = mysqli_fetch_array($formenteries4);


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
	.skin-blue .sidebar a {
    color: #ffffff;}
	.error {color: #FF0000;}
img {
    vertical-align: middle;
    height: 30px;
    width: 30px;
    border-radius: 20px;
}
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
.close2 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close2:hover,
.close2:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.close3 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close3:hover,
.close3:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
td, th {
  border: 1px solid black;
  text-align: left;
  padding: 8px;
  width:5%;
  height:30px;
}
.special1
{
    font-weight: bold;
}
</style>
<style>
@page { size: auto;  margin: 0mm; }

</style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
 
 <div class="content-wrapper">
   <section class="content-header">
      <h1 style="text-align: center;">
       ACURUS EMPLOYEE FORM
      <small> Resignation </small>
	  <?php if(mysqli_num_rows($nothingcnt1) > 1){
	  echo "<button type='button' style='margin-right:10px;position:relative;left:250;'  id='downloadpdf' onclick='print();' class='btn btn-success pull-right'>Download PDF &nbsp; <i class='fa fa-fw fa-file-pdf-o'> </i></button>"; } ?>
      </h1>
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
          
              <div class="box-body">
				</div>
			
			 <div class="box-footer">				   
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class" id="exitdownload">
		  <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" style="width:90%" >
			<h3 style="color:#65669a;text-align: -webkit-center;text-decoration: underline;">ACURUS SOLUTIONS PRIVATE LTD</h3>
			<h4 style="color:#65669a;text-align: -webkit-center;text-decoration: underline;">EXIT INTERVIEW FORM</h4>
			<table style="width:100%;border:1px solid black;">
		<tr>
			<td style ="width:20%;border:none;" class="special1">Name:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['EmpName']."</td>"; ?>
			<td style ="width:20%;border:none;" class="special1">Date Of Designation:</td>
			<?php  echo "<td style='width:40%;border:none;'>".$detRow['Date_Joined']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%;border:none;" class="special1">Employee ID:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['employee_id']."</td>"; ?>
			<td style ="width:20%;border:none;" class="special1">Resignation letter dated:</td>
			<?php  echo "<td style='width:40%;border:none;'>".$detRow['ds']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%;border:none;" class="special1">Employee Designation:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['Employee_Designation']."</td>"; ?>
			<td style ="width:20%;border:none;" class="special1">Date last worked:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['dl']."</td>"; ?>
		</tr>
		<tr>
			<td style ="width:20%;border:none;" class="special1">Project / Department:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['Department']."</td>"; ?>
			<td style ="width:20%;border:none;" class="special1">Date relieved:</td>
			<?php  echo "<td style='width:20%;border:none;'>".$detRow['dl']."</td>"; ?>
		</tr>
		</table>
		<br>
		<table style="width:100%;">
		<tr>
			<td class="special1" style="border:none;">1.  Top 3 reasons for leaving the Organization:</td>
		</tr>
		</table>
		<table style="width:100%;">
		<?php 
		$i=1;
		while($row3 = mysqli_fetch_assoc($formenteries3)){
			echo "<tr>";
			echo "<td style='border:none;padding-left: 50px;width:1px'>".$i."</td>";
			echo "<td style='border:none;width:1000px;'>".$row3['comments']."</td>";
			echo "</tr>";
			$i++;
		}
			?>
		</table>
		<table style="width:100%;">
		<tr>
			<td class="special1" style="border:none;">2.  Please complete the following</td>
		</tr>
		</table>
		<table style="width:100%;">
		<tr>
			<td style ="width:50%;background-color:#b3b3b3" class="special1"></td>
			<td style ="width:10%" class="special1">Strongly agree</td>
			<td style ="width:10%" class="special1">Agree</td>
			<td style ="width:10%" class="special1">Disagree</td>
			<td style ="width:10%" class="special1">Strongly disagree</td>
		</tr>
		<?php 
		$i=1;
		while($row31 = mysqli_fetch_assoc($formenteries)){
			echo "<tr>";
			echo "<td style='width:50%;' class='special1'>".$row31['value']."</td>";
			if($row31['comments']=='Strongly Agree'){
			echo "<td style='width:10%;'>Yes</td>";}
			else{
			echo "<td style='width:10%;'></td>";}
			if($row31['comments']=='Agree'){
			echo "<td style='width:10%;'>Yes</td>";}
			else{
			echo "<td style='width:10%;'></td>";}
			if($row31['comments']=='Disagree'){
			echo "<td style='width:10%;'>Yes</td>";}
			else{
			echo "<td style='width:10%;'></td>";}
			if($row31['comments']=='Strongly Disagree'){
			echo "<td style='width:10%;'>Yes</td>";}
			else{
			echo "<td style='width:10%;'></td>";}
			echo "</tr>";
			$i++;
		}
			?>
		</table>
		<br>
		<table style="width:100%;">
		<?php 
		$i=3;
		while($row32 = mysqli_fetch_assoc($formenteries1)){
		echo "<tr>";
		echo "<td style='border:none;width:100%;' class='special1'>".$row32['value']."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td style='border:none;width:100%;'>".$row32['comments']."</td>";
		echo "</tr>";
		$i++;
		}?>
		</table>
		<table style="width:100%;">
		<?php 
		$i=3;
		while($row33 = mysqli_fetch_assoc($formenteries2)){
		echo "<tr>";
		echo "<td style='border:none;width:100%;' class='special1'>".$row33['value']."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td style='border:none;width:100%;'>".$row33['comments']."</td>";
		echo "</tr>";
		$i++;
		}?>
		</table>
		<br>
		<table style="width:100%;">
		<tr>
		<td style="border:none;" class="special1">Your opinion is very important to us. Additional comments and suggestions are encouraged.</td>
		</tr>
		<td style="border:none;"><?php echo $detRow1['comments']; ?></td>
		</tr>
		</table>
		<table style="width:100%;">
		<tr>
			<td style="width:5%;border:none;" class="special1">Date:</td>
			<td style="width:55%;border:none;"><?php echo $date?></td>
			<td style="width:25%;border:none;" class="special1">Employee Signature:</td>
			<td style="width:25%;border:none;"></td>
		</tr>
		</table>
		<table style="width:100%;">
		<tr>
			<td class="special1" style="border:none;">HR Comments<span class="error">*  </span></td>
		</tr>
		<tr>
		<?php if(mysqli_num_rows($nothingcnt1) > 1){
		echo "<td style='border:none;'>".$detRow2['comments']."</td>";}
		?>
		<?php if(mysqli_num_rows($nothingcnt1) < 1){
		echo "<td style='border:none;'><input type='text' class='form-control' name='hr_add_comments' id='hr_add_comments' required></input></td>";}
			?>
		</tr>
		</table>
		<table style="width:100%;">
		<tr>
			<td style="width:5%;border:none;" class="special1">Date:</td>
			<td style="width:55%;border:none;"><?php echo $date?></td>
			<td style="width:25%;border:none;" class="special1">HR Signature:</td>
			<td style="width:25%;border:none;"></td>
		</tr>
		</table>
		<?php if(mysqli_num_rows($nothingcnt1) < 1){
		echo "<input type= 'submit' name= 'Submit' class='btn btn-info pull-right' value= 'Save' style = 'margin-right: 7px;' id='savefields' />";}
		?>
		</form>
          </div>
			  <div class="box-footer">   
							   
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
  $(function() {
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3,#currentdate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});
</script>

<script>
function print() {
	document.getElementById("downloadpdf").style.display="none";
    var e = document.getElementById(exitdownload);
    printWindow = window.open(), printWindow.document.write("<div style='width:70%;margin:0'>"), printWindow.document.write("<div id='exitdownload' onload="javascript:window.print();"/>"), printWindow.document.write("</div>"), printWindow.setTimeout(function () {
        printWindow.close()
    }, 1e4)
}
</script>
</body>
</html>
