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
$getName = mysqli_query($db,"select concat(First_Name,' ',last_Name,' ',MI) as Name,primary_mobile_number,department,Job_Type,official_email,workstation from employee_details where employee_id=$EmployeeID");
$getNameRow = mysqli_fetch_array($getName);
$getNameVal = $getNameRow['Name']; 
$Contact = $getNameRow['primary_mobile_number']; 
$department = $getNameRow['department']; 
$Job_Type = $getNameRow['Job_Type']; 
$official_email = $getNameRow['official_email']; 
$workstation = $getNameRow['workstation']; 
$getFamily = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Mother' and employee_id='$EmployeeID' ");
$getFamilyMother = mysqli_fetch_array($getFamily);
$MotherName = $getFamilyMother['family_member_name'];
$getFamilyDad = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee='Father' and employee_id='$EmployeeID' ");
$getFamilyDaDRow = mysqli_fetch_array($getFamilyDad);
$DadName = $getFamilyDaDRow['family_member_name'];

$getAllWS = mysqli_query($db,"select number from all_workstations where number not in (select workstation from employee_details)");
$getBoardingAdmin = mysqli_query($db,"SELECT system_login,system_login_password,mail_login,mail_login_password,os_type FROM `boarding_admin` where employee_id='$EmployeeID';");
$getBoardingAdminRow = mysqli_fetch_array($getBoardingAdmin);
$SystemLogin = $getBoardingAdminRow['system_login'];
$system_login_password = $getBoardingAdminRow['system_login_password'];
$mail_login = $getBoardingAdminRow['mail_login'];
$mail_login_password = $getBoardingAdminRow['mail_login_password'];
$os_type = $getBoardingAdminRow['os_type'];
$getAllos = mysqli_query($db,"select OS_Type from all_os_types where os_type!='$os_type'");
?>
<?php
			  $getMailType = mysqli_query($db,"select mail_type from employee_boarding where employee_id='$EmployeeID'");
			  $getMailTypeRow = mysqli_fetch_array($getMailType);
				$MailTypeVal = $getMailTypeRow['mail_type'];
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
			  <div class="form-group">
		    <label >Operating System <span class="astrick">*</span></label>
                 <select class="form-control select2" tabindex="11" id="OSType" name="OSType" required="required" style="width: 100%;" required>
				 <?php
				 if($os_type=='')
				 {
				 ?>
                   <option value="" selected disabled>Please Select an OS Below</option>
				   <?php
				 }
				 else
				 {
				   ?>
				    <option value="<?php echo $os_type  ?>" selected><?php echo $os_type  ?></option>
				   <?php
				 }
				   ?>
				  <?php  
				  while($getAllosRow= mysqli_fetch_assoc($getAllos))
				  {
				  ?>
				  <option value="<?php echo $getAllosRow['OS_Type']  ?>"><?php echo $getAllosRow['OS_Type']  ?></option>
					<?php
				  }
					?>
				</select>
              </div>
			  <div class="form-group">
		    <label>Sytem Login UserName <span class="astrick">*</span></label>
                <input type="text" tabindex="13"   name="WindowsLogin" id="WindowsLogin" value="<?php echo $SystemLogin ?>"  class="form-control" placeholder="System Login UserName">
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
                <input type="text" tabindex="10" name="OfficeMailPwd" id="OfficeMailPwd" value="<?php echo $mail_login_password ?>"  class="form-control" placeholder="Mail Password">
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
<script type="text/javascript">
   $(document).on('click','#SendtoAdmin',function(e) {
	  var returnval = true;
		ajaxindicatorstart("Please Wait..");
		e.preventDefault();
		var MailType = $('#MailType').val();
		if(MailType==null)
		{
			alert("Please Choose Official Mail Type.");
			returnval = false;
		}
		if(returnval==true)
		{
			 var data = $("#ChooseForm").serialize();
				$.ajax({
							data: data,
							type: "post",
							url: "SendAdminMail.php",
							success: function(data)
								{
									$('#btnADM').click();
									ajaxindicatorstop();
								}
 
					});
		}
		else
		{
			ajaxindicatorstop();
		}
 });
</script>
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
 var AcntNum = document.getElementById('AccountNumber');

if (AcntNum.value == "") {
    document.getElementById('AccnoLbl').style.color = "red";
}


var ifsc = document.getElementById('IFSCCode');

if (ifsc.value == "") {
    document.getElementById('IFSCLbl').style.color = "red";
}

var EmpRole = document.getElementById('RoleSelect');

if (EmpRole.value == "") {
    document.getElementById('RoleLbl').style.color = "red";
}


var EmpDept = document.getElementById('DeptSelect');

if (EmpDept.value == "") {
    document.getElementById('DeptLbl').style.color = "red";
}


var Reporting = document.getElementById('RepMgmr');

if (Reporting.value == "") {
    document.getElementById('RepMgrLbl').style.color = "red";
}

var Reporting = document.getElementById('MentorSel');

