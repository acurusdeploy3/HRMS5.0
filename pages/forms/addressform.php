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
	
if(isset($_POST['Submit'])){


if($_POST["sameaddress"]=='N')
{
	$sameadd = 'N';
}
else if($_POST["sameaddress"]=='Y')
{
	$sameadd = 'Y';
}
else
{
		$sameadd = 'N';
}
$Present_Address_Line_1=mysqli_real_escape_string($db,$_POST["Present_Address_Line_1"]);
$Permanent_Address_Line_1=mysqli_real_escape_string($db,$_POST["Permanent_Address_Line_1"]);
$Present_Address_Line_2=mysqli_real_escape_string($db,$_POST["Present_Address_Line_2"]);
$Permanent_Address_Line_2=mysqli_real_escape_string($db,$_POST["Permanent_Address_Line_2"]);
$Present_Address_Line_3=mysqli_real_escape_string($db,$_POST["Present_Address_Line_3"]);
$Permanent_Address_Line_3=mysqli_real_escape_string($db,$_POST["Permanent_Address_Line_3"]);
$Present_Street=mysqli_real_escape_string($db,$_POST["Present_Street"]);
$Permanent_Street=mysqli_real_escape_string($db,$_POST["Permanent_Street"]);
$Present_City=mysqli_real_escape_string($db,$_POST["Present_City"]);
$Permanent_City=mysqli_real_escape_string($db,$_POST["Permanent_City"]);
$Present_District=mysqli_real_escape_string($db,$_POST["Present_District"]);
$Permanent_District=mysqli_real_escape_string($db,$_POST["Permanent_District"]);

$result = mysqli_query($db,"update employee_details set Present_Address_Line_1= '$Present_Address_Line_1', 
		Present_Address_Line_3 = '$Present_Address_Line_3',
	Present_Address_Line_2 = '$Present_Address_Line_2',
	Present_Street = '".$Present_Street."',
	Present_City = '".$Present_City."',
	Present_District = '".$Present_District."',
	Present_State = '".$_POST["Present_State"]."',
	Present_Country = '".$_POST["Present_Country"]."',
	Present_Address_ZipCode = '".$_POST["Present_Address_ZipCode"]."',
	
	Permanent_Address_Line_1 = '$Permanent_Address_Line_1',
	Permanent_Address_Line_2 = '$Permanent_Address_Line_2',
		Permanent_Address_Line_3 = '$Permanent_Address_Line_3',
	Permanent_Street = '".$Permanent_Street."',
	Permanent_City = '".$Permanent_City."',
	Permanent_District = '".$Permanent_District."',
	Permanent_State = '".$_POST["Permanent_State"]."',
	Permanent_Country = '".$_POST["Permanent_Country"]."',
	Permanent_Address_Zip = '".$_POST["Permanent_Address_Zip"]."',
	is_address_same='$sameadd'
		
	where employee_id = '".$userid."' ");
 if(!$result){
			$message="Problem in Adding to database. Please Retry.";
	} else {
		header("Location:educationform.php");
	}
	$result = mysqli_query($db, "select * from employee_details where employee_id = '".$userid."' "); 
}
$states = mysqli_query($db,"SELECT name FROM states");
$states1 = mysqli_query($db,"SELECT name FROM states");

$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image, Permanent_State from employee_details where employee_id=$userid");

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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_designation,Employee_image,Permanent_State, Permanent_Country from employee_details where employee_id=$empId");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userDes = $useridrow['Employee_designation'];
$userImage = $useridrow['Employee_image'];
$permcountry = $useridrow['Permanent_Country'];

$address = mysqli_query($db," select Present_Address_Line_1,Present_Address_Line_2,Present_Address_Line_3,Present_Street,Present_City,Present_District,
Present_State,Present_Country,
Present_Address_ZipCode,Permanent_Address_Line_1,Permanent_Address_Line_2,Permanent_Address_Line_3,
Permanent_Street,Permanent_City,
Permanent_District,Permanent_State,Permanent_Country,Permanent_Address_Zip,is_address_same from employee_details where employee_id=$empId");
$detRow = mysqli_fetch_array($address);
$presentstate=$detRow['Present_State'];
$IsAddressSame=$detRow['is_address_same'];
$DropDownPresent = mysqli_query($db,"SELECT distinct(name),id  FROM `states` where name is not null and name != '$presentstate' and name!='' order by id");
$blood=$detRow['Employee_Blood_Group'];
$permamnentstate=$detRow['Present_State'];
$DropDownPermanent = mysqli_query($db,"SELECT distinct(name),id  FROM `states` where name is not null and name != '$permamnentstate' and name!='' order by id ");
$blood=$detRow['Employee_Blood_Group'];
$presentcountry=$detRow['Present_Country'];
$DropDownPresentCountry = mysqli_query($db,"SELECT distinct(country_name)  FROM `all_countries` where country_name is not null and country_name != '$presentcountry' and country_name!='' and country_name!='India' ");
$permamnentcountry=$detRow['Permanent_Country'];
$DropDownPermanentCountry = mysqli_query($db,"SELECT distinct(country_name)  FROM `all_countries` where country_name is not null and country_name != '$permamnentcountry' and country_name!='' and country_name!='India'  ");

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
      <small> Step 2 </small>
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
              <h3 class="box-title">PRESENT ADDRESS</h3>

            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			?>
            <form name ="form1" class="form-horizontal" method="post" action="" enctype="multipart/form-data" autocomplete="off">
              <div class="box-body">
				<div class="form-group">
                  <label for="inputPresentAddress1" class="col-sm-2 control-label">Building/Apartment Name</label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="Present_Address_Line_1"  placeholder="Building / Apartment Name" id="Present_Address_Line_1" value="<?php echo $detRow['Present_Address_Line_1']; ?>" autocomplete="off">
                  </div>
				  <label for="inputPresentAddress3" class="col-sm-1 control-label">Door #</label>
				  <label for="inputPresentAddress1" class="col-sm-1 control-label">New</label>
				  
				  <div class="col-sm-2">
                    <input type="text" class="form-control" name="Present_Address_Line_2" placeholder="" id="Present_Address_Line_2" value="<?php echo $detRow['Present_Address_Line_2']; ?>"  autocomplete="off">
                  </div>	
				<label for="inputPresentAddress2" class="col-sm-1 control-label">Old</label>
				  
				  <div class="col-sm-2">
                    <input type="text" class="form-control" name="Present_Address_Line_3" placeholder="" id="Present_Address_Line_3" value="<?php echo $detRow['Present_Address_Line_3']; ?>"  autocomplete="off">
                  </div>				  
                </div>		
					<div class="form-group">
					<label for="inputPresentStreet" class="col-sm-2 control-label">Street Name</label>
                  <div class="col-sm-4">
                    <input type="text" id="Present_Street" class="form-control" name="Present_Street" placeholder="Street Name" value="<?php echo $detRow['Present_Street']; ?>" autocomplete="off">
                  </div>
				  <label for="inputPresentCity" class="col-sm-2 control-label">City Name<span class="error">* </span></label>

                  <div class="col-sm-4">
                       <input type="text" id="Present_City" class="form-control" name="Present_City" placeholder="City Name"value="<?php echo $detRow['Present_City']; ?>" required autocomplete="off">
				</select>
                  </div>
                                  
                </div>				
                <div class="form-group">
				 <label for="inputPresentDistrict" class="col-sm-2 control-label">District</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="Present_District" name="Present_District" placeholder="District" value="<?php echo $detRow['Present_District']; ?>" autocomplete="off">
                  </div>
				<label for="inputPresentCountry" class="col-sm-2 control-label">Country<span class="error">*  </span> </label>
                  <div class="col-sm-4">
				  	<select name="Present_Country" id="country" class="form-control"  required >
					<?php if($presentcountry!='') {?>
					
					<option value="<?php echo $presentcountry ?>" selected><?php echo $presentcountry ?></option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPresentCountry))
						{
  				 ?>
					<option value= "<?php echo $row2['country_name']." ";?>" ><?php  echo $row2['country_name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
					<?php } else { ?>
					<option value="India" selected>India</option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPresentCountry))
						{
  				 ?>
					<option value= "<?php echo $row2['country_name']." ";?>" ><?php  echo $row2['country_name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
					<?php }?>
				</select>
                  </div>
				  
				  </div>
				
				<div class="form-group">
                  
                  <label for="inputPresentState" class="col-sm-2 control-label">State<span class="error">*  </span></label>
                  <div class="col-sm-4">
				<select class="form-control" id="state" name="Present_State"  >
				<option value="<?php echo $presentstate ?>" selected><?php echo $presentstate ?></option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPresent))
						{
  				 ?>
					<option value= "<?php echo $row2['name']." ";?>" ><?php  echo $row2['name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
		 </select>
                  </div>
				
                  <label for="inputPresentPincode" class="col-sm-2 control-label">Pincode <span class="error">*  </span></label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="Presentpincode" name="Present_Address_ZipCode" placeholder="Pincode" value="<?php echo $detRow['Present_Address_ZipCode']; ?>" required maxlength = "6" autocomplete="off">
                  </div>                            
                </div>				
              </div>
              <!-- /.box-body -->			  
			   <div class="box-header with-border">
              <h3 class="box-title">PERMANENT ADDRESS&nbsp;</h3>
			  <!-- <input type="checkbox" name="sameaddress" onclick="SameAddress(this.form)"> -->
			 
			
				
			 
<input type="checkbox" id="samecheckbox" name="sameaddress" value="<?php echo $IsAddressSame ?>" onclick="data_copy()" id="checkadd" <?php if($IsAddressSame=='Y') {?> checked="checked" <?php } else { ?>    <?php } ?>/> &nbsp;&nbsp; (Check to Copy Present Address)
			 
            </div>
			
			   <div class="box-body">
			  <div class="form-group">
                  <label for="inputPermanentAddress1" class="col-sm-2 control-label">Building/Apartment Name</label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="Permanent_Address_Line_1"  placeholder="Address Line 1" id="Permanent_Address_Line_1" value="<?php echo $detRow['Permanent_Address_Line_1']; ?>" autocomplete="off">
                  </div>
				  <label for="inputPermanentAddress4" class="col-sm-1 control-label">Door #</label>
				  <label for="inputPermanentAddress2" class="col-sm-1 control-label"> New</label>
				  
				  <div class="col-sm-2">
                    <input type="text" class="form-control" name="Permanent_Address_Line_2" placeholder="" id="Permanent_Address_Line_2" value="<?php echo $detRow['Permanent_Address_Line_2']; ?>"  autocomplete="off">
                  </div>
				<label for="inputPermanentAddress3" class="col-sm-1 control-label">Old</label>
				  
				  <div class="col-sm-2">
                    <input type="text" class="form-control" name="Permanent_Address_Line_3" placeholder="" id="Permanent_Address_Line_3" value="<?php echo $detRow['Permanent_Address_Line_3']; ?>" autocomplete="off">
                  </div>				  
                </div>
				<div class="form-group">
				
                  <label for="inputPermanentStreet" class="col-sm-2 control-label">Street Name</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="Permanent_Street" placeholder="Street Name" id="Permanent_Street" value="<?php echo $detRow['Permanent_Street']; ?>" autocomplete="off">
                  </div>
				                 <label for="inputPermanentCity" class="col-sm-2 control-label">City Name <span class="error">*  </span></label>

                  <div class="col-sm-4">
                       <input type="text" class="form-control" name="Permanent_City" placeholder="City Name" id="Permanent_City" value="<?php echo $detRow['Permanent_City']; ?>" required autocomplete="off">                
                  </div>

                </div>
                			
			  <div class="form-group">
			    <label for="inputPermanentDistrict" class="col-sm-2 control-label">District</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="Permanent_District" placeholder="District" id="Permanent_District" value="<?php echo $detRow['Permanent_District']; ?>" autocomplete="off">
                  </div>
				
			   <label for="inputPermanentCountry" class="col-sm-2 control-label">Country <span class="error">*  </span></label>
                  <div class="col-sm-4">
                   
					<select name="Permanent_Country" id="country1" class="form-control"  required >
					<?php if($permamnentcountry != '') { ?>
	<option value="<?php echo $permamnentcountry ?>" selected><?php echo $permamnentcountry ?></option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPermanentCountry))
						{
  				 ?>
					<option value= "<?php echo $row2['country_name']." ";?>" ><?php  echo $row2['country_name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
				 <?php } else  { ?>
				 <option value="India" selected>India</option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPermanentCountry))
						{
  				 ?>
					<option value= "<?php echo $row2['country_name']." ";?>" ><?php  echo $row2['country_name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
				 <?php } ?>
				</select>
                  </div>
				  
				  </div>
					
				<div class="form-group">
                  <label for="inputPermanentState" class="col-sm-2 control-label">State<span class="error">*  </span></label>
                  <div class="col-sm-4">
				<select class="form-control" id="state1" name="Permanent_State"  >
				<option value="<?php echo $permamnentstate ?>" selected><?php echo $permamnentstate ?></option>
				<?php
					while($row2 = mysqli_fetch_assoc($DropDownPermanent))
						{
  				 ?>
					<option value= "<?php echo $row2['name']." ";?>" ><?php  echo $row2['name']." "; ?></option> 
				<?php 
				    }
			   	 ?>
		 </select>
                  </div>
                
				
                  <label for="inputPresentPincode" class="col-sm-2 control-label">Pincode<span class="error">*  </span></label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="Permanentpincode" name="Permanent_Address_Zip" placeholder="Pincode" value="<?php echo $detRow['Permanent_Address_Zip']; ?>" required maxlength = "6" autocomplete="off">
                  </div>           
                 
                </div>
				</div>
			
			  <div class="box-footer">
			 
              <input type="button" class="btn btn-info pull-left" id="goprevious" type="button" value="Previous" onclick="window.location='firstform.php';"/>
               <input type="button" class="btn btn-info pull-left"  value="Clear " style = "background-color: #da3047;border-color:#da3047;margin-left: 7px;"  onclick="resetfields();" id="clearfields"/>		   
               <button type="submit" name="Submit" id="savefields" class="btn btn-info pull-right">Save and Continue</button>     
			
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
<script type="text/javascript">
function data_copy()
{

if(document.form1.sameaddress.checked ){
document.form1.Permanent_Address_Line_1.value=document.form1.Present_Address_Line_1.value;
document.form1.Permanent_Address_Line_2.value=document.form1.Present_Address_Line_2.value;
document.form1.Permanent_Address_Line_3.value=document.form1.Present_Address_Line_3.value;
document.form1.Permanent_City.value=document.form1.Present_City.value;
document.form1.Permanent_District.value=document.form1.Present_District.value;
document.form1.Permanent_Street.value=document.form1.Present_Street.value;
document.form1.Permanent_Address_Zip.value=document.form1.Present_Address_ZipCode.value;
document.form1.Permanent_Country.value=document.form1.Present_Country.value;

for(i=document.form1.Present_State.options.length-1;i>=0;i--)
{
if(document.form1.Present_State.options[i].selected)
document.form1.Permanent_State.options[i].selected=true;
}

}else{
document.form1.Permanent_Address_Line_1.value="";
document.form1.Permanent_Address_Line_2.value="";
document.form1.Permanent_Address_Line_3.value="";
document.form1.Permanent_City.value="";
document.form1.Permanent_District.value="";
document.form1.Permanent_Street.value="";
document.form1.Permanent_Address_Zip.value="";
document.form1.Permanent_Country.value="";
document.form1.Permanent_State.options[0].selected=true;

}

}

</script>
<script type="text/javascript">
       function resetfields() {
			document.getElementById("Present_Address_Line_1").value="";
			document.getElementById("Present_Address_Line_2").value="";
			document.getElementById("Present_Address_Line_3").value="";
			document.getElementById("Present_Street").value="";
			document.getElementById("Present_City").value="";
			document.getElementById("Present_District").value="";
			document.getElementById("country").value="";
			document.getElementById("state").value="";
			document.getElementById("Presentpincode").value="";
			document.getElementById("Permanent_Address_Line_1").value="";
			document.getElementById("Permanent_Address_Line_2").value="";
			document.getElementById("Permanent_Address_Line_3").value="";
			document.getElementById("Permanent_Street").value="";
			document.getElementById("Permanent_City").value="";
			document.getElementById("Permanent_District").value="";
			document.getElementById("country1").value="";
			document.getElementById("state1").value="";
			document.getElementById("Permanentpincode").value="";
			document.getElementById("samecheckbox").checked=false;
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

 