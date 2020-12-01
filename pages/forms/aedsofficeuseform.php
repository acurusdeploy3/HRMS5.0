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
session_start();
$usergrp=$_SESSION['login_user_group'];
$empId=$_SESSION['login_user'];
$date1 = date("d-M-Y");
$details = mysqli_query($db,"select employee_id,concat(First_name,'',Last_Name,'',MI) as name ,Date_of_Birth,Gender,Employee_Blood_Group,Employee_Personal_Email,country_code,Primary_Mobile_Number,Alternate_Mobile_number,emergency_contact_name,emergency_contact_relation,Vehicle_Name,Vehicle_reg,Marital_Status,Spouse_Name,Marriage_Date,About_acurus,Remarks,Employee_image,Position_Applied_For,
Present_Address_Line_1,Present_Address_Line_2,Present_Address_Line_3,Present_Street,Present_City,
Present_District,Present_State,Present_Country,Present_Address_ZipCode,Permanent_Address_Line_1,
Permanent_Address_Line_2,Permanent_Address_Line_3,Permanent_Street,Permanent_City,
Permanent_District,Permanent_State,Permanent_Country,Permanent_Address_Zip,Relationship_With_Employee,
Referrer_Contact_Phone,referrer_email,Referrer_Company_Name,Referrer_Name,refering_type,Date_Joined,
Employee_Designation,Bank_Name,Account_Number,Branch,IFSC_Code,PF_Number,ESIC_Number,Dispensary,
Salary_Payment_Mode,Local_Office,business_unit,date_of_Assessment,conducted_by,Marks_obtained,Max_Score,
typing_speed,typing_accuracy,round(((Marks_obtained/Max_Score)*100),2) as percent
from employee_details where employee_id=$empId");
 
$row= mysqli_fetch_assoc($details);
$langdet=mysqli_query($db,"SELECT language_name,can_read,can_speak,can_write FROM `employee_languages` where employee_id=$empId");
$qualdet=mysqli_query($db,"SELECT concat(course_name,' - ' ,specialization) as qualification, Institution, Location, From_year, To_Year, education_type, percentage_obtained, university FROM `employee_qualifications` where employee_id=$empId and course_name in('SSLC','Higher Secondary') and is_Active='Y' order by From_year asc");
$qualcoldet=mysqli_query($db,"SELECT concat(course_name,' - ' ,specialization) as qualification, Institution, Location, From_year, To_Year, education_type, percentage_obtained, university FROM `employee_qualifications` where employee_id=$empId and course_name not in('SSLC','Higher Secondary') and is_Active='Y' order by From_year asc");
$certdet=mysqli_query($db,"SELECT concat(course_name,' - ',certification_name) as qualification, description, course_offered_by, issued_date, expiry_date FROM `employee_certifications` where employee_id=$empId and is_Active='Y' order by issued_date asc ");
$workdet=mysqli_query($db,"SELECT  company_name, designation, worked_from, worked_upto,
 last_ctc, reason_for_leaving, work_duration_months
 FROM `employee_work_history` where employee_id=$empId and is_Active='Y' order by worked_from asc");
$famdet=mysqli_query($db,"SELECT family_member_name, relationship_with_employee, qualification, occupation, marital_status, contact_number
FROM `employee_family_particulars` where employee_id=$empId and is_Active='Y'");
$docdet=mysqli_query($db,"SELECT document_number FROM `kye_details` where document_type like '%PERMANANT ACCOUNT NUMBER%' and employee_id=$empId and is_Active='Y'");
$docnum= mysqli_fetch_assoc($docdet);
$officedet=mysqli_query($db,"select department,designation,band,level from resource_management_table where employee_id=$empId"); 
$row1=mysqli_fetch_array($officedet);
$projectdet=mysqli_query($db,"SELECT project_name FROM `employee_projects` e inner join all_projects a on e.project_id=a.project_id and e.employee_id=$empId");
$row2=mysqli_fetch_array($projectdet);
 
?>
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
 
          

          

   <div class="content-wrapper" id="datasheetcontent">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       ACURUS EMPLOYEE DATA SHEET
      </h1>
      <input type="button" value="Download" id="downloadpdf" onclick="print();"/>
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
			<h5 style="text-align: center;">For Office Use only</h5>
		  <table class="table" style="width:100%">
			<tbody>
			 <table style="width:100%;">
			 <tr>
			 <td style="width:25%;" class="boxcolor">Employee ID</td>
			 <?php echo "<td style='width:25%'>".$row['employee_id']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Employee Name</td>
			 <?php echo "<td style='width:25%'>".$row['name']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Date Of Appointment</td>
			 <?php echo "<td style='width:25%'>".$row['Date_Joined']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Salary Payment Mode</td>
			 <?php echo "<td style='width:25%'>".$row['Salary_Payment_Mode']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Designation</td>
			 <?php echo "<td style='width:25%'>".$row1['designation']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Department</td>
			 <?php echo "<td style='width:25%'>".$row1['department']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Function</td>
			 <?php echo "<td style='width:25%'></td>";?>
			 <td style="width:25%;" class="boxcolor">Level</td>
			 <?php echo "<td style='width:25%'>".$row1['level']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Band</td>
			 <?php echo "<td style='width:25%'>".$row1['band']."</td>";?>
			 <td style="width:25%;"class="boxcolor" >Project (at the time of joining)</td>
			 <?php echo "<td style='width:25%'>".$row2['project_name']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Employment Location</td>
			 <?php echo "<td style='width:25%'>".$row['business_unit']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Provident Fund Number</td>
			 <?php echo "<td style='width:25%'>".$row['PF_Number']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">ESIC Number</td>
			 <?php echo "<td style='width:25%'>".$row['ESIC_Number']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Dispensary</td>
			 <?php echo "<td style='width:25%'>".$row['Dispensary']."</td>";?>
			 </tr>
			 <tr>
			 <td style="width:25%;" class="boxcolor">Local Office</td>
			 <?php echo "<td style='width:25%'>".$row['Local_Office']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Permanent Account No (PAN)</td>
			 <?php echo "<td style='width:25%'>".$docnum['document_number']."</td>";?>
			 </tr>
			 </table>
			<table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Test Details </b></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			 <td style="width:25%;" class="boxcolor">Test Date</td>
			 <?php echo "<td style='width:25%'>".$row['date_of_Assessment']."</td>";?>
			 <td style="width:25%;" class="boxcolor" >Conducted By</td>
			 <?php echo "<td style='width:25%'>".$row['conducted_by']."</td>";?>	
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			 <td style="width:15%;" class="boxcolor">Aptitude Test Marks</td>
			 <?php echo "<td style='width:15%'>".$row['Marks_obtained']."</td>";?>
			 <td style="width:10%;" class="boxcolor">Out of</td>
			 <?php echo "<td style='width:10%'>".$row['Max_Score']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Test Percentage</td>
			 <?php echo "<td style='width:25%'>".$row['percent']."</td>";?>
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			 <td style="width:25%;" class="boxcolor">Typing Net Speed</td>
			 <?php echo "<td style='width:25%'>".$row['typing_speed']."</td>";?>
			 <td style="width:25%;" class="boxcolor">Typing Accuracy</td>
			 <?php echo "<td style='width:25%'>".$row['typing_accuracy']."</td>";?>	
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			 <td style="width:100%" class="boxcolor">Comments</td>
			</tr>
			<tr>
			 <?php echo "<td style='width:100%'></td>";?>
			<tr>
			</table>
			<table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Bank Information</b></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr class="boxcolor">
				<td style="width:25%"><b>Bank Name</b></td>
				<td style="width:20%"><b>Account Number</b></td>
				<td style="width:15%"><b>Branch</b></td>
				<td style="width:15%"><b>IFSC Code</b></td>
			</tr>
			<tr>
				<?php echo "<td style='width:25%'>".$row['PF_Number']."</td>";?>
				<?php echo "<td style='width:25%'>".$row['Account_Number']."</td>";?>
				<?php echo "<td style='width:25%'>".$row['Branch']."</td>";?>
				<?php echo "<td style='width:25%'>".$row['IFSC_Code']."</td>";?>
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Appraisals</b></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr class="boxcolor">
				<td style="width:10%"><b>Date</b></td>
				<td style="width:20%"><b>Description</b></td>
				<td style="width:15%"><b>Designation</b></td>
				<td style="width:15%"><b>Function</b></td>
				<td style="width:15%"><b>Department</b></td>
				<td style="width:10%"><b>Level</b></td>
				<td style="width:15%"><b>Project</b></td>
				<td style="width:15%"><b>CTC Annum</b></td>
			</tr>
			<tr class="boxcolor">
				<td style="width:10%"></td>
				<td style="width:20%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:10%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
			</tr>
			<tr >
				<td style="width:10%"></td>
				<td style="width:20%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:10%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
			</tr>
			<tr>
				<td style="width:10%"></td>
				<td style="width:20%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
				<td style="width:10%"></td>
				<td style="width:15%"></td>
				<td style="width:15%"></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Resignation</b></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr class="boxcolor">
				<td style="width:25%">Resignation Date</td>
				<td style="width:25%">Nature Of Leaving</td>
				<td style="width:25%">Attrition Type</td>
				<td style="width:25%">Reason</td>
			</tr>
			<tr>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
			</tr>
			<tr>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
				<td style="width:25%"></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr>
			<td style="width:100%" class="boxcolor"><b>Resignation reason in detail</b></td>
			</tr>
			<tr>
			<td style="width:100%"></td>
			</tr>
			</table>
			<table style="width:100%">
			<tr class="boxcolor">
				<?php echo "<td style='width:100%'></td>";?>
			</tr>
			</table>
			<table style="width:100%">	
			<tr>
				<td style="width:25%;border-bottom:none;" class="boxcolor">Details Provided by</td>
				<td style="width:25%" class="boxcolor">Name</td>
				<?php echo "<td style='width:100%'>".$row['name']."</td>";?>
			</tr>
			<tr>
				<td style="width:25%;border-top:none;" class="boxcolor"></td>
				<td style="width:25%" class="boxcolor">Designation</td>
				<?php echo "<td style='width:100%'>".$row1['designation']."</td>";?>
			</tr>
			</table>
		  </table>
			</tbody>
		</table>	
		</div>	
		<div class="box-footer">
		   <h6 style="text-align: center;">Acurus Solutions Private Limited, Chennai</h6>
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

 