if (Reporting.value == "") {
    document.getElementById('MentorLBl').style.color = "red";
}
var OffEmail = document.getElementById('OfficialMail');

if (OffEmail.value == "") {
    document.getElementById('OffMailLbl').style.color = "red";
}
var BUSel = document.getElementById('BusinessUnitSelect');

if (BUSel.value == "") {
    document.getElementById('BULbl').style.color = "red";
}



var LoSel = document.getElementById('LocalOfficeSelect');

if (LoSel.value == "") {
    document.getElementById('LoLbl').style.color = "red";
}


var PanVal = document.getElementById('PANNumber');

if (PanVal.value == "") {
    document.getElementById('PANLbl').style.color = "red";
}


var SPVal = document.getElementById('SalaryPaymentModeSelect');

if (SPVal.value == "") {
    document.getElementById('SpLbl').style.color = "red";
}


var SBVal = document.getElementById('SalaryBankSelect');

if (SBVal.value == "") {
    document.getElementById('BankLbl').style.color = "red";
}

var BBVal = document.getElementById('BankBranch');

if (BBVal.value == "") {
    document.getElementById('BranchLbl').style.color = "red";
}

var PFVal = document.getElementById('ProvidentFundNumber');

if (PFVal.value == "") {
    document.getElementById('PFlbl').style.color = "red";
}
</script>

<script>

var number = document.getElementById('WKS');

// Listen for input event on numInput.
number.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
</script>
<script>
$(function(){
  var addKYEName = $("form#addKYE select[name='docType'] option:eq(1)").val();
	var addKYENameArray = addKYEName.split(" ");
	$("form#addKYE div.btn_div a[href='#myModal']").attr("id",addKYENameArray[0]);
	$("form#addKYE input[name='docId']").attr("id",addKYENameArray[0]+"_doc_id");
  $("form#addKYE select[name='docType'] option:eq(1)").attr("selected","selected");
});



