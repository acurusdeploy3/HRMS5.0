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
require_once("top-header.php");
	if(isset($_GET['work_id']) && $_GET['work_id'] != ''){
	$work_id = $_GET['work_id'];}
if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$company_name=mysqli_real_escape_string($db,$_POST['company_name']);
$inputdesig=mysqli_real_escape_string($db,$_POST['inputdesig']);
$reason_for_leaving=mysqli_real_escape_string($db,$_POST['reason_for_leaving']);
$Job_Type=mysqli_real_escape_string($db,$_POST['Job_Type']);
$result = mysqli_query($db,"INSERT INTO employee_work_history(company_name, designation, worked_from,worked_upto, last_ctc,reason_for_leaving, work_duration_months,Job_Type,employee_id,created_date_and_time,created_by,is_active) 
	VALUES
	('".$company_name."',
	'".$inputdesig."',
	'".$_POST["worked_from"]."',
	'".$_POST["worked_upto"]."',	
	'".$_POST["last_ctc"]."',
	'".$reason_for_leaving."',
	'".$_POST["work_duration_months"]."',
	'".$Job_Type."',
	'".$userid."',
	'".$date."',
	'".$userid."',
	'Y'
	)");
	$result1=mysqli_query($db,"update employee_work_history set work_duration_months=TIMESTAMPDIFF(MONTH, worked_from,worked_upto)
	where employee_id=$userid");
 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:experienceform.php");
	}
}
$qualTypesQuery = mysqli_query($db,"SELECT qualification_desc FROM all_qualifications");
	$specTypesQuery = mysqli_query($db,"SELECT specialization_desc FROM all_specializations");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$expQuery = mysqli_query($db,"SELECT * FROM employee_work_history where employee_id = '".$userid."' and is_active = 'Y'");
$descTypesQuery = mysqli_query($db,"SELECT designation_desc FROM all_designations");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

?>
<html>
<head>
 
<script src="jquery-3.2.1.min.js" type="text/javascript"></script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="../images/fevicon.png" type="image/gif" sizes="16x16">
  <title>  
  AHRMS Employee Form: </title> 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script src="allformscript.js"></script>
<script src="countries.js"></script>
<script src="dist/js/loader.js"></script>
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


