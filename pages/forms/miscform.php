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
if(isset($_GET['language_id']) && $_GET['language_id'] != ''){
	$language_id = $_GET['language_id'];}
$target_dir = "../uploads/";
  if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}	
  $target_file = basename($_FILES["Employee_image"]["name"]);

  move_uploaded_file($_FILES["Employee_image"]["tmp_name"],$target_file);
  $Vehicle_Name=mysqli_real_escape_string($db,$_POST['Vehicle_Name']);
  $About_acurus=mysqli_real_escape_string($db,$_POST['About_acurus']);
  $Relationship_With_Employee=mysqli_real_escape_string($db,$_POST['Relationship_With_Employee']);
  $Referrer_Name=mysqli_real_escape_string($db,$_POST['Referrer_Name']);
  $Referrer_Company_Name=mysqli_real_escape_string($db,$_POST['Referrer_Company_Name']);
$result = mysqli_query($db,"update employee_details set 
	PF_Number = '".$_POST["PF_Number"]."',
	UAN_Number = '".$_POST["UAN_Number"]."',
	ESIC_Number = '".$_POST["ESIC_Number"]."',
	Dispensary = '".$_POST["Dispensary"]."',
	Vehicle_Name = '$Vehicle_Name',
	Vehicle_reg = '".$_POST["Vehicle_reg"]."',
	About_acurus = '$About_acurus',
	refering_type = '".$_POST["refering_type"]."',
	Referrer_Name = '$Referrer_Name',
	Referrer_Company_Name = '$Referrer_Company_Name',
	Relationship_With_Employee = '$Relationship_With_Employee',
	referrer_email = '".$_POST["referrer_email"]."',
	Referrer_Contact_Phone = '".$_POST["Referrer_Contact_Phone"]."',
	Salary_Payment_Mode = 'Direct Deposit'
	where employee_id = $empId");
	$date = date("Y-m-d h:i:s");
$language = count($_POST["language_new"]);
if($language > 0)
{
	echo $message=$language;
		for($m=0; $m<$language; $m++)  
		{  	
			$skillsval = $_POST["language_new"][$m];
			$skillsetexp =$_POST["can_speak"][$m];
			$skillsetexp1 =$_POST["can_read"][$m];
			$skillsetexp2 =$_POST["can_write"][$m];
			if($skillsval!='')
			{
				$langinsert="INSERT INTO employee_languages (employee_id, language_name, can_read, can_speak, can_write,created_date_and_time,created_by,Is_Active) VALUES('$empId','$skillsval','$skillsetexp1','$skillsetexp','$skillsetexp2','$date','$empId','Y')";
				mysqli_query($db,$langinsert);	
				//echo $message=$langinsert;
			}
			else{}
		}	
}
  
  $date = date("Y-m-d h:i:s");
  //$result = mysqli_query($db,"UPDATE employee_details SET Employee_image =  '$target_file' where employee_id = '".$login_session."'");
  if(!$result){
	  echo "<script>alert('Problem in Adding to database. Please Retry.')</script>";
	} else {
		
		header("Location:miscform.php");
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

$details = mysqli_query($db,"select First_Name,MI,Last_Name,Date_of_Birth,Gender,Vehicle_Name,Vehicle_reg,Marital_Status,Spouse_Name,Marriage_Date,About_acurus,Remarks,Employee_image,Relationship_With_Employee,Referrer_Contact_Phone,referrer_email,
Referrer_Company_Name,Referrer_Name,refering_type,PF_Number,ESIC_Number,Dispensary,Bank_Name,Account_Number,Branch,IFSC_Code,UAN_Number
 from employee_details where employee_id=$empId");
$detRow = mysqli_fetch_array($details);
$Source=$detRow['About_acurus'];
$DropDownSource = mysqli_query($db,"SELECT distinct(all_source)  FROM `all_positions` where all_source is not null and all_source != '$Source' and all_source!='' order by job_type asc");
$blood=$detRow['Employee_Blood_Group'];
$DropDownBlood = mysqli_query($db,"SELECT distinct(blood_group)  FROM `all_blood_groups` where blood_group is not null and blood_group != '$blood' and blood_group!=''");
$bank=$detRow['Bank_Name'];
$DropDownBank=mysqli_query($db,"SELECT distinct bank_name FROM `tblbankname`
where bank_name is not null and bank_name != '$bank' and bank_name!=''");
$langquery=mysqli_query($db,"Select * from employee_languages where employee_id=$empId and Is_Active='Y'
and language_name !=''");

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

.close12 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
th {
  background-color: #31607c;
  color:white;
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
#LanguageAddBtn{
	background-color: #286090;
	display: inline-block;
    padding: 4px 10px;
    margin-bottom: 0;
    font-size: 12px;
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
      <small> Step 7 </small>
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
              <h3 class="box-title"> MISCELLANEOUS DETAILS</h3>
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
                  <label for="inputPFNumber" class="col-sm-2 control-label">Provident Fund Number</label>

                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="PF_Number" name="PF_Number" placeholder=" Provident Fund Number" value="<?php echo $detRow['PF_Number']; ?>" autocomplete="off" > 
                  </div>
				 <label for="inputDispensary" class="col-sm-2 control-label"  >UAN Number</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder = "UAN Number" name="UAN_Number" id="UAN_Number" value="<?php echo $detRow['UAN_Number']; ?>" maxlength=12 pattern="\d{12}" autocomplete="off" >
                  </div>
                </div>
					<div class="form-group">
                  <label for="inputDispensary" class="col-sm-2 control-label"  >Dispensary</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control pull-right" placeholder = "Dispensary" name="Dispensary" id="Dispensary" value="<?php echo $detRow['Dispensary']; ?>" autocomplete="off" >
                  </div>
				 <label for="inputESICNumber" class="col-sm-2 control-label">ESIC Number</label>
				  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputESIC" alt = "Enter ESIC Number" name="ESIC_Number" placeholder="ESIC_Number" value="<?php echo $detRow['ESIC_Number']; ?>" autocomplete="off" >
                  </div>
				</div>
				
				<hr style="width:100%;"align="left">
				<div class="form-group">
                  <label for="inputVehicleDesc" class="col-sm-2 control-label">Vehicle Name</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="Vehicle_Name" id="inputvehiclename" placeholder="Vehicle Name" value="<?php echo $detRow['Vehicle_Name']; ?>" autocomplete="off" >
                  </div> 
                  <label for="inputVehicleRegNo" class="col-sm-2 control-label">Vehicle Registration No:</label>
                  <div class="col-sm-4">
                    
					 <input type="text" class="form-control" id="inputvehicleregnum"					 name="Vehicle_reg" placeholder="Vehicle Registration No:" 
					 value="<?php echo $detRow['Vehicle_reg']; ?>" autocomplete="off" >
                  </div>				  
                </div>
					<hr style="width:100%;"align="left">
					<div class="form-group">
					<label for="inputforrefertype" class="col-sm-2 control-label">Refering Type</label>
					<div class = "col-lg-4">
					<?php 
					$reftype =  $detRow['refering_type'];
					if ($reftype == 'Employee')
					{
				   ?>
					<select class="form-control" id="refertype" name="refering_type">
					<option value="<?php echo $reftype ?>"><?php echo $reftype ?></option>
					  <option value="Consultancy">Consultancy</option>
					 </select>
					<?php }
					else if ($reftype == 'Consultancy'){
					?>
					<select class="form-control" id="refertype" name="refering_type">
					<option value="<?php echo $reftype ?>"><?php echo $reftype ?></option>
					  <option value="Employee">Employee</option>
					 </select>
					<?php } 
					else {?>
					<select class="form-control" id="refertype" name="refering_type">
					<option value="Employee">Employee</option>
					<option value="Consultancy">Consultancy</option>
					 </select>
					<?php } ?>
					 </div>
					 <label for="inputforrefername" class="col-sm-2 control-label">Referrer Name</label>
					 <div class="col-sm-4">
					 <input type="Text" name="Referrer_Name" id="Referrer_Name" placeholder="Enter The Name" class="form-control" value="<?php echo $detRow['Referrer_Name']; ?>" autocomplete="off" ></input>
					 </div>
					</div>
					<div class="form-group">
					<label for="inputforrefcomp" class="col-sm-2 control-label">Company Name</label>
					<div class="col-sm-4">
					 <input type="Text" name="Referrer_Company_Name" id="Referrer_Company_Name" placeholder="Enter The Company Name" class="form-control" value="<?php echo $detRow['Referrer_Company_Name']; ?>" autocomplete="off" ></input>
					 </div>
					 <label for="inputreferemail" class="col-sm-2 control-label">Referer Email</label>
					<div class="col-sm-4">
						<input type="email" class="form-control" id="email" name="referrer_email" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" placeholder="Enter Referer Email" value="<?php echo $detRow['referrer_email']; ?>" autocomplete="off"  >
				
					</div>
					</div>
					<div class="form-group">
					 <label for="inputrefererNo" class="col-sm-2 control-label">Referer Mobile Number 
				  </label>
				  <div class = "col-lg-1">
				  <select name= "country_code" class = "form-control">
					<option value="+91"> +91</option>
					<option value="+1"> +1</option>
				  </select>
				  </div>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="Referrer_Contact_Phone" name="Referrer_Contact_Phone" placeholder="Enter 10 digit mobile number" maxlength = '10' pattern="[6789][0-9]{9}" title ="Enter 10 digit number" value="<?php echo $detRow['Referrer_Contact_Phone']; ?>" autocomplete="off" >
                  </div>
				  <label for="inputforrefrel" class="col-sm-2 control-label">Your Relationship</label>
					<div class="col-sm-4">
					 <input type="Text" name="Relationship_With_Employee" id="Relationship_With_Employee" placeholder="Enter Your Relationship" class="form-control" value="<?php echo $detRow['Relationship_With_Employee']; ?>" autocomplete="off" ></input>
					 </div>
					</div>
					<hr style="width:100%;"align="left">
					<div class="">
                  <label for="inputlanguageDesc" class="col-sm-2 control-label">Language Description</label>
					<div id="inputlang_div" >
					<?php require_once("inputlang.php") ?>
			    </div>
				</div>	
			   </div>

              <!-- /.box-body -->			  
			  
			
			  <div class="box-footer">
			      <input action="action" class="btn btn-info pull-left" type="button" value="Previous" onclick="window.location='familyform.php';"id="goprevious" />  
			   <input type="button" class="pull-left" value="Clear" id="clearfields" style = "margin-left: 7px;" onclick="resetfields();" >
			     <!--<input type="reset" name="reset" class="btn btn-info pull-left" value="Clear" style = "margin-left: 7px; background-color: #da3047;border-color:#da3047;" id="clearfields">-->
				<a href="kyeform.php"><button type= "button" name= "submit" class="btn btn-info pull-right" id="gonext"> Next</button></a>
               <button type="submit" name="Submit" class="pull-right" id="savefields" style = "margin-right: 7px;" >Save</button>     
			
              </div>
			  <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 20px">#</th>
					<th>Language Name</th>     				
					<th>Can Speak</th>			
					<th>Can Read</th>			
					<th>Can Write</th>
					
                </tr>
                <?php
                if(mysqli_num_rows($langquery) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $l = 1;
                  while($row19 = mysqli_fetch_assoc($langquery)){
                    echo "<tr><td>".$l.".</td>";
                    echo "<td>".$row19['language_name']."</td>";
                    echo "<td>".$row19['can_speak']."</td>";
                    echo "<td>".$row19['can_read']."</td>";
                    echo "<td>".$row19['can_write']."</td>";
                   
                 
                    echo  "<td><a href='deletelang.php?language_id=".$row19['language_id']."'><i class='fa fa-trash'></i></a></td>";
                    $l++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
              <!-- /.box-footer -->			  		  
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
		if(document.getElementById("inputmaritalstatus").value=="Married")
		{
			document.getElementById("ifYes").style.display="block";
		}
		else
		{
			document.getElementById("ifYes").style.display = "none";
		}
	}
	</script>
	<SCRIPT>
function addmorelang() {
	$("<Div>").load("inputlang.php", function() {
			$("#inputlang_div").append($(this).html());
	});	
}
function delmorelang() {
	$('DIV.input_language').each(function(index, item){
		jQuery('#DelLangData', this).each(function () {
            if ($(this).is(':checked')) {
				$(item).remove();
            }
        });
	});
}
</SCRIPT>
	 <script>
     $( function() {
        $('input[id$=DOBdate]').datepicker({
			dateFormat: 'yyyy-mm-dd',
			autoclose: true
		});
		
    });
</script>
<script>
     $( function() {
        $('input[id$=datepicker1]').datepicker({
			dateFormat: 'yyyy-mm-dd',
			autoclose: true
		});
		
    });
</script>
	
 <!-- JS-->
 <script type="text/javascript">
       function resetfields() {
		   document.getElementById("PF_Number").value="";
			document.getElementById("inputESIC").value="";
			document.getElementById("Dispensary").value="";
			//document.getElementById("accnum").value="";
			//document.getElementById("inputbranchname").value="";
			//document.getElementById("inputifsccode").value="";
			//document.getElementById("bankoptions").value="";
			document.getElementById("inputvehiclename").value="";
			document.getElementById("inputvehicleregnum").value="";
			document.getElementById('UAN_Number').value = "";
			document.getElementById("Referrer_Name").value="";
			document.getElementById("Referrer_Company_Name").value="";
			document.getElementById("email").value="";
			document.getElementById("Relationship_With_Employee").value="";
			document.getElementById("Referrer_Contact_Phone").value="";
			document.getElementById("refertype").value="";
			//document.getElementById('bankoptions').value = "";
			
        }
    </script>
		 <script type="text/javascript">
       $(document).on('click','#addbanknamebtn',function(e) {
		   var data = $("#inputnamebank").serialize();
//  var data = $("#BandForm").serialize();
 // ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "addingbankname.php",
         success: function(data){
			AddingBank();
		//	 ajaxindicatorstop();
			 
         }
});
 });
    </script>
<script type="text/javascript">
       function AddingBank() {
        	var modal = document.getElementById('myModal1');
            var ddl = document.getElementById("bankoptions");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("inputnamebank").value;
            option.value = document.getElementById("inputnamebank").value;
            ddl.options.add(option);
			modal.style.display = "none";
			document.getElementById("inputnamebank").value="";
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