function yesnoCheck(that) {
        if (that.value == "Yes") {
            
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
</script>
<script type="text/javascript">
       $(document).on('click','#addModbtn',function(e) {
		   var data = $("#inputMod").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddMod.php",
         success: function(data){
			AdditionalMod();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalMod() {
          
			var modal = document.getElementById('modal-default-Mod');
            var ddl = document.getElementById("TrainingModule");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputMod").value;
            option.value = document.getElementById("inputMod").value;
            ddl.options.add(option);
			document.getElementById("closeMod").click();
			document.getElementById("inputMod").value="";
        
			     
        }
    </script>


<script type="text/javascript">
       $(document).on('click','#addDeptbtn',function(e) {
		   var data = $("#inputDept").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDept.php",
         success: function(data){
			AdditionalDept();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function AdditionalDept() {
          
			var modal = document.getElementById('modal-default-Dept');
            var ddl = document.getElementById("DeptSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDept").value;
            option.value = document.getElementById("inputDept").value;
            ddl.options.add(option);
			document.getElementById("closeDept").click();
			document.getElementById("inputDept").value="";
        
			     
        }
    </script>
	
	
	
<script type="text/javascript">
       $(document).on('click','#addDocbtn',function(e) {
		   var data = $("#inputDoc").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddDoc.php",
         success: function(data){
			AdditionalDoc();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	<script type="text/javascript">
       function AdditionalDoc() {
          
			var modal = document.getElementById('modal-default-Doc');
            var ddl = document.getElementById("DocSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDoc").value;
            option.value = document.getElementById("inputDoc").value;
            ddl.options.add(option);
			document.getElementById("closeDoc").click();
			document.getElementById("inputDoc").value="";
        
			     
        }
    </script>


	
	





<script type="text/javascript">
function changetextbox()
{
    if (document.getElementById("MandForAll").value === "Yes") {
		document.getElementById("SelByRole").disabled=true;
		document.getElementById("SelByDept").disabled=true;
		document.getElementById("TraineesSel").disabled=true;
	}
	else
	{
		document.getElementById("SelByRole").disabled=false;
		document.getElementById("SelByDept").disabled=false;
		document.getElementById("TraineesSel").disabled=false;
	}
	
}
</script>
<script type="text/javascript">
function changeFreq()
{
    if (document.getElementById("TrainFreq").value === "Once") {
		document.getElementById("TrainFreqOcc").disabled=true;
		
	}
	else
	{
		document.getElementById("TrainFreqOcc").disabled=false;
		
	}
	
}
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })
	 $('#datepicker1').datepicker({
      autoclose: true
    })
	 $('#DOJ').datepicker({
      autoclose: true
    })
	$('#DOA').datepicker({
      autoclose: true
    })
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
			showInputs: false
    })
  })
</script>
<script type="text/javascript">
   $(document).on('click','#submitBtn',function(e) {
     
	ajaxindicatorstart("Please Wait..");
  var data = $("#BoardingForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertFormalities.php",
         success: function(data){
			 ajaxindicatorstop();
			$('#btnEMpl').click();

         }
});

});
    </script>
	<script type="text/javascript">
   $(document).on('click','#SubmitDataBtn',function(e) {
     
	ajaxindicatorstart("Please Wait..");
  var data = $("#DataForm").serialize();
  
  $.ajax({
         data: data,
         type: "post",
         url: "InsertDataSheet.php",
         success: function(data){
			 ajaxindicatorstop();
			$('#btnaeds').click();

         }
});

});
    </script>
	<script type="text/javascript">
       $(document).on('click','#addRolebtn',function(e) {
		   var data = $("#inputRole").serialize();
  //var data = $("#roleForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addRole.php",
         success: function(data){
			//alert(data);
			AdditionalRole();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       $(document).on('click','#SubmitCheckBtn1',function(e) {
		   var data = $("#ChooseForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "RadioEntries.php",
         success: function(data){ 
			ajaxindicatorstop();
			$('#btnpro').click(); 
         }
});
 });
    </script>
	<script type="text/javascript">
   $("#ChooseForm").submit(function(e) {
	  var returnval1 = true;
		ajaxindicatorstart("Please Wait..");
		e.preventDefault();
		var deptval = $("#DeptSelect").val();
		var RepMgmr = $("#RepMgmr").val();
		var BusinessUnitSelect = $("#BusinessUnitSelect").val();
		if(deptval==null)
			{
				alert("Please Choose a Department & Save Employment Data");
				returnval1 = false;
			}
	if(RepMgmr==null || BusinessUnitSelect==null)
	{
		alert("Please Complete Employment Formalities before Proceeding with Provisions.");
		returnval1 = false;
	}
	if(BusinessUnitSelect==null)
	{
		alert("Please Complete AEDS (Office Use) before Proceeding with Provisions.");
		returnval1 = false;
	}
	if(returnval1==false)
		{
			ajaxindicatorstop();
		}
		else
		{
			var data = $("#ChooseForm").serialize();
			$.ajax({
					data: data,
					type: "post",
					url: "RadioEntries.php",
					success: function(data){
							$('#btnpro').click(); 
							ajaxindicatorstop();
								}
				});
		}
 });
</script>
	<script type="text/javascript">
      $(document).ready(function() {
    $("#AdminForm").submit(function(e) {

	ajaxindicatorstart("Please Wait..");
	event.preventDefault();
  var data = $("#AdminForm").serialize();

  $.ajax({
         data: data,
         type: "post",
         url: "AdminEntries.php",
         success: function(data){

			window.location.href = "BoardingHome.php";
		   ajaxindicatorstop();

         }
});

});
});
    </script>
	<script type="text/javascript">
	 $(document).on('click','#Submitformalities',function(e) {
		 unsaved=false;
		 if(unsaved==true)
		 {
			 alert('There are unsaved Changes in the Form. Save before Proceeding.');
			 return false;
		 }
		 else
		 {
			 SubmitFormalitiesForm();
		 }
		  });
	</script>
	
	
	<script type="text/javascript">
      function SubmitFormalitiesForm()
	  {
	   var data = $("#FormalitiesForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "FinishFormalities.php",
         success: function(data){
			window.location.href = "BoardingHome.php";
			 ajaxindicatorstop();
			 
         }
});
 }
    </script>
<script>
function pagereload()
{
			location.reload();
			 ajaxindicatorstop();
}
</script>
	<script type="text/javascript">
       function AdditionalRole() {
          
			var modal = document.getElementById('modal-default-Role');
            var ddl = document.getElementById("RoleSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRole").value;
            option.value = document.getElementById("inputRole").value;
            ddl.options.add(option);
			document.getElementById("closeRole").click();
			document.getElementById("inputRole").value="";
        
			     
        }
    </script>
	
	<script type="text/javascript">
       $(document).on('click','#addBandbtn',function(e) {
		 //  var data = $("#inputBand").serialize();
  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addBand.php",
         success: function(data){
			//alert(data);
			AdditionalBand();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
<script type="text/javascript">
       function AdditionalBand() {
          
			var modal = document.getElementById('modal-default-Band');
            var ddl = document.getElementById("BandSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBandDesc").value;
            option.value = document.getElementById("inputBandDesc").value;
            ddl.options.add(option);
			document.getElementById("closeBand").click();
			document.getElementById("inputBandDesc").value="";
			document.getElementById("inputBand").value="";
        
			     
        }
    </script>
		
	<script type="text/javascript">
       $(document).on('click','#addLvlbtn',function(e) {
		   var data = $("#inputLevel").serialize();
//  var data = $("#BandForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AddLevel.php",
         success: function(data){
			//alert(data);
			Additionallev();
			 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
	<script type="text/javascript">
       function Additionallev() {
          
			var modal = document.getElementById('modal-default-Level');
            var ddl = document.getElementById("LevelSel");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputLevel").value;
            option.value = document.getElementById("inputLevel").value;
            ddl.options.add(option);
			document.getElementById("closeLvl").click();
			document.getElementById("inputLevel").value="";
        
			     
        }
    </script>
	
	
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>