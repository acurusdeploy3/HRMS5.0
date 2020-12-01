<?php
session_start();
$usergrp = $_SESSION['login_user_group'];
if($usergrp == 'HR' || $usergrp == 'HR Manager') 
{
?>

<?php

include("config2.php");
//require_once("top-header.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$empId=$_GET['id'];
$date = date("Y-m-d");
$details = mysqli_query($db,"select employee_id,concat(First_name,'',Last_Name,'',MI) as name ,Date_of_Birth,Gender,Employee_Blood_Group,Employee_Personal_Email,country_code,Primary_Mobile_Number,Alternate_Mobile_number,emergency_contact_name,emergency_contact_relation,Vehicle_Name,Vehicle_reg,Marital_Status,Spouse_Name,Marriage_Date,About_acurus,Remarks,Employee_image,Position_Applied_For,
Present_Address_Line_1,Present_Address_Line_2,Present_Address_Line_3,Present_Street,Present_City,
Present_District,Present_State,Present_Country,Present_Address_ZipCode,Permanent_Address_Line_1,
Permanent_Address_Line_2,Permanent_Address_Line_3,Permanent_Street,Permanent_City,
Permanent_District,Permanent_State,Permanent_Country,Permanent_Address_Zip,Relationship_With_Employee,
Referrer_Contact_Phone,referrer_email,Referrer_Company_Name,Referrer_Name,refering_type,Date_Joined,Employee_Designation,Bank_Name,Account_Number,Branch,IFSC_Code,PF_Number,ESIC_Number,Dispensary
 from employee_details where employee_id=$empId");
$row= mysqli_fetch_assoc($details);
$langdet=mysqli_query($db,"SELECT language_name,can_read,can_speak,can_write FROM `employee_languages` where employee_id=$empId and is_active='Y'");
$qualdet=mysqli_query($db,"SELECT concat(course_name,' - ' ,specialization) as qualification, Institution, Location, From_year, To_Year, education_type, percentage_obtained, university FROM `employee_qualifications` where employee_id=$empId and course_name in('SSLC','Higher Secondary') and is_Active='Y' order by From_year asc");
$qualcoldet=mysqli_query($db,"SELECT concat(course_name,' - ' ,specialization) as qualification, Institution, Location, From_year, To_Year, education_type, percentage_obtained, university FROM `employee_qualifications` where employee_id=$empId and course_name not in('SSLC','Higher Secondary') and is_Active='Y' order by From_year asc");
$certdet=mysqli_query($db,"SELECT concat(course_name,' - ',certification_name) as qualification, description, course_offered_by, year(issued_date) as yearissue, year(expiry_date) as yearexpiry FROM `employee_certifications` where employee_id=$empId and is_Active='Y' order by issued_date asc");
$workdet=mysqli_query($db,"SELECT  company_name, designation, worked_from, worked_upto,
 last_ctc, reason_for_leaving, CEILING((DATEDIFF(worked_upto,worked_from)/30)) AS work_duration_months
 FROM `employee_work_history` where employee_id=$empId and is_Active='Y' order by worked_from asc");
$famdet=mysqli_query($db,"SELECT family_member_name, relationship_with_employee, qualification, occupation, marital_status, contact_number
FROM `employee_family_particulars` where employee_id=$empId and is_Active='Y'");
$docdet=mysqli_query($db,"SELECT  document_type, document_number, has_expiry, valid_from, valid_to, is_Active
 FROM `kye_details` where document_type like 'PASSPORT%' and employee_id=$empId and is_Active='Y'");
?>
 <?php
include("config2.php");
session_start();
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_designation,Employee_image from employee_details where employee_id=$empId");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userDes = $useridrow['Employee_designation'];
$userImage = $useridrow['Employee_image'];
?>

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
   <link rel="stylesheet" href="../../dist/css/customStyles.css">
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
td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  width:5%;
    height:35px;
}
.vertical{
    writing-mode:tb-rl;
    -webkit-transform:rotate(180deg);
    -moz-transform:rotate(180deg);
    -o-transform: rotate(180deg);
    -ms-transform:rotate(180deg);
    transform: rotate(180deg);
    white-space:nowrap;
    display:block;
    bottom:0;
    width:20px;
    height:180px;
}
.boxcolor
{
	background-color:#f2f2f2;
	font-weight:bold;
}
.special1
{
	color:#002060;
}
.special2
{
	color:#002060;
	text-align: center;
    font-weight: bold;
}
#myimg
{
vertical-align: middle;
    height: 45px;
    width: 315px;
    border-radius: 20px;	
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
	  <button OnClick="window.location.href='BoardingHome.php'" type="button"  class="btn btn-primary pull-left">Back</button>
	  <button  style="margin-right:101px;" type="button" id="downloadpdf" onclick="print();" class="btn btn-default pull-right">Download PDF &nbsp; <i class="fa fa-fw fa-file-pdf-o"> </i></button> 		
      </h1>
	  <br><br>
    </section>

    <!-- Main content -->
<section class="content">
	<div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
               	
 					
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php
			echo $message;
			echo $temp;
			
			?>
            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
             
              <!-- /.box-footer -->			  		  
          <?php
                if(mysqli_num_rows($details) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
					?>
		<div>
		<h1 class="special2">Acurus Employee Data Sheet</h1>
		</div>		
		  <div class="border-class">
		  <table class="table" style="width:100%">
		  <tbody>
            <table style="width:100%">
                <tr>
					<td style="width:10%" class="boxcolor special1"> Personal E Mail ID </td>
					<?php  echo "<td style='width:36%'>".$row['Employee_Personal_Email']."</td>"; ?>
					<td style="width:9%" class="boxcolor special1"> Date of Information </td>
					<?php  echo "<td style='width:12%'>$date</td>"; ?>
				</tr>
			</table>
			<table style="width:100%">	
				<tr>
					<td style="width:15%" class="boxcolor"> Name </td>
					<?php  echo "<td style='width:25%'>".$row['name']."</td>"; ?>
					<td style="width:20%" class="boxcolor special1"> Employee ID </td>
					<?php  echo "<td style='width:12%'>".$row['employee_id']."</td>"; ?>
					<td style="width:25%;border-bottom:none;border-top:none"></td>
				</tr>
				<tr>
					<td style="width:15%" class="boxcolor"> Gender </td>
					<?php  echo "<td style='width:25%'>".$row['Gender']."</td>"; ?>
					<td style="width:20%" class="boxcolor"> Blood Group </td>
					<?php  echo "<td style='width:12%'>".$row['Employee_Blood_Group']."</td>"; ?>
					<td style="width:25%;border-bottom:none;border-top:none"></td>
				</tr>
				<tr>
					<td style="width:15%" class="boxcolor"> Date of Birth </td>
					<?php  echo "<td style='width:25%'>".$row['Date_of_Birth']."</td>"; ?>
					<td style="width:20%" class="boxcolor"> Marital Status </td>
					<?php  echo "<td style='width:12%'>".$row['Marital_Status']."</td>"; ?>
					<td style="width:25%;border-bottom:none;border-top:none"></td>
				</tr>
				<tr>
					<td style="width:15%" class="boxcolor"> Spouse Name </td>
					<?php  echo "<td style='width:25%'>".$row['Spouse_Name']."</td>"; ?>
					<td style="width:20%" class="boxcolor"> Marriage Date </td>
					<?php  echo "<td style='width:12%'>".$row['Marriage_Date']."</td>"; ?>
					<td style="width:25%;border-top:none"></td>
				</tr>
            </table>
			<table style="width:100%">
				<tr>
					<td style="width:50%" class="boxcolor">Post Applied For</td>
					<?php  echo "<td style='width:50%'>".$row['Position_Applied_For']."</td>"; ?>
				</tr>
			</table>
			<table>
				<tr> 
				<td class="boxcolor">Languages Known (Please place a [ √ ] tick mark in the appropriate column)</td>
				</tr>
			</table>
			<table style="width:100%">
			<tr>
			<td style="width:25%" class="boxcolor"><b> Language Name </b></td>
			<td style="width:25%" class="boxcolor"><b> Speak </b></td>
			<td style="width:25%" class="boxcolor"><b> Read </b></td>
			<td style="width:25%" class="boxcolor"><b> Write </b></td>
			</tr>
			                <?php
                  while($row1 = mysqli_fetch_assoc($langdet)){
					echo "<tr>" ; 
                    echo "<td style='width:25%' class='boxcolor'>".$row1['language_name']."</td>";
                    echo "<td style='width:25%'>".$row1['can_read']."</td>";
                    echo "<td style='width:25%'>".$row1['can_speak']."</td>";
                    echo "<td style='width:25%'>".$row1['can_write']."</td>";
					echo "</tr>";
                  }
				  if(!mysqli_num_rows($langdet))
				  {
					echo "<tr>" ; 
                    echo "<td style='width:25%'>Tamil</td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
					echo "</tr>";
					echo "<tr>" ; 
                    echo "<td style='width:25%'>English</td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
					echo "</tr>";
					echo "<tr>" ; 
                    echo "<td style='width:25%'>Hindi</td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
					echo "</tr>";
					echo "<tr>" ; 
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:25%'></td>";
					echo "</tr>";
				  }
                ?>
			</table>
			<table style="width:100%">
				<tr> 
				<td class="boxcolor"><b>Present</b> Address</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:25%" class="boxcolor">Building / Apartment Name</td>
				<?php  echo "<td style='width:25%'>".$row['Present_Address_Line_1']."</td>"; ?>
				<td style="width:10%" class="boxcolor">Door #</td>
				<td style="width:10%" class="boxcolor">New</td>
				<?php  echo "<td style='width:10%'>".$row['Present_Address_Line_2']."</td>"; ?>
				<td style="width:10%" class="boxcolor">Old</td>
				<?php  echo "<td style='width:21%'>".$row['Present_Address_Line_3']."</td>"; ?>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:25%" class="boxcolor">Street Name</td>
				<?php echo "<td style='width:25%'>".$row['Present_Street']."</td>";?>
				<td style="width:25%" class="boxcolor">Locality Name</td>
				<td style='width:25%'></td>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">City Name</td>
				<?php echo "<td style='width:25%'>".$row['Present_City']."</td>";?>
				<td style="width:25%" class="boxcolor">Pin Code</td>
				<?php echo "<td style='width:25%'>".$row['Present_Address_ZipCode']."</td>";?>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">District</td>
				<?php echo "<td style='width:25%'>".$row['Present_District']."</td>";?>
				<td style="width:25%" class="boxcolor">State</td>
				<?php echo "<td style='width:25%'>".$row['Present_State']."</td>";?>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">Mobile Number</td>
				<?php echo "<td style='width:25%'>".$row['Primary_Mobile_Number']."</td>";?>
				<td style="width:25%" class="boxcolor">Residence Telephone #</td>
				<td style='width:25%'></td>
				</tr>
			</table>
			<table style="width:100%">
				<tr> 
				<td class="boxcolor"><b>Permanent</b> Address</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:25%" class="boxcolor">Building / Apartment Name</td>
				<?php  echo "<td style='width:25%'>".$row['Permanent_Address_Line_1']."</td>"; ?>
				<td style="width:10%" class="boxcolor">Door #</td>
				<td style="width:10%" class="boxcolor">New</td>
				<?php  echo "<td style='width:10%'>".$row['Permanent_Address_Line_2']."</td>"; ?>
				<td style="width:10%" class="boxcolor">Old</td>
				<?php  echo "<td style='width:21%'>".$row['Permanent_Address_Line_3']."</td>"; ?>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:25%" class="boxcolor">Street Name</td>
				<?php echo "<td style='width:25%'>".$row['Permanent_Street']."</td>";?>
				<td style="width:25%" class="boxcolor">Locality Name</td>
				<td style='width:25%'></td>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">City Name</td>
				<?php echo "<td style='width:25%'>".$row['Permanent_City']."</td>";?>
				<td style="width:25%" class="boxcolor">Pin Code</td>
				<?php echo "<td style='width:25%'>".$row['Permanent_Address_Zip']."</td>";?>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">District</td>
				<?php echo "<td style='width:25%'>".$row['Permanent_District']."</td>";?>
				<td style="width:25%" class="boxcolor">State</td>
				<?php echo "<td style='width:25%'>".$row['Permanent_State']."</td>";?>
				</tr>
				<tr>
				<td style="width:25%" class="boxcolor">Mobile Number</td>
				<?php echo "<td style='width:25%'>".$row['Primary_Mobile_Number']."</td>";?>
				<td style="width:25%" class="boxcolor">Residence Telephone #</td>
				<td style='width:25%'></td>
				</tr>
			</table>
			
			
			</tbody>
		  </table>
          </div>
		   <div class="box-footer">
		   <h6 style="text-align: center;">Acurus Solutions Private Limited, Chennai</h6>
		   <h6 style="text-align: right;">1 of 4</h6>
		   </div>
		  <div>
		<h1 class="special2">Acurus Employee Data Sheet</h1>
		</div>
		  <div class="border-class">
		  <table class="table" style="width:100%">
		  <tbody>
			<table style="width:100%">
				<tr>
				<td style="width:100%" class="boxcolor"><b>Educational Qualifications</b></td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:5%"></td>
				<td style="width:95%">Kindly fill in ascending order of year</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:5%"></td>
				<td style="width:25%" class="boxcolor"><b>School Name</b></td>
				<td style="width:20%" class="boxcolor"><b>Location</b></td>
				<td style="width:20%" class="boxcolor"><b>Qualification</b></td>
				<td style="width:10%" class="boxcolor"><b>From Year</b></td>
				<td style="width:10%" class="boxcolor"><b>To Year</b></td>
				<td style="width:15%" class="boxcolor"><b>% Of Marks</b></td>
				</tr>
				  <?php
				  $i=1;
                  while($row2 = mysqli_fetch_assoc($qualdet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$i."</td>";
                    echo "<td style='width:25%'>".$row2['Institution']."</td>";
                    echo "<td style='width:20%'>".$row2['Location']."</td>";
                    echo "<td style='width:20%'>".$row2['qualification']."</td>";
                    echo "<td style='width:10%'>".$row2['From_year']."</td>";
					echo "<td style='width:10%'>".$row2['To_Year']."</td>";
					echo "<td style='width:15%'>".$row2['percentage_obtained']."</td>";
					echo "</tr>";
					  $i++;}
					   if(!mysqli_num_rows($qualdet))
					{	do{
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$z."</td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'></td>";
					echo "<td style='width:10%'></td>";
					echo "<td style='width:15%'></td>";
					echo "</tr>";
					$i++;
					  }while($i<4);
					}
					  
                ?>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:5%"></td>
				<td style="width:25%" class="boxcolor"><b>College Name</b></td>
				<td style="width:20%" class="boxcolor"><b>Location</b></td>
				<td style="width:20%" class="boxcolor"><b>Qualification</b></td>
				<td style="width:10%" class="boxcolor"><b>From Year</b></td>
				<td style="width:10%" class="boxcolor"><b>To Year</b></td>
				<td style="width:15%" class="boxcolor"><b>% Of Marks</b></td>
				</tr>
				  <?php
				  $j=1;
                  while($row3 = mysqli_fetch_assoc($qualcoldet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$j."</td>";
                    echo "<td style='width:25%'>".$row3['Institution']."</td>";
                    echo "<td style='width:20%'>".$row3['Location']."</td>";
                    echo "<td style='width:20%'>".$row3['qualification']."</td>";
                    echo "<td style='width:10%'>".$row3['From_year']."</td>";
					echo "<td style='width:10%'>".$row3['To_Year']."</td>";
					echo "<td style='width:15%'>".$row3['percentage_obtained']."</td>";
					echo "</tr>";
					  $j++;}
					  if(!mysqli_num_rows($qualcoldet))
					{	do{
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$z."</td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'></td>";
					echo "<td style='width:10%'></td>";
					echo "<td style='width:15%'></td>";
					echo "</tr>";
					$j++;
					}while($j<4);}
                ?>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:5%"></td>
				<td style="width:25%" class="boxcolor"><b>Institution</b></td>
				<td style="width:20%" class="boxcolor"><b>Location</b></td>
				<td style="width:20%" class="boxcolor"><b>Qualification</b></td>
				<td style="width:10%" class="boxcolor"><b>From Year</b></td>
				<td style="width:10%" class="boxcolor"><b>To Year</b></td>
				<td style="width:15%" class="boxcolor"><b>% Of Marks</b></td>
				</tr>
				  <?php
				  $k=1;
                  while($row4 = mysqli_fetch_assoc($certdet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$k."</td>";
                    echo "<td style='width:25%'>".$row4['course_offered_by']."</td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'>".$row4['qualification']."</td>";
                    echo "<td style='width:10%'>".$row4['yearissue']."</td>";
					echo "<td style='width:10%'>".$row4['yearexpiry']."</td>";
					echo "<td style='width:15%'></td>";
					echo "</tr>";
					$k++;
				  }
				  if(!mysqli_num_rows($certdet))
					{	
					do{
					echo "<tr>" ; 
					echo "<td style='width:5%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'></td>";
					echo "<td style='width:10%'></td>";
					echo "<td style='width:15%'></td>";
					echo "</tr>";
					$k++;
					}while ($k<4);
					}
                ?>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:100%" class="boxcolor"><b>Work Experience</b> - (Kindly fill in the most recent company at the top)</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
				<td style="width:5%" class="boxcolor">Tab</td>
				<td style="width:25%" class="boxcolor">Name of Work Place</td>
				<td style="width:20%" class="boxcolor">Designation</td>
				<td style="width:10%" class="boxcolor">From Year</td>
				<td style="width:9%" class="boxcolor">To year</td>
				<td style="width:8%" class="boxcolor">Months</td>
				<td style="width:12%" class="boxcolor">Reason for Leaving</td>
				<td style="width:15%" class="boxcolor">Last CTC Amount</td>
				</tr>
				<?php
				  $l=1;
                  while($row5 = mysqli_fetch_assoc($workdet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$l."</td>";
                    echo "<td style='width:25%'>".$row5['company_name']."</td>";
                    echo "<td style='width:20%'>".$row5['designation']."</td>";
                    echo "<td style='width:11%'>".$row5['worked_from']."</td>";
                    echo "<td style='width:11%'>".$row5['worked_upto']."</td>";
					echo "<td style='width:8%'>".$row5['work_duration_months']."</td>";
					echo "<td style='width:18%'>".$row5['reason_for_leaving']."</td>";
					echo "<td style='width:15%'>".$row5['last_ctc']."</td>";
					echo "</tr>";
					$l++;
                  }
				  if(!mysqli_num_rows($workdet))
				  { do{
					  echo "<tr>" ; 
					echo "<td style='width:5%'></td>";
                    echo "<td style='width:25%'>".$row5['company_name']."</td>";
                    echo "<td style='width:20%'>".$row5['designation']."</td>";
                    echo "<td style='width:11%'>".$row5['worked_from']."</td>";
                    echo "<td style='width:11%'>".$row5['worked_upto']."</td>";
					echo "<td style='width:8%'>".$row5['work_duration_months']."</td>";
					echo "<td style='width:18%'>".$row5['reason_for_leaving']."</td>";
					echo "<td style='width:15%'>".$row5['last_ctc']."</td>";
					echo "</tr>";
					$l++;
				  }while($l<4);
				}
                ?>
			</table>
		  </tbody>
		  </table>
		  <br><label> 1 Provide copies of certificates, as applicable </label>
		  <br><label> 2 Name of the Company</label>
		  <br><label> 3 Cost to Company – for a year</label>
		  </div>
		  <div class="box-footer">
		   <h6 style="text-align: center;">Acurus Solutions Private Limited, Chennai</h6>
		   <h6 style="text-align: right;">2 of 4</h6>
		   </div>
		  <div>
		<h1 class="special2">Acurus Employee Data Sheet</h1>
		</div>
		 <div class="border-class">
		  <table class="table" style="width:100%">
		  <tbody>
		  <table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Family Particulars</b></td>
			</tr>
		  </table>
		  <table style="width:100%">
			<tr>
				<td style="width:5%" class="boxcolor">Tab</td>
				<td style="width:25%" class="boxcolor">Name</td>
				<td style="width:20%" class="boxcolor">Relationship</td>
				<td style="width:20%" class="boxcolor">Qualifications</td>
				<td style="width:10%" class="boxcolor">Occupation</td>
				<td style="width:10%" class="boxcolor">Marital Status</td>
				<td style="width:15%" class="boxcolor">Telephone #</td>
			</tr>
			<?php
				  $m=1;
                  while($row6 = mysqli_fetch_assoc($famdet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$m."</td>";
                    echo "<td style='width:25%'>".$row6['family_member_name']."</td>";
                    echo "<td style='width:20%'>".$row6['relationship_with_employee']."</td>";
                    echo "<td style='width:20%'>".$row6['qualification']."</td>";
                    echo "<td style='width:10%'>".$row6['occupation']."</td>";
					echo "<td style='width:10%'>".$row6['marital_status']."</td>";
					echo "<td style='width:15%'>".$row6['contact_number']."</td>";
					echo "</tr>";
					$m++;
                  }
				  if(!mysqli_num_rows($famdet))
				  {
					  do{
					echo "<tr>" ; 
					echo "<td style='width:5%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'></td>";
					echo "<td style='width:10%'></td>";
					echo "<td style='width:15%'></td>";
					echo "</tr>";
					$m++;
					  }while($m<4);
				  }
                ?>
		  </table>
		   <table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Passport Details</b></td>
			</tr>
		  </table>
		  <table style="width:100%">
			<tr class="boxcolor">
				<td style="width:5%">Tab</td>
				<td style="width:25%">Passport Number</td>
				<td style="width:20%">Name in Passport</td>
				<td style="width:20%">Place of Issue</td>
				<td style="width:10%">Valid Until</td>
				<td style="width:25%">Visas Details</td>
			</tr>
			<?php
				  $n=1;
                  while($row71 = mysqli_fetch_assoc($docdet)){
					echo "<tr>" ; 
					echo "<td style='width:5%'>".$n."</td>";
                    echo "<td style='width:25%'>".$row71['document_number']."</td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'>".$row71['valid_to']."</td>";
					echo "<td style='width:25%'></td>";
					echo "</tr>";
					$n++;
                  }
				  if(!mysqli_num_rows($docdet)){
					  do{
					echo "<tr>" ; 
					echo "<td style='width:5%'></td>";
                    echo "<td style='width:25%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:20%'></td>";
                    echo "<td style='width:10%'></td>";
					echo "<td style='width:25%'></td>";
					echo "</tr>";
					$n++;
					  }while($n<4);
				  }
                ?>
		  </table>
		  <table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Vehicle Details</b></td>
			</tr>
		  </table>
		  <table style="width:100%">
			<tr class="boxcolor">
				<td style="width:5%">Tab</td>
				<td style="width:65%">Vehicle Description</td>
				<td style="width:35%">Vehicle Registration Number</td>
			</tr>
			<tr>
				<?php echo "<td style='width:5%'>1</td>";?>
				<?php echo "<td style='width:65%'>".$row['Vehicle_Name']."</td>";?>
				<?php echo "<td style='width:35%'>".$row['Vehicle_reg']."</td>";?>
			<tr>
		  </table>
		  <table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>How did you come to know of Acurus? Please provide details below</b></td>
			</tr>
			<tr><?php echo "<td style='width:100%'>".$row['About_acurus']."</td>";?></tr>
			</tr>
		  </table>
		  <table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>References </b>(Please provide reference person names)</td>
			</tr>
		  </table>
		  <table style="width:100%">
			<tr class="boxcolor">
				<td style="width:5%"><b>Tab</b></td>
				<td style="width:25%"><b>Name of Reference Person</b></td>
				<td style="width:20%"><b>Company Name</b></td>
				<td style="width:20%"><b>Your Relationship</b></td>
				<td style="width:10%"><b>E Mail ID</b></td>
				<td style="width:25%"><b>Telephone Number</b></td>
			</tr>
			<tr>
				<?php echo "<td style='width:5%'>1</td>";?>
				<?php echo "<td style='width:25%'>".$row['Referrer_Name']."</td>";?>
				<?php echo "<td style='width:20%'>".$row['Referrer_Company_Name']."</td>";?>
				<?php echo "<td style='width:20%'>".$row['Relationship_With_Employee']."</td>";?>
				<?php echo "<td style='width:10%'>".$row['referrer_email']."</td>";?>
				<?php echo "<td style='width:25%'>".$row['Referrer_Contact_Phone']."</td>";?>
			<tr>
			<tr></tr>
		  </table>
		  </tbody>		  
		  </table>
		  <br>
		  <label style="font-weight: normal !important;">I hereby declare that the above particulars provided by me true and correct. Also, I have provided details above voluntarily in fulfillment of my employment requirements with Acurus.</label>
		  <br><br><br><br>
		  <table style="width:100%; border:none;">
			<tr>
			<?php echo "<td style='width:25%;border:none;'>".$row['name']."</td>";?>
			<td style="width:25%;border:none;"></td>
			<td style="width:25%;border:none;"></td>
			</tr>
			<tr>
			<td style="width:25%;border:none;">Signature</td>
			<?php  echo "<td style='width:25%;border:none;'>Date:&nbsp;&nbsp;&nbsp;$date</td>";?>
			<td style="width:25%;border:none;">Place:</td>
			</tr>
		  </table>
		  </div>
		  <div class="box-footer">
		   <h6 style="text-align: center;">Acurus Solutions Private Limited, Chennai</h6>
		   <h6 style="text-align: right;">3 of 4</h6>
		   </div>
				<?php } ?>
          </div>
          <!-- /.box -->
		   </div>
      <!-- /.row -->
    </section>		  
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
</div> 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
function print() {
	document.getElementById("downloadpdf").style.display="none";
    var e = document.getElementById("datasheetcontent");
    printWindow = window.open(), printWindow.document.write("<div style='width:70%;margin:0'>"), printWindow.document.write("<div id='datasheetcontent' onload="javascript:window.print();"/>"), printWindow.document.write("</div>"), printWindow.setTimeout(function () {
        printWindow.close()
    }, 1e4)
}
</script>
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
</body>
</html>
<?php
}
else
{
	header("Location: ../forms/Logout.php");
}
?>