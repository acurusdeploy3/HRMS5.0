<?php   
  session_start();  
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
 
$empId=$_SESSION['login_user'];
require_once("config.php");
require_once("top-header.php");
	
if(isset($_POST['Submit'])){
	$mobnum = (isset($_POST['Primary_Mobile_Number']) && $_POST['Primary_Mobile_Number'])?$_POST['Primary_Mobile_Number']:'';

$target_dir = "../uploads/";
  if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}	
  $target_file = basename($_FILES["Employee_image"]["name"]);
$Remarks=mysqli_real_escape_string($db,$_POST["Remarks"]);
$First_Name=mysqli_real_escape_string($db,$_POST["First_Name"]);
$MI=mysqli_real_escape_string($db,$_POST["MI"]);
$Last_Name=mysqli_real_escape_string($db,$_POST["Last_Name"]);
$emergency_contact_name=mysqli_real_escape_string($db,$_POST["emergency_contact_name"]);
$emergency_contact_relation=mysqli_real_escape_string($db,$_POST["emergency_contact_relation"]);
$About_acurus=mysqli_real_escape_string($db,$_POST["About_acurus"]);
  move_uploaded_file($_FILES["Employee_image"]["tmp_name"],$target_file);
  $query1="update employee_details set 
	First_Name= '".$First_Name."' ,
	MI = '".$MI."',
	Last_Name = '".$Last_Name."',
	Date_of_Birth = '".$_POST["Date_of_Birth"]."',
	Gender = '".$_POST["Gender"]."'	,
	Employee_Blood_Group = '".$_POST["Employee_Blood_Group"]."',
	Employee_Personal_Email = '".$_POST["Employee_Personal_Email"]."',
	country_code = '".$_POST["country_code"]."',
	Primary_Mobile_Number = '".$_POST["Primary_Mobile_Number"]."',
	Alternate_Mobile_number = '".$_POST["Alternate_Mobile_number"]."',
	emergency_contact_name = '".$emergency_contact_name."',
	emergency_contact_relation = '".$emergency_contact_relation."',
	Marital_Status = '".$_POST["Marital_Status"]."',
	About_acurus = '$About_acurus',	
	Remarks = '$Remarks',
	is_Active = 'Y' where employee_id = $empId";
$result = mysqli_query($db,$query1);

	$result11="update employee_details set 	
	Spouse_Name = '".$_POST["Spouse_Name"]."',
	Marriage_Date = '".$_POST["Marriage_Date"]."'		
	where employee_id = $empId and Marital_Status='Married'";
mysqli_query($db,$result11);
  
  $date = date("Y-m-d h:i:s");
  //$result = mysqli_query($db,"UPDATE employee_details SET Employee_image =  '$target_file' where employee_id = '".$login_session."'");
  if(!$result){
	  echo "<script>alert('Problem in Adding to database. Please Retry.')</script>";
	} else {
		
		//$message=$query1;
		header("Location:addressform.php");
	}
}
if(isset($_POST['Submit'])){
	mysqli_query($db,"UPDATE employee_details SET Employee_image =  '$target_file' where employee_id = '".$login_session."'");
}
$bloodgrouplist = mysqli_query($db,"SELECT blood_group FROM all_blood_groups");
$aboutacurus = mysqli_query($db,"SELECT about_desc FROM all_about_acurus");
?>
<?php
include("config.php");
session_start();
$empId=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$empId");

$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

