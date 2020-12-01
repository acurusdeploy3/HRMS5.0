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
	if(isset($_GET['family_id']) && $_GET['family_id'] != ''){
	$family_id = $_GET['family_id'];}
if(isset($_POST['Submit'])){
	$dob=(isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != '')?$_POST['date_of_birth']:'0001-01-01';
$date = date("Y-m-d h:i:s");
$family_member_name=mysqli_real_escape_string($db,$_POST['family_member_name']);
$relationship_with_employee=mysqli_real_escape_string($db,$_POST['relationship_with_employee']);
$qualification=mysqli_real_escape_string($db,$_POST['qualification']);
$occupation=mysqli_real_escape_string($db,$_POST['occupation']);
$status=mysqli_real_escape_string($db,$_POST['status']);
$query1="INSERT INTO employee_family_particulars(family_member_name,date_of_birth, relationship_with_employee,qualification, occupation, marital_status, created_date_and_time,country_code,contact_number,status, employee_id,created_by,is_active) 
	VALUES
	('".$family_member_name."',
	'".$dob."',
	'".$relationship_with_employee."',
	'".$qualification."',
	'".$occupation."',
	'".$_POST["marital_status"]."',
	'".$date."',
	'".$_POST["country_code"]."',
	'".$_POST["contact_number"]."',
	'".$status."',	
	'".$userid."',
	'".$userid."',
	'Y'
	)";
$result = mysqli_query($db,$query1);
 if(!$result){
			$message="Problem in Adding to database. Please Retry";
			
	} else {
		//$message=$query1;
		header("Location:familyform.php");
	}
}
$relationQuery = mysqli_query($db,"SELECT relation FROM all_relations");
	$qualTypesQuery = mysqli_query($db,"SELECT qualification_desc FROM all_qualifications");
	
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$famQuery = mysqli_query($db,"SELECT * FROM employee_family_particulars where employee_id = '".$userid."' and is_active = 'Y'");

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
.col-lg-1 {
    width: 9.7%;	
}
.col-sm-3 {
    width: 23.5%;
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
th {
  background-color: #31607c;
  color:white;
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
#finishbtn{
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
      <small> Step 6 </small>
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
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">FAMILY DETAILS</h3>
			 
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			?>
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" autocomplete="off" >
              <div class="box-body">
			  <label>Note<span class="astrick">** </span>  Kindly do not include yourself in the family details</label><br>
				<div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Name<span class="error">*  </span> </label>
				<div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Full Name" id="name" name="family_member_name" value="<?=( isset( $_POST['family_member_name'] ) ? $_POST['family_member_name'] : '' )?>" autocomplete="off" required>
                  </div>				  
                
                  <label for="inputDOB" class="col-sm-2 control-label"  >D.O.B</label>	
					<div class="col-sm-4">
					<input type="text" class="form-control pull-right" placeholder = "yyyy-mm-dd" name="date_of_birth" id="datepicker" value="<?=( isset( $_POST['date_of_birth'] ) ? $_POST['date_of_birth'] : '' )?>"  autocomplete="anyrandomstring" data-date-end-date="0d">
                  </div>
			</div>			
					 <div class="form-group">
                  <label for="inputRelation" id="inputrelemployee" class="col-sm-2 control-label">Relation<span class="error">*  </span></label>
                <div class="col-md-3">
                     <select class="form-control" id="relationwith" name="relationship_with_employee" required >
                      <option value="">Select Relation</option>
                    <?php
					
                    while($row = mysqli_fetch_assoc($relationQuery)){
                      echo "<option value='".$row['relation']."'>".$row['relation']."</option>";
                    }
                    ?>        
                                         
                    </select>
                  </div>
				  <div class = "col-sm-1">
				  <a href="#" id="myBtn" title="Click to Add another Relation" data-toggle="modal" data-target="#modal-default-Relation"><i class="fa fa-fw fa-plus"></i></a>	
				  </div>
				  
				  <div id="myModal" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p>Add New Relation:</p>
								<input type="text"  class="form-control" id="inputRelation" name="inputRelation" placeholder="Enter Relation" /><br>
					<input type="button" id="addRelationbtn"  name="AddRelBtn" class = "btn btn-primary" value = "Add Relation" ></input>
						</div>
			</div>  
                  <label for="inputQualification" id="Qualificationinput"class="col-sm-2 control-label">Qualification</label>
                  <div class="col-md-3">
                     <select class="form-control" id="inputqual" name="qualification">
					 <option value="">Select Education Type</option>
                    <?php
					
                    while($row = mysqli_fetch_assoc($qualTypesQuery)){
                      echo "<option value='".$row['qualification_desc']."'>".$row['qualification_desc']."</option>";
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
						<p>Add New Qualification:</p>
								<input type="text"  class="form-control" id="inputEducation" name="inputEducation" placeholder="Enter Qualification" /><br>
					<input type="button" id="addEducationbtn"  name="AddEduBtn"  class = "btn btn-primary"value = "Add Qualification"></input>
						</div>
			</div>  
				  </div>
					<div class="form-group">
                  <label for="inputOccupation" class="col-sm-2 control-label">Occupation</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Occupation" name="occupation" value="<?=( isset( $_POST['occupation'] ) ? $_POST['occupation'] : '' )?>">
                  </div>
                  
                  <label for="inputMaritalStatus" class="col-sm-2 control-label">Marital Status</label>

                  <div class="col-sm-4">
                     <select class="form-control" name="marital_status">
                      <option value="">Select Marital Status</option>
                      <option value="Unmarried">Unmarried</option>
                      <option value="Married">Married</option>
                      <option value="Prefer not to disclose">Prefer not to disclose</option>
                                        
                    </select>
                  </div>
                  </div>
				
				  <div class="form-group">
                 
                  
				   <label for="inputMobileNo" class="col-sm-2 control-label">Contact Number 
				  </label>
				  <div class = "col-lg-1">
				  <select id='country_code' name= "country_code" class = "form-control">
					<option value="+91"> +91</option>
					<option value="+1"> +1</option>
				  </select>
				  </div>
                  <div class="col-sm-3">
                  
                    <input type="text" class="form-control"  placeholder="Contact number" id="contact_number" name="contact_number" value="<?=( isset( $_POST['contact_number'] ) ? $_POST['contact_number'] : '' )?>" maxlength = "10" autocomplete="off">
                  
                  </div>
				  
				  
				  
				  
				  <label for="inputContactNo" class="col-sm-2 control-label">Status</label>

                  <div class="col-sm-4">
                    <select class="form-control" name="status">
                     <option value="">Select Status</option>
                      <option value="Alive">Alive</option>
                      <option value="Deceased">Deceased</option>
                                                            
                    </select>
                  </div>
                  </div>
             
				
              
				
				</div>
			
			  <div class="box-footer">
                <input action="action" class="btn btn-info pull-left" type="button" value="Previous" onclick="window.location='certificationform.php';"id="goprevious" />  
                <input type="reset" name="reset" class="btn btn-info pull-left" value="Clear" style = "margin-left: 7px; background-color: #da3047;border-color:#da3047;" id="clearfields">
				 
				<?php
					$getParents = mysqli_query($db,"SELECT * FROM `employee_family_particulars` where relationship_with_employee in ('Father','Mother') and employee_id=$userid and is_active='Y';");
					if(mysqli_num_rows($getParents)>=2)
					{
				?>				
				<a href="miscform.php"><button type= "button" name= "submit" id= "finishbtn" class="btn btn-info pull-right" id="gonext" > Next </button> </a>
				
				<?php
					}
					else
					{
				?>
				<a href="#"><button type= "button" name= "submit" ONCLICK="alert('Your Father & Mother Data is Mandatory')" id= "finishbtn" class="btn btn-info pull-right" id="gonext" > Next </button> </a>
				<?php
					}
				?>
				<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
              </div>
              <!-- /.box-footer -->			  		  
          
		        	<!-- Modal for education -->
			 
		  
		  <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
					<th>Date of Birth</th>
					<th>Relation</th>          
					<th>Qualification</th>
					<th>Occupation</th>
					<th>Marital Status</th>
					<th>Contact Number</th>
					<th>Status</th>
					
					<th>Actions</th>
					
                </tr>
                <?php
                if(mysqli_num_rows($famQuery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($famQuery)){
                    echo "<tr><td>".$i.".</td>";
                    echo "<td>".$row['family_member_name']."</td>";
					if($row['date_of_birth'] =='0001-01-01')
					{ echo "<td></td>";
					}
					else{
					echo "<td>".$row['date_of_birth']."</td>";}
                    echo "<td>".$row['relationship_with_employee']."</td>";
                    echo "<td>".$row['qualification']."</td>";
                    echo "<td>".$row['occupation']."</td>";
                    echo "<td>".$row['marital_status']."</td>";
                    echo "<td>".$row['contact_number']."</td>";
                    echo "<td>".$row['status']."</td>";
                   
                 echo  "<td><a href='deletefam.php?family_id=".$row['family_id']."'><i class='fa fa-trash'></i></a></td>";
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
            $( "#datepicker" ).datepicker({
               defaultDate: +9,
			   autoclose: true,
			   maxDate:0
            });
         });
      </script>
	<script>
	$("#savefields").click(function() {
			if(document.getElementById('name').value=='' || document.getElementById('datepicker').value=='' || document.getElementById('relationwith').value==''){
			}else{ajaxindicatorstart("Processing..Please Wait..");}
});

	</script>
	<SCRIPT>
	$("#finishbtn").click(function() {
    if(document.getElementById("name").value != '' || document.getElementById("relationwith").value != '' ){
        alert("There are unsaved Changes in the Form. Save before Proceeding.");
		return false;
    } else {
       
    }
});
	</SCRIPT>
	<script>
   //  $( function() {
//$('input[id$=datepicker]').datepicker({
	//		var today = new Date();
	//		dateFormat: 'yyyy-mm-dd',
		//	endDate: "today",
          //  maxDate: today,
		//	autoclose: true
		//});
		
//    });
</script>
 <script type="text/javascript">
       $(document).on('click','#addEducationbtn',function(e) {
		   var data = $("#inputEducation").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingneweducation.php",
         success: function(data){
			AddingEducation();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
 <!-- JS-->
<script type="text/javascript">
       function AddingEducation() {
	
			var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("inputqual");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputEducation").value;
            option.value = document.getElementById("inputEducation").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputEducation").value="";
			// document.getElementById("AdditionalSourceText").value="";
        
			     
        }
    </script>	
 <!-- JS-->
 <script type="text/javascript">
       $(document).on('click','#addRelationbtn',function(e) {
		   var data = $("#inputRelation").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingrelation.php",
         success: function(data){
			AddingRelation();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
<script type="text/javascript">
       function AddingRelation() {
			var modal = document.getElementById('myModal');
            var ddl = document.getElementById("relationwith");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputRelation").value;
            option.value = document.getElementById("inputRelation").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			 document.getElementById("inputRelation").value="";
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

 