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
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];
if(isset($_POST['Submit'])){
 $query90 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Option_value%' and resignation_id=$resignation_id");
 while($row11 = mysqli_fetch_assoc($query90))
 {
	 $PostVal = $row11['value_data'];
	 //$comm=$row11['value'].'Comments';
	// $deptname= $row11['department'];
	 $update1="update exitinterviewformenteries set comments='".$_POST[$PostVal]."',status='Y' where value_data = '$PostVal' and resignation_id=$resignation_id ";
	$updatequery = mysqli_query($db,$update1);
 }
 $query91 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Text_value%' and resignation_id=$resignation_id");
 while($row12 = mysqli_fetch_assoc($query91))
 {
	 $PostVal1 = $row12['value_data'];
	 //$comm=$row11['value'].'Comments';
	// $deptname= $row11['department'];
	 $update2="update exitinterviewformenteries set comments='".mysqli_real_escape_string($db,$_POST[$PostVal1])."',status='Y' where value_data = '$PostVal1' and resignation_id=$resignation_id ";
	$updatequery1 = mysqli_query($db,$update2);
 }
 $commentsupdate= mysqli_query($db,"update exitinterviewformenteries set comments='".mysqli_real_escape_string($db,$_POST['add_comments'])."',status='Y' where value_data = 'Text_Comments1' and resignation_id=$resignation_id");
 $query92 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Drop_Value%' and resignation_id=$resignation_id");
 while($row13 = mysqli_fetch_assoc($query92))
 { 
 $PostVal3 = $row13['value'];
 $update3 = "update exitinterviewformenteries set comments='".mysqli_real_escape_string($db,$_POST[$PostVal3])."',status='Y' where value = '$PostVal3' and resignation_id=$resignation_id";
 $commentsupdate2 = mysqli_query($db,$update3);
 }
 $update11 = "update employee_resignation_information set exit_interview_status='C',modified_by=$userid where resignation_id=$resignation_id";
 $commentsupdate11 = mysqli_query($db,$update11);
 echo "<script>alert('Thank you for completing the Exit Interview Form');
 window.location='employeeresignationform.php';</script>";
}
$query1=mysqli_query($db,"select  resignation_id,concat(First_name,' ',MI,' ',Last_Name) as EmpName,d.employee_id,
date_format(Date_Joined,'%d %b %Y') as Date_Joined,date_format(date(date_of_leaving),'%d %b %Y') as dl,
Employee_Designation,Department,r.process_queue from employee_resignation_information r 
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and (r.process_queue='HR_Manager_Process' or r.process_queue='No_Due_Completed') and resignation_id=$resignation_id");
$detRow=mysqli_fetch_array($query1);
$reasonsdropdown =  mysqli_query($db,"SELECT resignation_reason FROM `all_reasons` where category='exit interview'");
$reasonsdropdown1 =  mysqli_query($db,"SELECT resignation_reason FROM `all_reasons` where category='exit interview'");
$reasonsdropdown2 =  mysqli_query($db,"SELECT resignation_reason FROM `all_reasons` where category='exit interview'");
$formenteries = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Option_value%' and resignation_id=$resignation_id");
$formenteries1 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Text_value%' and resignation_id=$resignation_id");
$formenteries2 = mysqli_query($db,"SELECT * FROM `exitinterviewformenteries` where value_data like 'Text_Comments%' and resignation_id=$resignation_id");
$detRow1 = mysqli_fetch_array($formenteries2);
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
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  width:5%;
  height:30px;
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
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='employeeresignationform.php';" type="button" value="Back" id="goprevious"/>                
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
           <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
              <div class="box-body">
				<div class="form-group">
				<label class="col-sm-2 control-label" for="exitinterview">Name:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="EmpName" name="EmpName" value="<?php echo $detRow['EmpName']; ?>"  required disabled	 >
					</input>
					</div>
					<label class="col-sm-2 control-label" for="exitinterview">Date Of Designation:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="Date_Joined" name="Date_Joined" value="<?php echo $detRow['Date_Joined']; ?>"  required disabled	 >
					</input>
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label" for="exitinterview">Employee ID:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="employee_id" name="employee_id" value="<?php echo $detRow['employee_id']; ?>"  required disabled	 >
					</input>
					</div>
					<label class="col-sm-2 control-label" for="exitinterview">Resignation letter dated:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="dl" name="dl" value="<?php echo $detRow['dl']; ?>"  required disabled	 >
					</input>
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label" for="exitinterview">Employee Designation:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="Employee_Designation" name="Employee_Designation" value="<?php echo $detRow['Employee_Designation']; ?>"  required disabled	 >
					</input>
					</div>
					<label class="col-sm-2 control-label" for="exitinterview">Date last worked:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="dl" name="dl" value="<?php echo $detRow['dl']; ?>"  required disabled	 >
					</input>
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label" for="exitinterview">Project / Department:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="Department" name="Department" value="<?php echo $detRow['Department']; ?>"  required disabled	 >
					</input>
					</div>
					<label class="col-sm-2 control-label" for="exitinterview">Date relieved:</label>
				<div class="col-sm-4">
                    <input type="text"  class="form-control" id="dl" name="dl" value="<?php echo $detRow['dl']; ?>"  required disabled	 >
					</input>
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-4 control-label" for="exitinterview">Rank your top 3 reason for leaving the organization <span class="error">*  </span></label></div>
				<div class="form-group">
				<div class="col-sm-4">
                     <select class="form-control" name="exit_reason_1" id="exit_reason_1" required>
					 <option value ="" selected></option>
                    <?php
                    while($row5 = mysqli_fetch_assoc($reasonsdropdown))
					{
					?>
			<option value="<?php echo $row5['resignation_reason']; ?>"><?php echo $row5['resignation_reason']; ?></option>
					<?php
                    }
                    ?>                                                      
                    </select>
					<a href="#" id="myBtn" title="Click to Add another option" data-toggle="modal" data-target="#modal-default-Option"><i class="fa fa-fw fa-plus"></i></a></div><div class="col-sm-4">
					 <select class="form-control" name="exit_reason_2" id="exit_reason_2" required>
					 <option value ="" selected></option>
                    <?php
                    while($row6 = mysqli_fetch_assoc($reasonsdropdown1))
					{
					?>
			<option value="<?php echo $row6['resignation_reason']; ?>"><?php echo $row6['resignation_reason']; ?></option>
					<?php
                    }
                    ?>                                                      
                    </select>
					<a href="#" id="myBtn1" title="Click to Add another option" data-toggle="modal" data-target="#modal-default-Option2"><i class="fa fa-fw fa-plus"></i></a></div><div class="col-sm-4">
					 <select class="form-control" name="exit_reason_3" id="exit_reason_3" required>
					 <option value ="" selected></option>
                    <?php
                    while($row7 = mysqli_fetch_assoc($reasonsdropdown2))
					{
					?>	
			<option value="<?php echo $row7['resignation_reason']; ?>"><?php echo $row7['resignation_reason']; ?></option>
					<?php
                    }
                    ?>                                                      
                    </select>
					<a href="#" id="myBtn2" title="Click to Add another option" data-toggle="modal" data-target="#modal-default-Option1"><i class="fa fa-fw fa-plus"></i></a></div>
					</div>
					<div class="form-group">
				<label class="col-sm-8 control-label" style="text-align: left;" for="exitinterview">Please complete the following</label></div>
				
				<?php 
				while($row72 = mysqli_fetch_assoc($formenteries))
				{
					echo "<div class='form-group'>";
					echo "<label for='inputforform' class='col-sm-5 control-label'>".$row72['value']."<span class='error'>*  </span></label>";
					if($row72['comments']=='Strongly Agree')
				{
				echo "<div class='col-sm-3'><select class='form-control' id=".$row72['value_data']." name=".$row72['value_data']." required>
				<option value=".$row72['comments'].">".$row72['comments']."</option>
				<option value='Agree'>Agree</option>
				<option value='Disagree'>Disagree</option>
				<option value='Strongly Disagree'>Strongly Disagree</option></select></div>";
				}
				else if($row72['comments']=='Agree')
				{
				echo "<div class='col-sm-3'><select class='form-control' id=".$row72['value_data']." name=".$row72['value_data']." required>
				<option value=".$row72['comments'].">".$row72['comments']."</option>
				<option value='Strongly Agree'>Strongly Agree</option>
				<option value='Disagree'>Disagree</option>
				<option value='Strongly Disagree'>Strongly Disagree</option></select></div>";
				}
				else if($row72['comments']=='Disagree')
				{
				echo "<div class='col-sm-3'><select class='form-control' id=".$row72['value_data']." name=".$row72['value_data']." required>
				<option value=".$row72['comments'].">".$row72['comments']."</option>
				<option value='Strongly Agree'>Strongly Agree</option>
				<option value='Agree'>Agree</option>
				<option value='Strongly Disagree'>Strongly Disagree</option></select></div>";
				}
				else if($row72['comments']=='Strongly Disagree')
				{
				echo "<div class='col-sm-3'><select class='form-control' id=".$row72['value_data']." name=".$row72['value_data']." required>
				<option value=".$row72['comments'].">".$row72['comments']."</option>
				<option value='Strongly Agree'>Strongly Agree</option>
				<option value='Agree'>Agree</option>
				<option value='Disagree'>Disagree</option></select></div>";
				}
				else{
				echo "<div class='col-sm-3'><select class='form-control' id=".$row72['value_data']." name=".$row72['value_data']." required>
				<option value='Strongly Agree'>Strongly Agree</option>
				<option value='Agree'>Agree</option>
				<option value='Disagree'>Disagree</option>	
				<option value='Strongly Disagree'>Strongly Disagree</option></select></div>";
				}
				echo "</div>";
				}
					?>
					<?php 
				while($row74 = mysqli_fetch_assoc($formenteries1))
				{
					echo "<div class='form-group'>";
					echo "<label for='inputforform' class='col-sm-5 control-label'>".$row74['value']."<span class='error'>*  </span></label>";
				echo "<div class='col-sm-7'><input type='text' class='form-control' id=".$row74['value_data']." name=".$row74['value_data']." pattern='^[a-zA-Z0-9][\sa-zA-Z0-9\.\-\?\,]*' value='".$row74['comments']."' required></input></div>";
				echo "</div>";
				}
					?>
				<div class="form-group">
				<label class="col-sm-9 control-label" style="text-align: left;" for="exitinterview">Your opinion is very important to us. Additional comments and suggestions are encouraged.</label><br>
				<div class='col-sm-10'>
				<input type='text' class='form-control' name="add_comments" id="add_comments"value="<?php echo $detRow1['comments']; ?>"></input>
				</div></div>
					<div id="myModal" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p>Add New Option:</p>
								<input type="text"  class="form-control" id="inputOption" name="inputOption" placeholder="Enter Your Option" /><br>
					<input id="addOptionbtn"  name="AddOptBtn" type="button" class = "btn btn-primary"value = "Add Option" onclick= "AddingOption();" />
						</div>
			</div> 
			<div id="myModal1" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close2">&times;</span>
						<p>Add New Option:</p>
								<input type="text"  class="form-control" id="inputOption1" name="inputOption1" placeholder="Enter Your Option" /><br>
					<input id="addOptionbtn1"  name="AddOptBtn1" type="button" class = "btn btn-primary"value = "Add Option" onclick= "AddingOptionreason();" />
						</div>
			</div> 	
		<div id="myModal2" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close3">&times;</span>
						<p>Add New Option:</p>
								<input type="text"  class="form-control" id="inputOption2" name="inputOption2" placeholder="Enter Your Option" /><br>
					<input id="addOptionbtn2"  name="AddOptBtn2" type="button" class = "btn btn-primary"value = "Add Option" onclick= "AddingOptionrsn();" />
						</div>
			</div> 				
				</div>
			  </div>
			  
			  <div class="box-footer">   
				<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields" />			   
              </div>
			  
			  </form>
          </div>	  
          </div>
          <!-- /.box -->
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
  $(function() {
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3,#currentdate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});


	$('form').submit(function(e) {
  if (!confirm('Are you sure you want to submit the form?'))
  {
    e.preventDefault();
  }
else
{
 ajaxindicatorstart("Processing..Please Wait..");
}
});

</script>
<script type="text/javascript">
       function AddingOption() {
			var modal = document.getElementById('myModal');
            var ddl = document.getElementById("exit_reason_1");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputOption").value;
            option.value = document.getElementById("inputOption").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputOption").value="";
			// document.getElementById("AdditionalSourceText").value="";
			     
        }
    </script>
	<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

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
<script type="text/javascript">
       function AddingOptionreason() {
			var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("exit_reason_2");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputOption1").value;
            option.value = document.getElementById("inputOption1").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputOption1").value="";
			// document.getElementById("AdditionalSourceText").value="";
			     
        }
    </script>
	<script>
// Get the modal
var modal1 = document.getElementById('myModal1');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close2")[0];

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
<script type="text/javascript">
       function AddingOptionrsn() {
			var modal = document.getElementById('myModal2');
            var ddl = document.getElementById("exit_reason_3");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputOption2").value;
            option.value = document.getElementById("inputOption2").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputOption2").value="";
			// document.getElementById("AdditionalSourceText").value="";
			     
        }
    </script>
	<script>
// Get the modal
var modal2 = document.getElementById('myModal2');

// Get the button that opens the modal
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close3")[0];

// When the user clicks the button, open the modal 
btn2.onclick = function() {
    modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
}
</script>
</body>
</html>