$details = mysqli_query($db,"select First_Name,MI,Last_Name,Date_of_Birth,Gender,Employee_Blood_Group,
Employee_Personal_Email,country_code,Primary_Mobile_Number,Alternate_Mobile_number,emergency_contact_name,
emergency_contact_relation,Vehicle_Name,Vehicle_reg,Marital_Status,Spouse_Name,Marriage_Date,About_acurus,
Remarks
 from employee_details where employee_id=$empId");
$detRow = mysqli_fetch_array($details);
$Source=$detRow['About_acurus'];
$DropDownSource = mysqli_query($db,"SELECT distinct(about_desc)  FROM `all_about_acurus` where about_desc is not null and about_desc != '$Source' and about_desc!='' ");
$blood=$detRow['Employee_Blood_Group'];
$DropDownBlood = mysqli_query($db,"SELECT distinct(blood_group) as bd  FROM `all_blood_groups` where blood_group is not null and blood_group != '$blood' and blood_group!=''");
?>
<html>
<head>
 
<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
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
<script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


<style>
.btn-default {
    background-color: #3c8dbc;
border-color : #3c8dbc; }
	.skin-blue .sidebar a {
    color: #ffffff;}
	.error {color: #FF0000;}
	.col-lg-1 {
    width: 9.7%;	
}
.col-sm-3 {
    width: 23.5%;
}
img {
    vertical-align: middle;
    height: 30px;
    width: 30px;
    border-radius: 20px;
}
.fa-fw {
    padding-top: 13px;
}
.upload {
    color: #f3f7f9 !important;
    cursor: pointer !important;
    font-size: 12px;
    #height: 22px;
    padding: 5px 10px;
    text-align: left;
    text-shadow: 0 1px 0 #ffffff;  
    padding-left: 6px;
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
th {
  background-color: #31607c;
  color:white;
}
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
					
			  &nbsp;&nbsp;
              <span class="hidden-xs"><?php  echo $empId  ?></span>
              
			
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <p><?php // echo "<img src='../images/".$useridrow['Employee_image']."'  />" ;?> 

					
							  <?php 
					if (($useridrow['Employee_image'])!=null)
					{ echo "<img src='../../uploads/".$useridrow['Employee_image']."'  />" ;
					}
					else
					{ 
				 echo "<img src='../../uploads/avatar5.png'  />" ;
					}
					?> 
		
				</p>
					<p>
                  <?php  echo $usernameval  ?>
                  <small><?php  echo $userDes ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
             
            </ul>
          </li>
		  <li>
            
         <!-- <div class="upload" style="position: relative; overflow: hidden; cursor: default;">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <input type="file" class="form-control" id="Employee_image" name="Employee_image" accept="image/*" value="<?//=( isset( $_POST['Employee_image'] ) ? $_POST['Employee_image'] : '' )?>" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                  </div> -->
		  </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
          </li>
		  <p>
</p>
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
		   <?php //echo "<img src='../images/".$useridrow['Employee_image']."'  />" ;?>
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
      <small> Step 1 </small>
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
              <h3 class="box-title">PERSONAL DETAILS</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			<?php
			echo $temp;
			echo $message;	
			?>
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" onload="load();" autocomplete="off">
              <div class="box-body">
				<div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Full Name <span class="error">*  </span></label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="First_Name" name="First_Name" placeholder="First name" value="<?php echo $detRow['First_Name']; ?>" required  readonly> 
                  </div>
				  <div class="col-sm-2">
                    <input type="text" class="form-control" id="inputMiddleName" alt = "Enter middle name" name="MI" placeholder="Middle name" value="<?php echo $detRow['MI']; ?>" readonly>
                  </div>
				  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputLastName" name="Last_Name" placeholder="Last name" value="<?php echo $detRow['Last_Name']; ?>"  readonly>
                  </div>
                </div>
					<div class="form-group">
                  <label for="inputDOB" class="col-sm-2 control-label"  >D.O.B <span class="error">*  </span></label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder = "yyyy-mm-dd" name="Date_of_Birth" id="DOBdate" value="<?php echo $detRow['Date_of_Birth']; ?>" required autocomplete="anyrandomstring" data-date-end-date="0d">
                  </div>
				  <label for="inputGender" class="col-sm-2 control-label">Gender<span class="error">*  </span></label>
                   <div class="col-sm-4">
				   <?php 
					$gender =  $detRow['Gender'];
					if ($gender == 'Male')
					{
				   ?>
                    <select class="form-control" name="Gender" id ="genderselect" required >
					<option value="<?php echo $gender ?>"><?php echo $gender ?></option>
                      <option value="Female">Female</option>
                      <option value="Prefer not to disclose">Prefer not to disclose</option>       
                    </select>
					<?php
					}
					elseif ($gender == 'Female')
					{
					?>
					<select class="form-control" name="Gender" id ="genderselect" required >
                      <option value="<?php echo $gender ?>"><?php echo $gender ?></option>
                      <option value="Male">Male</option>
                      <option value="Prefer not to disclose">Prefer not to disclose</option>       
                    </select>
					<?php
					}
					else if($gender == 'Prefer not to disclose')
					{
					?>
					<select class="form-control" name="Gender" id ="genderselect" required >
                      <option value="<?php echo $gender ?>"><?php echo $gender ?></option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>      
                    </select>
					<?php
					}
					else{
					?>
					<select class="form-control" name="Gender" id ="genderselect" required >
					<option value="">Choose Your Gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option> 
					<option value="Prefer not to disclose">Prefer not to disclose</option>  					  
                    </select>
					<?php }?>
				
		 </select>
                   </div>
                </div>	
					<div class="form-group">
                  <label for="inputBloodGroup" id="bloodgroupinfo" class="col-sm-2 control-label">Blood Group	<span class="error">*  </span>				  					
				</label>
					<div class="col-md-3">
					 	
					<select class="form-control" id="BloodGroupSelect" name="Employee_Blood_Group" required >
					<?php 
					if ($blood != '')
					{
				   ?>				
				<option value="<?php echo $blood ?>"><?php echo $blood ?></option>
				<?php
					while($row21 = mysqli_fetch_assoc($DropDownBlood))
						{
  				 ?>
					<option value= "<?php echo $row21['bd']." ";?>" ><?php  echo $row21['bd']." "; ?></option> 
				<?php 
				    }
					}else{ ?>
					<option value="">Select Your Blood Group</option>
					<?php
					while($row21 = mysqli_fetch_assoc($DropDownBlood))
						{
  				 ?>		<option value= "<?php echo $row21['bd']." ";?>" ><?php  echo $row21['bd']." "; ?></option> 			
					<?php
					}
					}
			   	 ?>
		 </select>						
					</div>
					<div class="col-sm-1">
					<a href="#" id="myBtn" title="Click to Add More Blood Groups" data-toggle="modal" data-target="#modal-default-Blood-Group"><i class="fa fa-fw fa-plus"></i></a>	
						</div>
						
					<div id="myModal" class="modal">

			<!-- Modal content -->
				<div class="modal-content">
					<span class="close1">&times;</span>
						<p>Add New Blood Group:</p>
								<input type="text"  class="form-control" id="inputBloodGroup" name="inputBloodGroup" placeholder="Enter Blood Group" /><br>
					<input id="addBloodgroupbtn"  name="AddbloodBtn" type="button" class = "btn btn-primary"value = "Add Blood Group"/>
						</div>
			</div>	
				
					<label for="inputEmail" class="col-sm-2 control-label">Personal Email<span class="error">*  </span></label>
					<div class="col-sm-4">
						<input type="email" class="form-control" id="email" name="Employee_Personal_Email" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" placeholder="Personal Email" value="<?php echo $detRow['Employee_Personal_Email']; ?>" required >
				
					</div>
				</div>
				<div class="form-group">
                  <label for="inputMobileNo" class="col-sm-2 control-label">Mobile Number 
				  <span class="error">*  </span> </label>
				  <div class = "col-lg-1">
				  <select name= "country_code" class = "form-control">
					<option value="+91"> +91</option>
					<option value="+1"> +1</option>
				  </select>
				  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="Primary_Mobile_Number" name="Primary_Mobile_Number" placeholder="Enter 10 digit mobile number" value="<?php echo $detRow['Primary_Mobile_Number']; ?>" required maxlength = '10' pattern="[6789][0-9]{9}" title ="Enter 10 digit number" >
                  </div>
                  <label for="inputEmergencyNo" class="col-sm-2 control-label">Emergency Number</label>
				  <div class = "col-lg-1">
				  <select name= "country_code" class = "form-control">
					<option value="+91"> +91</option>
					<option value="+1"> +1</option>
				  </select>
				  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" maxlength="10" id="inputEmergencyNo" name="Alternate_Mobile_number" placeholder="Enter 10 digit mobile number" value="<?php echo $detRow['Alternate_Mobile_number']; ?>"pattern="[6789][0-9]{9}" title ="Enter 10 digit number" >
                  </div>
                </div>
				<div class="form-group">
                  <label for="inputMobileNo" class="col-sm-2 control-label">Person Name (Emergency Contact)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputcontactname" name="emergency_contact_name" placeholder="Name of Emergency Contact Person" value="<?php echo $detRow['emergency_contact_name']; ?>" >
                  </div>
                  <label for="inputEmergencyrelation" class="col-sm-2 control-label">Person Relation (Emergency Contact)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputcontactrelation" name="emergency_contact_relation" placeholder="Relation with Emergency Contact Person" value="<?php echo $detRow['emergency_contact_relation']; ?>"  >
                  </div>
                </div>
				<div class="form-group">
			
                  <label for="inputMaritalStatus" class="col-sm-2 control-label">Marital Status <span class="error">*  </span></label>
					<div class="col-sm-4">
					<?php 
					$maristatus =  $detRow['Marital_Status'];
					if ($maristatus == 'Married')
					{
				   ?>
                    <select class="form-control" name="Marital_Status" onchange="yesnoCheck(this);" id ="inputmaritalstatus"required>
					<option value="<?php echo $maristatus ?>"><?php echo $maristatus ?></option>
                      <option value="Unmarried"> Unmarried</option>
                      <option value="Prefer not to disclose">Prefer not to disclose</option>     
					  
                    </select>
					<?php
					}
					elseif ($maristatus == 'Unmarried')
					{
						
					?>
					<select class="form-control" name="Marital_Status" onchange="yesnoCheck(this);" id ="inputmaritalstatus"required>
                      <option value="<?php echo $maristatus ?>"><?php echo $maristatus ?></option>
                      <option value="Married">Married</option>
                      <option value="Prefer not to disclose">Prefer not to disclose</option>       
                    </select>
					<?php
					}
					else if($maristatus == 'Prefer not to disclose')
					{
					?>
					<select class="form-control" name="Marital_Status" onchange="yesnoCheck(this);" id ="inputmaritalstatus"required>
                      <option value="<?php echo $maristatus ?>"><?php echo $maristatus ?></option>
                      <option value="Married">Married</option>
                      <option value="Unmarried">Unmarried</option>      
                    </select>
					<?php
					}
					else{
					?>
					<select class="form-control" name="Marital_Status" onchange="yesnoCheck(this);" id ="inputmaritalstatus"required>
					<option value="" >Choose Your Marital Status</option>
                      <option value="Married">Married</option>
                      <option value="Unmarried">Unmarried</option> 
					  <option value="Prefer not to disclose">Prefer not to disclose</option>
					</select>
					<?php } ?>
					</div>
					<label for="inputAboutacurus" id ="acurusinfo" class="col-sm-2 control-label">How did you know about Acurus</label>
					<div class="col-sm-3">				
					<select class="form-control" id="About_acurus" name="About_acurus"  >
				 <?php 
					if ($Source != '')
					{
				   ?>				
				<option value="<?php echo $Source ?>"><?php echo $Source ?></option>
				<?php
					while($row22 = mysqli_fetch_assoc($DropDownSource))
						{
  				 ?>
					<option value= "<?php echo $row22['about_desc']." ";?>" ><?php  echo $row22['about_desc']." "; ?></option> 
				<?php 
				    }
					}else{ ?>
					<option value="">Select Your Option</option>
					<?php
					while($row22 = mysqli_fetch_assoc($DropDownSource))
						{
  				 ?>		<option value= "<?php echo $row22['about_desc']." ";?>" ><?php  echo $row22['about_desc']." "; ?></option> 			
					<?php
					}
					}
			   	 ?>
				 
		 </select>
					</div>	
					<div class="col-sm-1">
	<a href="#" id="myBtn1" title="Click to Add More Options" data-toggle="modal" data-target="#modal-default-About-Acurus"><i class="fa fa-fw fa-plus"></i></a>		
	</div>	
	 <div id="myModal1" class="modal">

			<!-- Modal content -->
				<div class="modal-content">	
					<span class="close12">&times;</span>
						<p>Add New Option:</p>
								<input type="text"  class="form-control" id="inputAboutacurus" name="inputAboutacurus" placeholder="Enter your Option" /><br>
					<input id="addaboutacurusbtn"  name="AddabtBtn" type="button" class = "btn btn-primary"value = "Add About Acurus"  />
						</div>
			</div>
					</div>
					<div class="form-group">
					<?php 
					$maristatus =  $detRow['Marital_Status'];
					if ($maristatus == 'Married')
					{
				   ?>
					<div id="ifYes" style="display: block;">
					
							<label for="inputSpouseName"  id="spouse" class="col-sm-2 control-label">Spouse Name</label>
							<div class="col-sm-4">
							<input type="text" class="form-control" id="inputSpouseName" name="Spouse_Name" placeholder="Spouse Name" value="<?php echo $detRow['Spouse_Name']; ?>">
							</div>
				
					<label for="inputMarriageDate" id="marriagedate" class="col-sm-2 control-label">Marriage Date</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder = "yyyy-mm-dd" name="Marriage_Date" id="datepicker1" value="<?php echo $detRow['Marriage_Date']; ?>" autocomplete="anyrandomstring" data-date-end-date="0d">
                  </div>
				  </div>
				  <?php
					}
					else
					{	
				$detRow['Spouse_Name']="";	
				$detRow['Marriage_Date']="";
				  ?>
				  <div id="ifYes" style="display: none;">
					
							<label for="inputSpouseName"  id="spouse" class="col-sm-2 control-label">Spouse Name</label>
							<div class="col-sm-4">
							<input type="text" class="form-control" id="inputSpouseName" name="Spouse_Name" placeholder="Spouse Name" value="<?php echo $detRow['Spouse_Name']; ?>"/>
							</div>
				
					<label for="inputMarriageDate" id="marriagedate" class="col-sm-2 control-label">Marriage Date</label>
                  <div class="col-sm-4">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" placeholder = "yyyy-mm-dd" name="Marriage_Date" id="datepicker1" value="<?php echo $detRow['Marriage_Date']; ?>" />
                    </div>
                  </div>
				  </div>
				  <?php
					}
					?>
				  </div>
					<div class="form-group">
					<label for="inputVehicleDesc" class="col-sm-2 control-label">Remarks</label>
                  <div class="col-sm-4">
					<input type="text" class="form-control" id="Remarks" name="Remarks" placeholder="Remarks" value="<?php echo $detRow['Remarks']; ?>" />
                  </div> 
				  <!--<label for="pwd" class="col-sm-2 control-label">Upload profile picture</label> 
                 
                    
					<div class="col-sm-4">
                    <input type="file" class="form-control" id="Employee_image" accept="image" name="Employee_image" value="//echo $detRow['Employee_image'];" >
					</div> -->
                  
					</div>	
			   </div>
              
              <!-- /.box-body -->			  
			  
			
			  <div class="box-footer">
			      
			   <input type="button" class="pull-left" value="Clear" id="clearfields"
			    onclick="resetfields();">
               <button type="submit" name="Submit" class="pull-right" id="savefields" >Save and Continue</button>     
			
			</form>
              </div>
			  </div>
              <!-- /.box-footer -->			  		  
           
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
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});

 function yesnoCheck(that) {
        if (that.value == "Married") {
            document.getElementById("ifYes").style.display = "block";
        }
		else{
		document.getElementById("ifYes").style.display = "none";	
		}
    }
	function load()
	{
		if(document.getElementById("inputmaritalstatus").value == "Married")
		{
			document.getElementById("ifYes").style.display="block";
		}
		else 
		{
			document.getElementById("ifYes").style.display = "none";
		}
	}
	</script>
	 <script>
     $( function() {
        $('input[id$=DOBdate]').datepicker({
			dateFormat: 'yyyy-mm-dd',
			autoclose: true,
			maxDate: new Date() 
		});
		
    });
</script>
<script>
     $( function() {
        $('input[id$=datepicker1]').datepicker({
			dateFormat: 'yyyy-mm-dd',
			autoclose: true,
			maxDate: new Date() 
		});
		
    });
</script>
		 <script type="text/javascript">
       $(document).on('click','#addBloodgroupbtn',function(e) {
		   var data = $("#inputBloodGroup").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingnewbloodgroup.php",
         success: function(data){
			AddingBloodGroup();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
 <!-- JS-->
<script type="text/javascript">
       function AddingBloodGroup() {
			var modal = document.getElementById('myModal');
            var ddl = document.getElementById("BloodGroupSelect");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputBloodGroup").value;
            option.value = document.getElementById("inputBloodGroup").value;
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
 <!-- JS-->
 <script type="text/javascript">
       function resetfields() {
			document.getElementById("First_Name").value="";
			document.getElementById("inputMiddleName").value="";
			document.getElementById("inputLastName").value="";
			document.getElementById("DOBdate").value="";
			//document.getElementById("genderselect").value="Choose your gender";
			//document.getElementById("BloodGroupSelect").value="Select blood group";
			document.getElementById("email").value="";
			document.getElementById("Primary_Mobile_Number").value="";
			document.getElementById("inputEmergencyNo").value="";
			document.getElementById("inputcontactname").value="";
			document.getElementById("inputcontactrelation").value="";
			document.getElementById("inputSpouseName").value="";
			document.getElementById('inputmaritalstatus').value = "";
			document.getElementById('genderselect').value = "";
			document.getElementById('BloodGroupSelect').value = "";
			document.getElementById('About_acurus').value = "";
			document.getElementById("marriagedate").value="";
			document.getElementById("ifYes").style.display = "none";
			//document.getElementById("acurusoptions").value="Choose your option";
			document.getElementById("datepicker1").value="";
			document.getElementById("Remarks").value="";
        }
    </script>
	 <script type="text/javascript">
       $(document).on('click','#addaboutacurusbtn',function(e) {
		   var data = $("#inputAboutacurus").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingnewoption.php",
         success: function(data){
			AddingAboutAcurus();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
<script type="text/javascript">
       function AddingAboutAcurus() {
        	var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("About_acurus");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputAboutacurus").value;
            option.value = document.getElementById("inputAboutacurus").value;
            ddl.options.add(option);
			modal.style.display = "none";
			document.getElementById("inputAboutacurus").value="";
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
