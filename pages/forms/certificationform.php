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
require_once("top-header.php");
	if(isset($_GET['certifications_id']) && $_GET['certifications_id'] != ''){
	$certifications_id = $_GET['certifications_id'];}
if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$course_name=mysqli_real_escape_string($db,$_POST['course_name']);
$description=mysqli_real_escape_string($db,$_POST['description']);
$certification_name=mysqli_real_escape_string($db,$_POST['certification_name']);
$course_offered_by=mysqli_real_escape_string($db,$_POST['course_offered_by']);
$expdate=$_POST["expiry_date"];
$issdate=$_POST["issued_date"];
if($expdate=='' && $issdate==''){
$result = mysqli_query($db,"INSERT INTO employee_certifications(course_name, description, course_offered_by,certification_name,employee_id,created_by,created_date_and_time,is_Active) 
	VALUES
	('$course_name',
	'$description',
	'$course_offered_by',
	'$certification_name',	
	'$userid',
	'$userid',
	'$date',
	'Y'
	)");
	 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:certificationform.php");
	}
}
else if( $expdate !='' && $issdate =='')
{
	$test="INSERT INTO employee_certifications(course_name, description, course_offered_by,certification_name,expiry_date,employee_id,created_by,created_date_and_time,is_Active) 
	VALUES
	('".$_POST["course_name"]."',
	'".$_POST["description"]."',
	'".$_POST["course_offered_by"]."',
	'".$_POST["certification_name"]."',	
	'".$_POST["expiry_date"]."',
	'".$userid."',
	'".$userid."',
	'".$date."',
	'Y'
	)";
	$result = mysqli_query($db,$test);
	 if(!$result){
			//$message=$test;
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:certificationform.php");
	}
}
else if( $expdate =='' && $issdate !='')
{
	$result = mysqli_query($db,"INSERT INTO employee_certifications(course_name, description, course_offered_by,certification_name,issued_date,employee_id,created_by,created_date_and_time,is_Active) 
	VALUES
	('".$_POST["course_name"]."',
	'".$_POST["description"]."',
	'".$_POST["course_offered_by"]."',
	'".$_POST["certification_name"]."',	
	'".$_POST["issued_date"]."',
	'".$userid."',
	'".$userid."',
	'".$date."',
	'Y'
	)");
	 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:certificationform.php");
	}
}
else
{
	$result = mysqli_query($db,"INSERT INTO employee_certifications(course_name, description, course_offered_by,certification_name,expiry_date,issued_date,employee_id,created_by,created_date_and_time,is_Active) 
	VALUES
	('".$_POST["course_name"]."',
	'".$_POST["description"]."',
	'".$_POST["course_offered_by"]."',
	'".$_POST["certification_name"]."',	
	'".$_POST["expiry_date"]."',
	'".$_POST["issued_date"]."',
	'".$userid."',
	'".$userid."',
	'".$date."',
	'Y'
	)");
	 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:certificationform.php");
	}
}
}
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$certQuery = mysqli_query($db,"SELECT * FROM employee_certifications where employee_id = '".$userid."' and is_Active = 'Y' ");

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
					{ echo "<img src='../images/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../images/avatar5.png'  />" ;
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
					{ echo "<img src='../images/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../images/avatar5.png'  />" ;
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
					{ echo "<img src='../images/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../images/avatar5.png'  />" ;
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
      <small> Step 5 </small>
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
              <h3 class="box-title">CERTIFICATION DETAILS</h3>
			  <small> If any </small>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
            <form class="form-horizontal" method="post" action="" id="certform" enctype="multipart/form-data" autocomplete="off">
              <div class="box-body">
				<div class="form-group">
                  <label for="inputCourseName" class="col-sm-2 control-label">Course Name<span class="error">*  </span></label>
				<div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Course Name" name="course_name" id="course_name" value="<?=( isset( $_POST['course_name'] ) ? $_POST['course_name'] : '' )?>" required autocomplete="off">
                  </div>				  
                 <label for="inputIssuedDate" class="col-sm-2 control-label">Issued Date</label>

                  <div class="col-sm-4">
				  <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    <input type="text" class="form-control"  placeholder="yyyy-mm-dd" name="issued_date" id="datepicker" value="<?=( isset( $_POST['issued_date'] ) ? $_POST['issued_date'] : '' )?>"  autocomplete="anyrandomstring" data-date-end-date="0d">
                  </div>
				  </div>
                  
				  </div>
					 <div class="form-group">
					 <label for="inputDuration" class="col-sm-2 control-label">Expiry Date</label>
                 
					<div class="col-sm-4">
				  <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    <input type="text" class="form-control"  placeholder="yyyy-mm-dd" name="expiry_date" id="datepicker1" value="<?=( isset( $_POST['expiry_date'] ) ? $_POST['expiry_date'] : '' )?>"   autocomplete="anyrandomstring"></input>
                  </div>
				  </div>				  
                  <label for="inputCertificationName" class="col-sm-2 control-label">Certification Name</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Certification Name" name="certification_name" value="<?=( isset( $_POST['certification_name'] ) ? $_POST['certification_name'] : '' )?>" autocomplete="off" ></input>
                  </div>
                  </div>
                
					<div class="form-group">
                 <label for="inputCourseOfferedBy" class="col-sm-2 control-label">Course offered by </label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="Course offered by" name="course_offered_by" value="<?=( isset( $_POST['course_offered_by'] ) ? $_POST['course_offered_by'] : '' )?>" autocomplete="off"> 
                  </div>
                 <label for="inputDescription" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Description" name="description" value="<?=( isset( $_POST['description'] ) ? $_POST['description'] : '' )?>" autocomplete="off">
                  </div>
                  
                  
                  </div>	
				
				
				</div>
			
			 <div class="box-footer">
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='experienceform.php';" type="button" value="Previous" id="goprevious"/>                
				<input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields">
				<a href="familyform.php"><button type= "button" name= "submit" class="btn btn-info pull-right" id="gonext"> Next</button></a>
				
				<input type= "submit" name= "Submit" class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" id="savefields">
				
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Course Name</th>
					<th>Description</th>          
					<th>Course offered by</th>
					<th>Certification Name</th>				
					<th>Issued Date</th>			
					<th>Expiry Date</th>			
					<th>Actions</th>
					
                </tr>
                <?php
                if(mysqli_num_rows($certQuery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($certQuery)){
                    echo "<tr><td>".$i.".</td>";
                    echo "<td>".$row['course_name']."</td>";
                    echo "<td>".$row['description']."</td>";
                    echo "<td>".$row['course_offered_by']."</td>";
                    echo "<td>".$row['certification_name']."</td>";
                    echo "<td>".$row['issued_date']."</td>";
                    echo "<td>".$row['expiry_date']."</td>";
                   
                 
                    echo  "<td><a href='deletecert.php?certifications_id=".$row['certifications_id']."'><i class='fa fa-trash'></i></a></td>";
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
  $("#datepicker,#datepicker1,#datepicker2,#datepicker3").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});

	</script>
		<script>
	$("#savefields").click(function() {
			if(document.getElementById('course_name').value==''){
			}else{ajaxindicatorstart("Processing..Please Wait..");}
});
	</script>
	<script type="text/javascript">
       $(document).on('click','#addEducationbtn',function(e) {
		   var data = $("#inputEducation").serialize();
//  var data = $("#BandForm").serialize();
  //ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingneweducation.php",
         success: function(data){
			//alert("Added Successfully");
			AddingEducation();
			 //ajaxindicatorstop();
			 
         }
		 
});
 });
    </script>
	<SCRIPT>
	$("#gonext").click(function() {
    if(document.getElementById("course_name").value != ''){
        alert("There are unsaved Changes in the Form. Save before Proceeding.");
		return false;
    } else {
    }
});
	</SCRIPT>
 <!-- JS-->
<script type="text/javascript">
       function AddingEducation() {
          
			var modal = document.getElementById('modal-default-Education');
            var ddl = document.getElementById("EducationSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputEducation").value;
            option.value = document.getElementById("inputEducation").value;
            ddl.options.add(option);
			document.getElementById("closeEducation").click();
			//document.getElementById("inputEducation").value="";
        
			     
        }
    </script>
	<script type="text/javascript">
       $(document).on('click','#addSpecializationbtn',function(e) {
		   var data = $("#inputSpecialization").serialize();
//  var data = $("#BandForm").serialize();
  //ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingspecialization.php",
         success: function(data){
			//alert("Added Successfully");
			AddingSpecialization();
			 //ajaxindicatorstop();
			 
         }
		 
});
 });
    </script>
	
 <!-- JS-->
<script type="text/javascript">
       function AddingSpecialization() {
          
			var modal = document.getElementById('modal-default-Specialization');
            var ddl = document.getElementById("SpecializationSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputSpecialization").value;
            option.value = document.getElementById("inputSpecialization").value;
            ddl.options.add(option);
			document.getElementById("closeSpecialization").click();
			//document.getElementById("inputEducation").value="";
        
			     
        }
    </script>
	<script>
	$("#datepicker1").change(function () {
    var startDate = document.getElementById("datepicker").value;
    var endDate = document.getElementById("datepicker1").value;
 
    if ((Date.parse(endDate) <= Date.parse(startDate))) {
        alert("End date should be greater than Start date");
        document.getElementById("datepicker1").value = "";
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

 