<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp=='System Admin Manager' || $usergrp=='System Admin') 
{
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Employee Boarding</title>
 <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
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
  <script src="../../dist/js/loader.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
.astrick
{
	color:red;
}
</style>
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Employee Boarding
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="BoardingHome.php">Boarding Home</a></li>
        <li class="active">Complete Formalities</li>
      </ol>
    </section>
<?php
$EmployeeID = $_GET['id'];
session_start();
$_SESSION['Employee_id']=$EmployeeID;
include("config2.php");
$getName = mysqli_query($db,"select concat(First_Name,' ',last_Name,' ',MI) as Name,primary_mobile_number,department,Job_Type,official_email,substring_index(official_email,'@',-1) as mailtype,workstation from employee_details where employee_id=$EmployeeID");
$getNameRow = mysqli_fetch_array($getName);
$getNameVal = $getNameRow['Name']; 
$Contact = $getNameRow['primary_mobile_number']; 
$department = $getNameRow['department']; 
$Job_Type = $getNameRow['Job_Type']; 
$official_email = $getNameRow['official_email'];  
if($getNameRow['mailtype']=='intramail.acurussolutions.com'){
	$MailTypeVal='IntraMail';
}
else{
	$MailTypeVal='Acurus Mail';
}
$getFamily = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Mother' and employee_id='$EmployeeID' ");
$getFamilyMother = mysqli_fetch_array($getFamily);
$MotherName = $getFamilyMother['family_member_name'];
$getFamilyDad = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Father' and employee_id='$EmployeeID' ");
$getFamilyDaDRow = mysqli_fetch_array($getFamilyDad);
$DadName = $getFamilyDaDRow['family_member_name'];
$getBoardingAdmin = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id='$EmployeeID';");
$getBoardingAdminRow = mysqli_fetch_array($getBoardingAdmin);
$SystemLogin = $getBoardingAdminRow['system_login'];
$system_login_password = $getBoardingAdminRow['system_login_password'];
$mail_login = $getBoardingAdminRow['mail_login'];
$mail_login_password = $getBoardingAdminRow['mail_login_password'];
$os_type = $getBoardingAdminRow['os_type'];
$getAllos = mysqli_query($db,"select OS_Type from all_os_types where os_type!='$os_type'");
?>
    <!-- Main content -->
    <section class="content">
<!-- For Admin -->
<?php
if($usergrp=='System Admin Manager' || $usergrp=='System Admin')
{
?>
  <div class="box box-default">
        <div class="box-header with-border">
	<div class="box-header">
			
			 <table>
			  <tbody>
			  <tr>
			  <th></th>
			  <th></th>
			  <th></th>
			  </tr>
			  <tr>
			  <td>
				<button OnClick="window.location.href='BoardingHome.php'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
			  </td>
			  
			  </tr>
			  </tbody>
			  </table>
			  <br>
             <h4 class="box-title"><strong><?php echo $getNameVal ?> : <?php echo $EmployeeID  ?></strong></h4>
			  <br>
			  <div class="box-tools pull-right">
          </div>
			  
            </div>	
	 <div class="row">
	  <div class="col-md-6">
	  <form id="AdminForm" method="post">
	  <?php
		include("config2.php");
		?>
		 <div class="form-group">
		    <label>Employee ID <span class="astrick">*</span></label>
                <input type="text" tabindex="1"   name="EmployeeIDSys" id="EmployeeIDSys" value="<?php echo $EmployeeID; ?>"  class="form-control" placeholder="Employee ID" readonly>
              </div>
		  <div class="form-group">
		    <label>Mother's Name <span class="astrick">*</span></label>
                <input type="text" tabindex="3"   name="MotherName" id="MotherName" value="<?php echo $MotherName; ?>"  class="form-control" placeholder="Mother's Name" readonly>
              </div>
 <div class="form-group">
		    <label>Employee Contact <span class="astrick">*</span></label>
                <input type="text" tabindex="5"   name="EmpContact" id="EmpContact" value="<?php echo $Contact; ?>"  class="form-control" placeholder="Employee Contact" readonly>
              </div>
<div class="form-group">
		    <label>Department <span class="astrick">*</span></label>
                <input type="text" tabindex="7"   name="EmpDepartment" id="EmpDepartment" value="<?php echo $department; ?>"  class="form-control" placeholder="Employee Department" readonly>
              </div>	
	<div class="form-group">
		    <label >Official E-Mail  <span class="astrick">*</span></label>
                <input type="email" tabindex="9" pattern="[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*" name="OfficeMail" id="OfficeMail" value="<?php echo $official_email; ?>"  class="form-control" placeholder="Official Mail" />
              </div>
</div>  
 <div class="col-md-6">
	  <?php
		include("config2.php");
		
		?>
		 <div class="form-group">
		    <label>Name <span class="astrick">*</span></label>
                <input type="text" tabindex="2"   name="EmpName" id="EmpName" value="<?php echo $getNameVal; ?>"  class="form-control" placeholder="Employee Name" readonly>
              </div>
		 <div class="form-group">
		    <label>Father's Name <span class="astrick">*</span></label>
                <input type="text" tabindex="4"   name="FatherName" id="FatherName" value="<?php echo $DadName; ?>"  class="form-control" placeholder="Father's Name" readonly>
              </div>
		 
 <div class="form-group">
		    <label >Job Type  <span class="astrick">*</span></label>
                <input type="text" tabindex="6" name="JobTypeSel" id="JobTypeSel" value="<?php echo $Job_Type; ?>"  class="form-control" placeholder="Job Type" readonly>
              </div>	
			<div class="form-group">
		    <label>E-Mail Type  <span class="astrick">*</span></label>
                <input type="text" tabindex="8" name="EmailType" id="EmailType" value="<?php echo $MailTypeVal; ?>" class="form-control" placeholder="Mail Type" readonly>
              </div>
			   <div class="form-group">
		    <label>Official E-Mail Password  <span class="astrick">*</span></label>
                <input type="text" tabindex="10" name="OfficeMailPwd" id="OfficeMailPwd" value="<?php echo $mail_login_password ?>"  class="form-control" placeholder="Mail Password" required></input>
              </div> 	  
</div>
</div>  
<input type="submit" id="AdminSubmitBtn" value="Submit"  class="btn btn-primary pull-right" />
</div>  
  </div>
  </form>
<?php
}
?>
 <!-- /.box-body -->  
    </section>
    <!-- /.content -->
 </div>
 
 <?php
 
 require_once('Layouts/documentModals.php');
 ?>
      <!-- /.box --
      <!-- /.row -->

  <!-- /.content-wrapper -->
  <footer class="main-footer">
  
    <strong><a href="#">Acurus Solutions Private Limited</a>.</strong> 
  </footer>
   </div>     
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
<!-- Page script -->
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
<script type="text/javascript">
var OffEmail = document.getElementById('OfficialMail');

if (OffEmail.value == "") {
    document.getElementById('OffMailLbl').style.color = "red";
}

</script>

	<script type="text/javascript">
      $(document).ready(function() {
    $("#AdminForm").submit(function(e) {
if(document.getElementById('OfficeMailPwd').value=='')
{
	
}
else
{
	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#AdminForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "SendMailChangeNotification.php",
         success: function(data){

			window.location.href = "BoardingHome.php";
		   ajaxindicatorstop();

         }
});
}
});
});
    </script>
<script>
function pagereload()
{
			location.reload();
			 ajaxindicatorstop();
}

	

	
	
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>