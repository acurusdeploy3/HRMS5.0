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
session_start();  
$userid=$_SESSION['login_user'];
require_once("config.php");
require_once("top-header.php");
require_once("documentModals.php");
	
	if(isset($_GET['qualifications_id']) && $_GET['qualifications_id'] != ''){
	$qualifications_id = $_GET['qualifications_id'];}


if(isset($_POST['Submit'])){
$date = date("Y-m-d h:i:s");
$fromyear = (isset($_POST['From_year']) && $_POST['From_year'])?$_POST['From_year']:'';
$toyear = (isset($_POST['To_Year']) && $_POST['To_Year'])?$_POST['To_Year']:'';
/*if($fromyear >= $toyear )
{
	 echo "<script>alert('To Date should be greater than To')</script>";
}*/

$course_name=mysqli_real_escape_string($db,$_POST['course_name']);
$specialization=mysqli_real_escape_string($db,$_POST['specialization']);
$Institution=mysqli_real_escape_string($db,$_POST['Institution']);
$Location=mysqli_real_escape_string($db,$_POST['Location']);
$university=mysqli_real_escape_string($db,$_POST['university']);
$result = mysqli_query($db,"INSERT INTO employee_qualifications(course_name,specialization, Institution,Location, From_year, To_Year,education_type, created_date_and_time,percentage_obtained,university, employee_id,created_by,is_active) 
	VALUES
	('".$course_name."',
	'".$specialization."',
	'".$Institution."',
	'".$Location."',
	'".$fromyear."',
	'".$toyear."',
	'".$_POST["education_type"]."',
	'".$date."',	
	'".$_POST["percentage_obtained"]."',
	'".$university."',	
	'".$userid."',
	'".$userid."',
	'Y'
	)");

 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
			
			
	} else {
		header("Location:educationform.php");
	}
}
$qualTypesQuery = mysqli_query($db,"SELECT qualification_desc FROM all_qualifications");
	$specTypesQuery = mysqli_query($db,"SELECT specialization_desc FROM all_specializations");
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$eduQuery = mysqli_query($db,"SELECT * FROM employee_qualifications where employee_id = '".$userid."' and is_active = 'Y'");

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
th {
  background-color: #31607c;
  color:white;
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
					?>   </p>
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
      <small> Step 3 </small>
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
              <h3 class="box-title">EDUCATION PROFILE</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" autocomplete="off">
              <div class="box-body">
				<div class="form-group">
                  <label for="inputEducation" id="EducationInfo" class="col-sm-2 control-label">Education<span class="error">*</span> </label>
                  <div class="col-sm-3">
                    <select class="form-control" name="course_name" id="additionalcourse" required >
					<option value="">Select Education </option>
                    <?php
					
                    while($row = mysqli_fetch_assoc($qualTypesQuery)){
                      echo "<option value='".$row['qualification_desc']."'>".$row['qualification_desc']."</option>";
                    }
                    ?>
                                        
                    </select>
					</div>
					<div class = "col-sm-1">
					<a href="#" id="myBtn" title="Click to Add another Education" data-toggle="modal" data-target="#modal-default-Education"><i class="fa fa-fw fa-plus"></i></a>			
                  </div>	
					<div id="myModal" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close12" data-dismiss="modal">&times;</span>
						<p>Add New Education:</p>
								<input type="text"  class="form-control" id="inputEducation" name="inputEducation" placeholder="Enter Education" /><br>
					<input id="AddEductaionBtn"  name="AddEduBtn" type="button" class = "btn btn-primary"value = "Add Education"/>
						</div>
			</div>
					
                  <label for="inputSpecialization" class="col-sm-2 control-label">Specialization </label>
                  <div class="col-sm-3">
                   <select class="form-control" name="specialization" id="specializationinput">
                      <option value="">Select Specialization Type</option>
                    <?php
					
                    while($row = mysqli_fetch_assoc($specTypesQuery)){
                      echo "<option value='".$row['specialization_desc']."'>".$row['specialization_desc']."</option>";
                    }
                    ?>
                                                                                 
                    </select>
                  </div>
				  <div class = "col-sm-1">
				  <a href="#" id="myBtn1" title="Click to Add another Specialization" data-toggle="modal" data-target="#modal-default-Specialization"><i class="fa fa-fw fa-plus"></i></a>
				</div>
			<div id="myModal1" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1" data-dismiss="modal">&times;</span>
						<p>Add New Specialization:</p>
								<input type="text"  class="form-control" id="inputSpecialization" name="inputSpecialization" placeholder="Enter Specialization" /><br>
					<input id="AddSpecializationBtn"  name="AddSpecBtn" type="button" class = "btn btn-primary"value = "Add Specialization" />
						</div>
			</div>
				</div>		
					<div class="form-group">
                  <label for="inputInstitution" class="col-sm-2 control-label">Institution Name<span class="error">*  </span></label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" name="Institution" id="Institution" placeholder="Institution Name" required autocomplete="off">
                  </div>
                 <label for="inputUniversity" class="col-sm-2 control-label">University</label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" name="university" placeholder="University Name" autocomplete="off">
                  </div>
				</div>		
                <div class="form-group">
					<label for="inputLocation" class="col-sm-2 control-label">Location</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="Location" placeholder="Location" autocomplete="off">
					</div>
					<label for="inputPercentage" class="col-sm-2 control-label">Percentage or CGPA<span class="error">*  </span></label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" id="percentage_obtained" name="percentage_obtained" placeholder="Percentage or CGPA" required autocomplete="off">
                  </div>                                
                </div>
				
				 <div class="form-group">
				<label for="inputFromYear" class="col-sm-2 control-label"  >From Year <span class="error">*  </span></label>
                  <div class="col-sm-4">
                    
                      <input type="text" class="form-control pull-right" placeholder = "From Year" id="fromyear" name="From_year" maxlength= "4" value="<?=( isset( $_POST['From_year'] ) ? $_POST['From_year'] : '' )?>" required autocomplete="off">
                    
                  </div>
                  <label for="inputToYear" class="col-sm-2 control-label"  >To Year <span class="error">*  </span></label>
                  <div class="col-sm-4">
                    
                      <input type="text" class="form-control pull-right" placeholder= "To Year" id= "toyear" name="To_Year" maxlength= "4" value="<?=( isset( $_POST['To_Year'] ) ? $_POST['To_Year'] : '' )?>" required autocomplete="off">
                   
                  </div>
				   
                </div>			
             
			  <div class="form-group">
                  <label for="inputEducationType" class="col-sm-2 control-label">Education Type</label>
                  <div class="col-sm-4">
                   <select class="form-control" name="education_type">
                      <option value="">Select Education Type</option>
                      <option value="Full time">Full time</option>
                      <option value="Part time">Part time</option>
                      <option value="Distant">Distant</option>                                        
                    </select>
                  </div>
                </div>
				<!--<div class="col-md-2 btn_div">
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PASSPORT" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
							<a href="#myModal" class="btn_anchor" data-toggle="modal" id="PASSPORT" data-target="#view-modal"><i class="fa fa-search" aria-hidden="true"></i></a>
						</div>
              -->
				
				</div>
			
			  <div class="box-footer">
                   <input class="btn btn-info pull-left" onclick="window.location='addressform.php';" type="button" value="Previous" id="goprevious" />                
				<input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;border-color:#da3047; margin-left: 7px;" id="clearfields">
				<a href="experienceform.php"><button type= "button" name= "submit" class="btn btn-info pull-right" id="gonext"> Next</button></a>
				
				<input type= "submit" name= "Submit"   class="btn btn-info pull-right" value= "Save" style = "margin-right: 7px;" onclick="return validatedate();" id="savefields">
              </div>
              <!-- /.box-footer -->			  		  
          
		  <div class="border-class">	
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>	  
                  
                  <th>Education</th>
                  <th>Specialization</th>
                  <th>Institution Name</th>
                  <th>University</th>
				  <th>Location</th>
					<th>From Year</th>
					<th>To Year</th>
					<th>% or CGPA</th>
					<th>Education Type</th>
					<th>Actions</th>
                </tr>
                
				<?php
                                if(mysqli_num_rows($eduQuery) < 1){
									echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
									
                                  while($row = mysqli_fetch_assoc($eduQuery)){
                                    echo "<tr>
									  <td>".$i."</td>							 
                										  <td>".$row['course_name']."</td>
                										  <td>".$row['specialization']."</td>
                										  <td>".$row['Institution']."</td>
                										  <td>".$row['university']."</td>
                										  <td>".$row['Location']."</td>
                										  <td>".$row['From_year']."</td>
                										  <td>".$row['To_Year']."</td>
                                      <td>".$row['percentage_obtained']."</td>
                                      <td>".$row['education_type']."</td>
									   <td><a href='deleteedu.php?qualifications_id=".$row['qualifications_id']."'><i class='fa fa-trash'></i></a></td>
                										</tr>";
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
  $("#datepicker,#datepicker1").datepicker({ 
	dateFormat: 'yyyy',
    autoclose: true
  });
});

	</script>
		<script>
	$("#savefields").click(function() {
			ajaxindicatorstart("Processing..Please Wait..");
			if(document.getElementById('additionalcourse').value=='' || document.getElementById('Institution').value==''|| document.getElementById('percentage_obtained').value=='' || document.getElementById('fromyear').value=='' || document.getElementById('toyear').value=='')
			{
				alert("Please Fill in all the Fields");
				ajaxindicatorstop();
				return false;
			}
			else
			{
				var startDate = document.getElementById("fromyear").value;
					var endDate = document.getElementById("toyear").value;
					if(startDate > endDate)
						{
							alert("To Year should be greater than From Year");
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
</script>
	 <script type="text/javascript">
       $(document).on('click','#AddEductaionBtn',function(e) {
		   var data = $("#inputEducation").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingneweducation.php",
         success: function(data){
			AddNewEducation();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
	
 <!-- JS-->
<SCRIPT>
	$("#gonext").click(function() {
    if(document.getElementById('additionalcourse').value!='' || document.getElementById('Institution').value!=''|| document.getElementById('percentage_obtained').value!='' || document.getElementById('fromyear').value!='' || document.getElementById('toyear').value!=''){
        alert("There are unsaved Changes in the Form. Save before Proceeding.");
		return false;
    } else {
       
    }
});
	</SCRIPT>
 <!-- JS-->

<script type="text/javascript">
       function AddNewEducation() {
          
			var modal = document.getElementById('myModal');
            var ddl = document.getElementById("additionalcourse");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputEducation").value;
            option.value = document.getElementById("inputEducation").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			// document.getElementById("AdditionalSourceText").value="";
        
			     
        }
    </script>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

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
       $(document).on('click','#AddSpecializationBtn',function(e) {
		   var data = $("#inputSpecialization").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingspecialization.php",
         success: function(data){
			AddNewSpecialization();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
<script type="text/javascript">
       function AddNewSpecialization() {
          
			var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("specializationinput");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputSpecialization").value;
            option.value = document.getElementById("inputSpecialization").value;
            ddl.options.add(option);
			 modal.style.display = "none";
			// document.getElementById("AdditionalSourceText").value="";
        
			     
        }
    </script>

<script>
// Get the modal
var modal1 = document.getElementById('myModal1');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

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
<script src="js/intlTelInput/intlTelInput.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</body>
</html>

 