<style>
.btn-default {
    background-color: #3c8dbc;
border-color : #3c8dbc; }
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
</style>
</head>
 
 <header class="main-header">
    <!-- Logo -->
    <a href="firstform.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span>Acurus HRMS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
         
          
          <!-- Tasks: style can be found in dropdown.less -->
          
		  <?php
include("config.php");
session_start();
$empId=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_designation,Employee_image from employee_details where employee_id=$empId");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userDes = $useridrow['Employee_designation'];
$userImage = $useridrow['Employee_image'];
?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <?php 
					if (($useridrow['Employee_image'])!=null)
					{ echo "<img src='../../uploads/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../../uploads/avatar5.png'  />" ;
					}
					?> 
			  
              <span class="hidden-xs"><?php  echo $empId  ?></span>
              
			 <?php //echo '<a href=# alt=Image Tooltip rel=tooltip content="<div id=imagcon><img src='.$useridrow['Employee_image'].' class=tooltip-image/></div> </a>'?>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <p> <?php 
					if (($useridrow['Employee_image'])!=null)
					{ echo "<img src='../../uploads/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../../uploads/avatar5.png'  />" ;
					}
					?>  </p>
					<p>
                  <?php  echo $usernameval  ?>
                  <small><?php  echo $userDes ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
             
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <!--<img src="../../dist/img/avatar5.png" class="img-circle" alt="User Image">  -->
		    <?php 
					if (($useridrow['Employee_image'])!=null)
					{ echo "<img src='../../uploads/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../../uploads/avatar5.png'  />" ;
					}
					?> 
        </div>
        <div class="pull-left info">
          <p><?php  echo $usernameval  ?></p>
          <a href="#"><?php  echo $userRole  ?></a>
      
        </div>
      </div>
      <!-- search form -->

      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
		
		 <li class="treeview">
          <a href="#">
            <img src="../images/Employee.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Employee Info</span>
           
          </a>
         

        </li>
		
		
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

 

 
 
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       ACURUS EMPLOYEE FORM
      <small> Step 4 </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">EMPLOYMENT DETAILS</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			?>
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" autocomplete="off">
              <div class="box-body">
			  <label>Note<span class="astrick">** </span>  Kindly do not include your current employment</label>
				<div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Name of Company<span class="error">*  </span></label>
				<div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Name of Company" id = "company_name" name="company_name" value="<?=( isset( $_POST['company_name'] ) ? $_POST['company_name'] : '' )?>" required autocomplete="off">
                  </div>				                 
                  <label for="inputDesignation" class="col-sm-2 control-label">Designation <span class="error">*  </span></label>
                  <div class="col-md-3">
                     <select class="form-control" id="inputdesig" name="inputdesig" required>
					 <option value="">Select Designation</option>
                    <?php
					
                    while($row1 = mysqli_fetch_assoc($descTypesQuery)){
                      echo "<option value='".$row1['designation_desc']."'>".$row1['designation_desc']."</option>";
                    }
                    ?>                     
                    </select>
                  </div>
				  <div class = "col-sm-1">
				  <a href="#" id="myBtn1" title="Click to Add another Education" data-toggle="modal" data-target="#modal-default-Education"><i class="fa fa-fw fa-plus"></i></a>	
				  </div>
				  <div id="myModal1" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close12">&times;</span>
						<p>Add New Designation:</p>
								<input type="text"  class="form-control" id="inputDesignation" name="inputDesignation" placeholder="Enter Designation" /><br>
					<input type="button" id="addDesignationbtn"  name="AdddescBtn"  class = "btn btn-primary"value = "Add Designation"></input>
						</div>
			</div> 
				  </div>
					<!--<div class="form-group">
                 			                 
                  <label for="inputDesignation" class="col-sm-2 control-label">Worked From <span class="error">*  </span></label>
                  <div class="col-sm-4">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" placeholder="yyyy-mm-dd"  id="WorkFrom" name="worked_from" value="<?//=( isset( $_POST['worked_from'] ) ? $_POST['worked_from'] : '' )?>" required>
                    </div>
                  </div>
				  <label for="inputDesignation" class="col-sm-2 control-label">Worked Upto <span class="error">*  </span></label>
                  <div class="col-sm-4">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" placeholder="yyyy-mm-dd" id="WorkTo" name="worked_upto" value="<?//=( isset( $_POST['worked_upto'] ) ? $_POST['worked_upto'] : '' )?>" required >
                    </div>
                  </div>	
				  </div> -->
				 <div class="form-group">
                 			                 
                  <label for="inputDesignation" class="col-sm-2 control-label">Worked From <span class="error">*  </span></label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder="yyyy-mm-dd"  id="StartDate" name="worked_from" value="<?=( isset( $_POST['worked_from'] ) ? $_POST['worked_from'] : '' )?>" required autocomplete="anyrandomstring" data-date-end-date="0d">
                  </div>
				  <label for="inputDesignation" class="col-sm-2 control-label">Worked Upto <span class="error">*  </span></label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder="yyyy-mm-dd" id="EndDate" name="worked_upto" value="<?=( isset( $_POST['worked_upto'] ) ? $_POST['worked_upto'] : '' )?>" required autocomplete="anyrandomstring" data-date-end-date="0d">
                    </div>
				  </div>
                
						<div class="form-group">
                  			  
                  <label for="inputPreviousCTC" class="col-sm-2 control-label">CTC - Per Month(INR)</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Previous CTC in INR" id="previousctc" name="last_ctc" value="<?=( isset( $_POST['last_ctc'] ) ? $_POST['last_ctc'] : '' )?>" autocomplete="off">
                  </div>
				  <label for="inputLeavingReason" class="col-sm-2 control-label">Reason for leaving</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Reason for leaving" name="reason_for_leaving" value="<?=( isset( $_POST['reason_for_leaving'] ) ? $_POST['reason_for_leaving'] : '' )?>" autocomplete="off">
                  </div>
                  </div>
				
				  <div class="form-group">
                  <label for="inputLeavingReason" class="col-sm-2 control-label">Type of Job </label>
						<div class="col-sm-4">
                   <select class="form-control" name="Job_Type">
                      <option value="">Select Job Type</option>
                      <option value="Full time">Full time</option>
                      <option value="Part time">Part time</option>
                      <option value="Contract">Contract</option>                                        
                      <option value="Consultant">Consultant</option>                                        
                    </select>
                  </div>
				  </div>
			
				</div>
			
			 <div class="box-footer">
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='educationform.php';" type="button" value="Previous" id ="goprevious"/>                
				<input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px; border-color:#da3047;" id="clearfields">
				<a href="certificationform.php"><button type= "button" name= "submit" class="btn btn-info pull-right" id="gonext"> Next</button></a>
				
				<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
				
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name of Company</th>
					<th>Designation</th>          
					<th>Worked From</th>
					<th>Worked Upto</th>
					<th>Previous CTC(INR)</th>
					<th>Reason for Leaving</th>
					<th>Experience(in months)</th>									
					<th>Type of job</th>									
					<th>Actions</th>
                </tr>
                <?php
                if(mysqli_num_rows($expQuery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($expQuery)){
                    echo "<tr><td>".$i.".</td>";
                    echo "<td>".$row['company_name']."</td>";
                    echo "<td>".$row['designation']."</td>";
                    echo "<td>".$row['worked_from']."</td>";
                    echo "<td>".$row['worked_upto']."</td>";
                    echo "<td>".$row['last_ctc']."</td>";
                    echo "<td>".$row['reason_for_leaving']."</td>";
                    echo "<td>".$row['work_duration_months']."</td>";
                    echo "<td>".$row['Job_Type']."</td>";
                 
                    echo "<td><a href='deleteexp.php?work_id=".$row['work_id']."'><i class='fa fa-trash'></i></a></td>";
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
      <!-- /.row -->
    </section>		  
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div>
 
  <script>
  $(function() {
  $("#StartDate,#EndDate").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});

	</script>
		<SCRIPT>
	$("#gonext").click(function() {
    if(document.getElementById('StartDate').value!='' || document.getElementById('EndDate').value!=''|| document.getElementById('company_name').value!='' || document.getElementById('inputdesig').value!=''){
        alert("There are unsaved Changes in the Form. Save before Proceeding.");
		return false;
    } else {
       
    }
});
	</SCRIPT>
	<script>
	$("#savefields").click(function() {
		ajaxindicatorstart("Processing..Please Wait..");
			if(document.getElementById('StartDate').value=='' || document.getElementById('EndDate').value==''|| document.getElementById('company_name').value=='' || document.getElementById('inputdesig').value=='')
			{
				alert("Please Fill in all the Fields");
				ajaxindicatorstop();
				return false;
			}
			
			else
			{
				var startDate = document.getElementById("StartDate").value;
					var endDate = document.getElementById("EndDate").value;
					if(startDate > endDate)
						{
							alert("To Date should be greater than From Date");
							ajaxindicatorstop();
							return false;
		
						}
						else
						{
							return true;
						}
			}
				
			
});
	</script>
  <script type="text/javascript">
       $(document).on('click','#addDesignationbtn',function(e) {
		   var data = $("#inputDesignation").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingnewdesignation.php",
         success: function(data){
			AddingDesignation();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
 <!-- JS-->
<script type="text/javascript">
       function AddingDesignation() {
	
			var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("inputdesig");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputDesignation").value;
            option.value = document.getElementById("inputDesignation").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputDesignation").value="";
			// document.getElementById("AdditionalSourceText").value="";
        
			     
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
 <script>
 
$("#WorkTo").change(function () {
    var workFrom = document.getElementById("WorkFrom").value;
    var workTo = document.getElementById("WorkTo").value;
 
    if ((Date.parse(workTo) <= Date.parse(workFrom))) {
        alert("End date should be greater than Start date");
        document.getElementById("workTo").value = "";
    }
});
	</script>

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Slimscroll -->
<script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script src="customJs.js"></script>
</body>
</html>

 