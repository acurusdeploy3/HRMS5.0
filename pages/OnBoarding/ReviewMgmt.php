<?php   
session_start();  
$userid=$_SESSION['login_user'];
$_SESSION['From_Page'] = "ReviewMgmt.php";
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
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$month= date("M-Y");
$usergrp=$_SESSION['login_user_group'];
$queryemplst = mysqli_query($db,"select PMS_Master_ID, m.Employee_ID, m.Department,  Attendance_Percentage, m.Is_Extended, m.Reason_For_Extension, Manager_Comments,  Year_ID, Process_Queue_ID, Pending_Queue_ID, m.Is_active,  pms_id_val,
concat(m.hod_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,  concat(m.reporting_manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,date_format(concat(substring_index(date_add(m.review_to_date,interval 2 month),'-',2),'-01'),'%d %b %Y') as eff_date,date_format(d.date_joined,'%d %b %Y') as date_joined,date_format(m.Review_From_Date,'%d %b %Y') as Review_From_Date,date_format(m.Review_To_Date,'%d %b %Y') as Review_To_Date,
concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name,d.Employee_image,d.employee_designation
from pms_master m 
inner join employee_details d on m.employee_id = d.employee_id   
inner join employee_details d1 on m.reporting_manager_id = d1.employee_id
inner join employee_details d2 on m.hod_id = d2.employee_id
where d.is_active='Y' and month_id=month(now()) and  year_id = (select year_id from year_lookup where years= year(now())) order by m.employee_id");
$queryextlst = mysqli_query($db,"select PMS_Extended_ID, m.Employee_ID, m.Reason_For_Extension, New_Review_To_Date,Attendance_Percentage,m1.department,m.pms_id_val,m.pms_id_val,concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name,
concat(m1.hod_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,
concat(m1.reporting_manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,date_format(m1.Review_From_Date,'%d %b %Y') as Review_From_Date,date_format(m1.Review_To_Date,'%d %b %Y') as Review_To_Date,
date_format(d.date_joined,'%d %b %Y') as date_joined,d.Employee_image,d.employee_designation
from pms_extended_list m 
inner join employee_details d on m.employee_id = d.employee_id
inner join pms_master m1 on m.pms_master_id=m1.pms_master_id 
inner join employee_details d1 on m1.reporting_manager_id = d1.employee_id
inner join employee_details d2 on m1.hod_id = d2.employee_id
where d.is_active='Y' and m.month_id=month(now()) and  m.year_id = (select year_id from year_lookup where years= year(now()))");
$querymgrextlst = mysqli_query($db,"select PMS_Master_ID, m.Employee_ID, m.Department,  Attendance_Percentage, m.Is_Extended, m.Reason_For_Extension, Manager_Comments,  Year_ID, Process_Queue_ID, Pending_Queue_ID, m.Is_active,  pms_id_val,Recommended_Next_Review_Month ,
concat(m.hod_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,  concat(m.reporting_manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,date_format(concat(substring_index(date_add(m.review_to_date,interval 2 month),'-',2),'-01'),'%d %b %Y') as eff_date,date_format(d.date_joined,'%d %b %Y') as date_joined,date_format(m.Review_From_Date,'%d %b %Y') as Review_From_Date,date_format(m.Review_To_Date,'%d %b %Y') as Review_To_Date,
concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name,d.Employee_image,d.employee_designation
from pms_master m 
inner join employee_details d on m.employee_id = d.employee_id 
inner join employee_details d1 on m.reporting_manager_id = d1.employee_id
inner join employee_details d2 on m.hod_id = d2.employee_id
where process_queue_id='11' and m.is_active='Y' and d.is_active='Y' and m.month_id=month(now()) and  m.year_id = (select year_id from year_lookup where years= year(now()))");
$queryactlst = mysqli_query($db,"select PMS_Master_ID, m.Employee_ID, m.Department, Attendance_Percentage, m.Is_Extended, m.Reason_For_Extension, Manager_Comments, Year_ID, m.Process_Queue_ID,Process_Queue_Name, Pending_Queue_ID, m.Is_active, p.process_status,
concat(m.hod_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,  concat(m.reporting_manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,date_format(concat(substring_index(date_add(m.review_to_date,interval 2 month),'-',2),'-01'),'%d %b %Y') as eff_date,date_format(d.date_joined,'%d %b %Y') as date_joined,date_format(m.Review_From_Date,'%d %b %Y') as Review_From_Date,date_format(m.Review_To_Date,'%d %b %Y') as Review_To_Date,
concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name, pms_id_val ,d.Employee_image,d.employee_designation
from pms_master m 
inner join employee_details d on m.employee_id = d.employee_id   
inner join employee_details d1 on m.reporting_manager_id = d1.employee_id
inner join employee_details d2 on m.hod_id = d2.employee_id
inner join process_queue_table p on m.process_queue_id=p.process_queue_id 
where m.process_queue_id not in (11,12) and m.is_active='Y' order by year_id,month_id,employee_id");
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_Name) as Name,Job_Role,Employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['Employee_image'];

?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link rel="icon" href="images\fevicon.png" type="image/gif" sizes="16x16">
  <title>Performance Review</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->  
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="dist/css/w3.css">
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
  <script src="dist/js/loader.js"></script>
  

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]><![endif]-->
  <script src=""></script>
  
<style>
.table table-striped:hover {
  background-color: #ffff99;
}

	.error {color: #FF0000;}
.fa-fw {
    padding-top: 13px;
}
#faicon
{
    font-size: 30px ! important;
    color: #31607c ! important;
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
#btnhistory{
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
#headerstyle {
background-color: teal;
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
    width: 55%;
}
/* The Close Button 1  */

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
/* The Close Button 2  */

.close13 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close13:hover,
.close13:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.close123 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close123:hover,
.close123:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
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
       Performance Management
      </h1>
    </section>
	<section class="content">
      <div class="row">
        
        <!-- right column -->
        <div class="col-md-11">
          <!-- Horizontal Form -->
          <div class="box box-info" style="width:110%;">
            <div class="box-header with-border">
              <h3 class="box-title">REVIEW INFORMATION</h3>
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
                   <input action="action" class="btn btn-info pull-left" onclick="window.location='../../DashboardFinal.php';" type="button" value="Back" id="goprevious"/>                
				<!-- <input type= "reset" class="btn btn-info pull-left" value= "Clear" style = "background-color: #da3047;margin-left: 7px;border-color:#da3047;" id="clearfields" onclick="clearfields();"> 	
				<input type="button" class="btn btn-info pull-right" value= "Finish"
					id="gonext" style = "margin-right: 7px;" >-->
              </div>
              <!-- /.box-footer -->			  		  
           <div id="myCarousel" class="carousel slide" data-interval="false" data-ride="carousel">
		   <div class="carousel-inner">
           <?php
				$y=12;
				while ($y>0){
					$monthquery = mysqli_query($db,"select date_format(last_day(date_sub(date(now()), interval '$y' month)),'%d-%b-%Y') as btnvalue,date_format(date_sub(date(now()), interval '$y' month),'%M-%Y') as btnname");		
                	$row8 = mysqli_fetch_assoc($monthquery);
					$reviewvalue = $row8['btnvalue'];
                $criteriacatmaster1 ="select a.*,(SELECT round(avg((present*100)/working_days),2) as Attendance_Percentage
FROM `pms_summary` inner join year_lookup on year(now())=years
where `from` between a.startdate and a.todate and employee_id=a.Employee_ID
) as Attendance_Percentage

from (select PMS_Master_ID,m.Employee_ID,
pm.Is_Extended,pm.Reason_For_Extension,
pm.Manager_Comments, pm.Year_ID,pm.Process_Queue_ID,pm.Pending_Queue_ID, pm.Is_active,pm.pms_id_val,d.Department,
concat(a.employee_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,
concat(p.manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,
date_format(concat(substring_index(date_add(m.review_to_date,interval 2 month),'-',2),'-01'),'%d %b %Y') as eff_date,
date_format(d.date_joined,'%d %b %Y') as date_joined,
date_format(m.Review_From_Date,'%d %b %Y') as Review_From_Date
,d.Employee_image,d.employee_designation,
date_format(m.Review_To_Date,'%d %b %Y') as Review_To_Date,
date_format(m.Review_From_Date,'%d-%M-%Y')  as startdate,
date_format(m.Review_To_Date,'%d-%M-%Y') as todate,
concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name
FROM `employee_performance_review_dates` m
inner join employee_details d on m.employee_id = d.employee_id
inner join pms_manager_lookup p on m.employee_id=p.employee_id
inner join employee_details d1 on p.manager_id = d1.employee_id
inner join all_hods h on d.business_unit=h.location
inner join all_hods a on d.department=a.department
inner join employee_details d2 on a.employee_id = d2.employee_id
left join pms_master pm on m.employee_id=pm.employee_id and m.review_from_date=pm.review_from_date and m.review_to_date=pm.review_to_date
where m.review_from_date <=DATE_FORMAT(date_sub(date_sub(date(now()) , interval 1 month),interval 1 year),'%Y-%m-01')
and d.is_active='Y'
order by date_format(m.review_from_date,'%Y-%m-%d'))as a
where review_to_date like date_format(date_sub(str_to_date(concat('$reviewvalue'),'%d-%b-%Y'),interval 1 month),'%M - %Y')
order by a.employee_id";
					$chkcriteriacatmaster1=MySQLi_query($db,$criteriacatmaster1);
					$cnt = mysqli_num_rows($chkcriteriacatmaster1);
					if(mysqli_num_rows($chkcriteriacatmaster1) > 1)
					{?>
					<div class="item">
					<div class="border-class" id="tablelst">
                    <?php 
                    	$rev =explode("-",$reviewvalue);
                    	$newVal =  $rev[1].'-'.$rev[2];
                    	//echo $newVal;
                    ?>
					<h4 style="margin-left: 7px;font-weight: bold;color: black;">EMPLOYEE LIST AS OF <?php echo strtoupper($newVal); ?></h4>
					<table class="table" id="lsttable" name="lsttable" style="font-size:14px;">
					<tbody>
						<tr>
							<th id="headerstyle" style="width: 6%;text-align: right;">#</th>
							<th id="headerstyle">Employee</th>
							<th id="headerstyle"></th>
							<th id="headerstyle">Department</th> 
						</tr>
                 
                    <?php
						$i = 1;
						while($row1 = mysqli_fetch_assoc($chkcriteriacatmaster1)){
							echo "<tr><td style='width:1%;text-align: right;'>".$i.".</td>";
							echo "<input type='hidden' class='EmpId' value=".$row1['Employee_ID']."></input>";
                      		echo "<input type='hidden' class='EmpName' value='".$row1['empl_name']."'></input>";
							echo "<input type='hidden' class='EmpDes' value='".$row1['employee_designation']."'></input>";
							$ProfPic = $row1['Employee_image'];
							$profPicPath = '../../uploads/'.$ProfPic;
							echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
							echo "<input type='hidden' class='Period' value='".$row1['Review_From_Date']." to ".$row1['Review_To_Date']. "'></input>";
							echo "<input type='hidden' class='PMSId' value=".$row1['pms_id_val']."></input>";
							echo "<input type='hidden' class='Att' value=".$row1['Attendance_Percentage']."></input>";
							echo "<input type='hidden' class='Dept' value='".$row1['date_joined']."'></input>";
							echo "<input type='hidden' class='rept' value='".$row1['reporting_manager_id']."'></input>";
							echo "<input type='hidden' class='hodi' value='".$row1['hod_id']."'></input>";
							echo "<input type='hidden' class='effdate' value='".$row1['eff_date']."'></input>";
							echo "<td style='width:20%'>".$row1['Employee_ID']."-".$row1['empl_name']."</td>";
							echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
							echo "<td style='width:15%'>".$row1['Department']."</td>";
							echo "</tr>" ;
						$i++;
						}
						?>
					</tbody>
					</table>
					</div>
					</div>					
				<?php } 
				$y--;} ?>
           		   <div class="item active">
		  <div class="border-class" id="tablelst">
		  <h4 style="margin-left: 7px;font-weight: bold;color: black;">EMPLOYEE LIST AS OF <?php echo strtoupper($month); ?></h4>
		  <table class="table" id="lsttable" name="lsttable" style="font-size:14px;">
              <tbody>
                <tr>
                  <th id="headerstyle" style="width: 6%;text-align: right;">#</th>
                  <th id="headerstyle">Employee</th>
                  <th id="headerstyle"></th>
					<th id="headerstyle">Department</th> 
                </tr>
                <?php
                if(mysqli_num_rows($queryemplst) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row = mysqli_fetch_assoc($queryemplst)){
                    echo "<tr><td style='width:5%;text-align: right;'>".$i.".</td>";
					echo "<input type='hidden' class='EmpId' value=".$row['Employee_ID']."></input>";
                  echo "<input type='hidden' class='EmpName' value='".$row['empl_name']."'></input>";
							echo "<input type='hidden' class='EmpDes' value='".$row['employee_designation']."'></input>";
							$ProfPic = $row['Employee_image'];
							$profPicPath = '../../uploads/'.$ProfPic;
							echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
					echo "<input type='hidden' class='Period' value='".$row['Review_From_Date']." to ".$row['Review_To_Date']. "'></input>";
					echo "<input type='hidden' class='PMSId' value=".$row['pms_id_val']."></input>";
					echo "<input type='hidden' class='Att' value=".$row['Attendance_Percentage']."></input>";
					echo "<input type='hidden' class='Dept' value='".$row['date_joined']."'></input>";
					echo "<input type='hidden' class='rept' value='".$row['reporting_manager_id']."'></input>";
					echo "<input type='hidden' class='hodi' value='".$row['hod_id']."'></input>";
					echo "<input type='hidden' class='effdate' value='".$row['eff_date']."'></input>";
                    echo "<td style='width:20%'>".$row['Employee_ID']."-".$row['empl_name']."</td>";
					echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
                    echo "<td style='width:15%'>".$row['Department']."</td>";
					echo "</tr>" ;
				$i++;
					}
                  }
                ?>
              </tbody>
            </table>
          </div>
          </div>
		  <?php
				$x=1;
				while ($x<7){
					$monthquery = mysqli_query($db,"select date_format(date_add(date(now()), interval '$x' month),'%b-%Y') as btnvalue,date_format(date_add(date(now()), interval '$x' month),'%M-%Y') as btnname ");
					$row7 = mysqli_fetch_assoc($monthquery);
					$reviewvalue = $row7['btnvalue'];
					$criteriacatmaster = "select * from (Select PMS_Master_ID,m.Employee_ID,d.employee_designation,d.Employee_image,Attendance_Percentage, pm.Is_Extended,pm.Reason_For_Extension,pm.Manager_Comments, pm.Year_ID,pm.Process_Queue_ID,pm.Pending_Queue_ID, pm.Is_active,pm.pms_id_val,d.Department,concat(a.employee_id,'-',d2.First_Name,' ',d2.MI,' ',d2.Last_Name) as hod_id,concat(p.manager_id,'-',d1.First_Name,' ',d1.MI,' ',d1.Last_Name) as reporting_manager_id,date_format(concat(substring_index(date_add(m.review_to_date,interval 2 month),'-',2),'-01'),'%d %b %Y') as eff_date,date_format(d.date_joined,'%d %b %Y') as date_joined,date_format(m.Review_From_Date,'%d %b %Y') as Review_From_Date,date_format(m.Review_To_Date,'%d %b %Y') as Review_To_Date,concat(d.First_Name,' ',d.MI,' ',d.Last_Name) as empl_name FROM `employee_performance_review_dates` m
					inner join employee_details d on m.employee_id = d.employee_id
					inner join pms_manager_lookup p on m.employee_id=p.employee_id
					inner join employee_details d1 on p.manager_id = d1.employee_id
                    inner join all_hods h on d.business_unit=h.location
					inner join all_hods a on d.department=a.department
					inner join employee_details d2 on a.employee_id = d2.employee_id
					left join pms_master pm on m.employee_id=pm.employee_id and m.review_from_date=pm.review_from_date
					and m.review_to_date=pm.review_to_date
					where m.review_from_date >= DATE_FORMAT(date_sub(date_add(date(now()) , interval  1 month),interval 1 year),'%Y-%m-01') and m.review_to_date <= date_add(date(now()), interval 6 month) and d.is_active='Y' order by date_format(m.review_from_date,'%Y-%m-%d'))as a where review_from_date like date_format(date_sub(str_to_date(concat('01-','$reviewvalue'),'%d-%b-%Y'),interval 1 year),'%d %b %Y') order by a.employee_id";
					$chkcriteriacatmaster=MySQLi_query($db,$criteriacatmaster);
					$cnt = mysqli_num_rows($chkcriteriacatmaster);
					if(mysqli_num_rows($chkcriteriacatmaster) < 1)
					{?>
					<div class="item">
					<div class="border-class" id="tablelst">
					<h4 style="margin-left: 7px;font-weight: bold;color: black;">EMPLOYEE LIST AS OF <?php echo strtoupper($reviewvalue); ?></h4>
					<table class="table" id="lsttable" name="lsttable" style="font-size:14px;">
					<tbody>
						<tr>
							<th id="headerstyle" style="width: 6%;text-align: right;">#</th>
							<th id="headerstyle">Employee</th>
							<th id="headerstyle"></th>
							<th id="headerstyle">Department</th> 
						</tr>
						<tr>
							<td colspan="4">No Results Found </td>
						</tr>
					</tbody>
					</table>
					</div>
					</div>					
					<?php }
					else
					{
						$k=1;
						while ($Result = MySQLi_fetch_array($chkcriteriacatmaster)) {
					?>
					<div class="item">
					<div class="border-class" id="tablelst">
					<h4 style="margin-left: 7px;font-weight: bold;color: black;">EMPLOYEE LIST AS OF <?php echo strtoupper($reviewvalue); ?></h4>
					<table class="table" id="lsttable" name="lsttable" style="font-size:14px;">
					<tbody>
					<tr>
						<th id="headerstyle" style="width: 6%;text-align: right;">#</th>
						<th id="headerstyle">Employee</th>
						<th id="headerstyle"></th>
						<th id="headerstyle">Department</th> 
					</tr>
					<?php
						if(mysqli_num_rows($chkcriteriacatmaster) < 1){
						echo "<tr><td cols-span='4'> No Results Found </td></tr>";
						}else{
						$i = 1;
						while($row = mysqli_fetch_assoc($chkcriteriacatmaster)){
							echo "<tr><td style='width:1%;text-align: right;'>".$i.".</td>";
                        echo "<input type='hidden' class='EmpName' value='".$row['empl_name']."'></input>";
							echo "<input type='hidden' class='EmpDes' value='".$row['employee_designation']."'></input>";
							$ProfPic = $row['Employee_image'];
							$profPicPath = '../../uploads/'.$ProfPic;
							echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
							echo "<input type='hidden' class='EmpId' value=".$row['Employee_ID']."></input>";
							echo "<input type='hidden' class='Period' value='".$row['Review_From_Date']." to ".$row['Review_To_Date']. "'></input>";
							echo "<input type='hidden' class='PMSId' value=".$row['pms_id_val']."></input>";
							echo "<input type='hidden' class='Att' value=".$row['Attendance_Percentage']."></input>";
							echo "<input type='hidden' class='Dept' value='".$row['date_joined']."'></input>";
							echo "<input type='hidden' class='rept' value='".$row['reporting_manager_id']."'></input>";
							echo "<input type='hidden' class='hodi' value='".$row['hod_id']."'></input>";
							echo "<input type='hidden' class='effdate' value='".$row['eff_date']."'></input>";
							echo "<td style='width:20%'>".$row['Employee_ID']."-".$row['empl_name']."</td>";
							echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
							echo "<td style='width:15%'>".$row['Department']."</td>";
							echo "</tr>" ;
						$i++;
						}
						}
						?>
					</tbody>
					</table>
				</div>
				</div>
				<?php 
					}
					}
				?>			
				<?php 
					$x++;
				}
				?>
          </div>
		<a class="left carousel-control" href="#myCarousel" data-slide="prev" style="width:4%;    background-image: none ! important;">
			<span class="glyphicon glyphicon-chevron-left" style="color: #21515f;"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#myCarousel" data-slide="next" style="width: 4%;    background-image: none ! important;">
			<span class="glyphicon glyphicon-chevron-right" style="color: #21515f;"></span>
			<span class="sr-only">Next</span>
		</a>
          </div>
		  <br><br><br>
		  <div class="border-class">
		  <h4 style="margin-left: 7px;font-weight: bold;color:black">ACTIVE REVIEWS</h4>
		  <table class="table" id="lstacttable" name="lstacttable" style="font-size:14px;">
              <tbody>
                <tr>
                  <th id="headerstyle" style="width: 10px;">#</th>
                  <th id="headerstyle">Employee Name</th>
                  <th id="headerstyle"></th>
					<th id="headerstyle">Department</th>
					<th id="headerstyle">Review Status</th>
					<th id="headerstyle">View/Edit</th>
                </tr>
                <?php
                if(mysqli_num_rows($queryactlst) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row3 = mysqli_fetch_assoc($queryactlst)){
                    echo "<tr><td style='width:1%; text-align='right;'>".$i.".</td>";
					echo "<input type='hidden' class='EmpId' value=".$row3['Employee_ID']."></input>";
					echo "<input type='hidden' class='Period' value='".$row3['Review_From_Date']." to ".$row3['Review_To_Date']. "'></input>";
					echo "<input type='hidden' class='PMSId' value=".$row3['pms_id_val']."></input>";
					echo "<input type='hidden' class='Att' value=".$row3['Attendance_Percentage']."></input>";
					echo "<input type='hidden' class='Dept' value='".$row3['date_joined']."'></input>";
					echo "<input type='hidden' class='rept' value='".$row3['reporting_manager_id']."'></input>";
					echo "<input type='hidden' class='hodi' value='".$row3['hod_id']."'></input>";
                  	echo "<input type='hidden' class='EmpName' value='".$row3['empl_name']."'></input>";
					echo "<input type='hidden' class='EmpDes' value='".$row3['employee_designation']."'></input>";
					$ProfPic = $row3['Employee_image'];
					$profPicPath = '../../uploads/'.$ProfPic;
					echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
					echo "<input type='hidden' class='effdate' value='".$row3['eff_date']."'></input>";
                    echo "<td style='width:20%'>".$row3['Employee_ID']."-".$row3['empl_name']."</td>";
					echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
                    echo "<td style='width:20%'>".$row3['Department']."</td>";
					echo "<td style='width:20%'>".$row3['process_status']."</td>";
					if($row3['Process_Queue_ID']=='6')
					{
						echo "<td><a href='EditEmpDeliverablesCulture.php?pms_id_val=".$row3['pms_id_val']."'  title='Edit'><span class='glyphicon glyphicon-pencil'> </span></a></td>";
					}
					else if($row3['Process_Queue_ID']!='1' && $row3['Process_Queue_ID']!='6'){
					echo "<td style='width:10%'></td>";
					}
					else {
						echo "<td style='width:10%'></td>";
					}
					$i++;
					}
                  }
                ?>
              </tbody>
            </table>
          </div>	
		  <br><br><br>
		  <div class="border-class">
		  <h4 style="margin-left: 7px;font-weight: bold;color:black;">EXTENSION OF APPRAISAL PERIOD - MANAGER REQUEST</h4>
		  		  <table class="table" id="lstreqtable" name="lstreqtable" style="font-size:14px;">
              <tbody>
                <tr>
                  <th id="headerstyle" style="width: 10px;">#</th>
                  <th id="headerstyle">Employee</th>
				  <th id="headerstyle"></th>
					<th id="headerstyle">Department</th>      
					<th id="headerstyle">Manager Comments</th>
					<th id="headerstyle">Recommended Next Review Month</th>
					<th id="headerstyle">Next Review Date</th>
					
                </tr>
                <?php
                if(mysqli_num_rows($querymgrextlst) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row2 = mysqli_fetch_assoc($querymgrextlst)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
                    echo "<input type='hidden' class='EmpId' value=".$row2['Employee_ID']."></input>";
					echo "<input type='hidden' class='Period' value='".$row2['Review_From_Date']." to ".$row2['Review_To_Date']. "'></input>";
					echo "<input type='hidden' class='PMSId' value=".$row2['pms_id_val']."></input>";
					echo "<input type='hidden' class='Att' value=".$row2['Attendance_Percentage']."></input>";
					echo "<input type='hidden' class='Dept' value='".$row2['date_joined']."'></input>";
                  echo "<input type='hidden' class='EmpName' value='".$row2['empl_name']."'></input>";
					echo "<input type='hidden' class='EmpDes' value='".$row2['employee_designation']."'></input>";
					$ProfPic = $row2['Employee_image'];
					$profPicPath = '../../uploads/'.$ProfPic;
					echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
					echo "<input type='hidden' class='rept' value='".$row2['reporting_manager_id']."'></input>";
					echo "<input type='hidden' class='hodi' value='".$row2['hod_id']."'></input>";
					echo "<input type='hidden' class='effdate' value='".$row2['eff_date']."'></input>";
                    echo "<td style='width:20%'>".$row2['Employee_ID']."-".$row2['empl_name']."</td>";
					echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
                    echo "<td style='width:20%'>".$row2['Department']."</td>";
					echo "<td style='width:20%'>".$row2['Manager_Comments']."</td>";
					echo "<td style='width:20%'>".$row2['Recommended_Next_Review_Month']."</td>";
					echo "<td style='width:5%'><a href='#' id='myBtn2'  data-toggle='modal' data-target='#myextModal_".$emplid1."'><i class='fa fa-calendar' aria-hidden='true' style='font-size: large;font-weight: 500;'></i></a></td>"; ?>
					<div id="myextModal_<?php echo $emplid1; ?>" class="modal">
			<!-- Modal content -->
				<div class="modal-content">
					<span class="close13" data-dismiss="modal">&times;</span>
						<p></p>
						<form id="extensionperiod" method="POST" action="UpdateExtension.php">
						<input type="hidden" value = "<?php echo $emplid1; ?>" name="EmpIdvalue" id="EmpIdvalue"/>
						<label for="Allocation" class="col-sm-5 control-label">Update Next Review Date</label><br><br>
						<?php

						echo "<div class='form-group'>";
						echo "<div class='form-group'><label class='col-sm-4 control-label'>Next Review Date<span class='error'>*  </span></label>";
						echo "<div class='col-sm-6'><input type='Text' id='nxtdate' placeholder='YYYY-MM-dd'  class='form-control' name='nxtdate' autocomplete='off' required></input></div><br><br>";
						echo "<input type= 'submit' name= 'submit' id='setextbtn' class = 'btn btn-primary pull-right' value = 'Save'></input></div>";?>
					<br>					
						</form>
							</div>
			</div>
					<?php echo "</tr>";
					$i++;
					}
                  }
                ?>
              </tbody>
            </table>
          </div>
		  <br><br><br>
		   <div class="border-class">
		   <h4 style="margin-left: 7px;font-weight: bold;color:black;">LIST OF EMPLOYEES - PMS EXTENDED</h4>
		  <table class="table" id="lstexttable" name="lstexttable" style="font-size:14px;">
              <tbody>
                <tr>
                  <th id="headerstyle" style="width: 10px">#</th>
                  <th id="headerstyle">Employee</th>
                  <th id="headerstyle"></th>
					<th id="headerstyle">Department</th>    
					<th id="headerstyle">Reason For Extension</th>	
					<th id="headerstyle">Next Review Date</th>
                </tr>
                <?php
                if(mysqli_num_rows($queryextlst) < 1){
                  echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
                  while($row1 = mysqli_fetch_assoc($queryextlst)){
                    echo "<tr><td style='width:1%'>".$i.".</td>";
                    echo "<input type='hidden' class='EmpId' value=".$row1['Employee_ID']."></input>";
					echo "<input type='hidden' class='Period' value='".$row1['Review_From_Date']." to ".$row1['Review_To_Date']. "'></input>";
					echo "<input type='hidden' class='PMSId' value=".$row1['pms_id_val']."></input>";
					echo "<input type='hidden' class='Att' value=".$row1['Attendance_Percentage']."></input>";
					echo "<input type='hidden' class='Dept' value='".$row1['date_joined']."'></input>";
					echo "<input type='hidden' class='rept' value='".$row1['reporting_manager_id']."'></input>";
					echo "<input type='hidden' class='hodi' value='".$row1['hod_id']."'></input>";
					echo "<input type='hidden' class='effdate' value='".$row1['eff_date']."'></input>";
                  echo "<input type='hidden' class='EmpName' value='".$row1['empl_name']."'></input>";
					echo "<input type='hidden' class='EmpDes' value='".$row1['employee_designation']."'></input>";
					$ProfPic = $row1['Employee_image'];
					$profPicPath = '../../uploads/'.$ProfPic;
					echo "<input type='hidden' class='EmpImg' value=".$profPicPath."></input>";
                    echo "<td style='width:20%'>".$row1['Employee_ID']."-".$row1['empl_name']."</td>";
					echo "<td style='width:5%'><a href='#' title='View Employee Data' id='empldata' data-toggle='modal' data-target='#datamodel'> <i class='fa fa-info-circle' aria-hidden='true'></i></td>";
                    echo "<td style='width:15%'>".$row1['department']."</td>";
					echo "<td style='width:20%'>".$row1['Reason_For_Extension']."</td>";
					echo "<td style='width:20%'>".$row1['New_Review_To_Date']."</td>";
                    $i++;
				  }
                  }
                ?>
              </tbody>
            </table>
          </div>
		  		<div id="datamodel" class="modal">
				<div class="modal-content">
					<span class="close123" data-dismiss="modal">&times;</span>
					<p style="font-size: 22px;color: #eb6027;">DETAILS</p>
					<div class="box box-widget widget-user-2">
						<div class="widget-user-header bg-yellow">
							<div class="widget-user-image">
								<img class="img-circle" name="EmpImg" id="EmpImg"  alt="User Avatar">
							</div>
							<h3 class="widget-user-username">
							<span name="EmpName" id="EmpName"></span></h3>
							<h3 class="widget-user-username" style="font-size: 20px ! important">
							<span name="EmpDes" id="EmpDes"></span></h3>
							
						</div>
						<div class="box-footer no-padding">
							<ul class="nav nav-stacked">
								<li><a href="#">Date of joining<span class="pull-right" style="color: black !important;font-weight: 800;" name="Dept" id="Dept" ></span></a></li>
								<li><a href="#">Attendance %<span class="pull-right" style="color: black !important;font-weight: 800;" name="Att" id="Att"></span></a></li>
								<li><a href="#">Review period<span class="pull-right" style="color: black !important;font-weight: 800;" name="Period" id="Period"></span></a></li>
								<li><a href="#">Manager<span class="pull-right" style="color: black !important;font-weight: 800;" name="rept" id="rept"></span></a></li>
								<li><a href="#">HOD<span class="pull-right" style="color: black !important;font-weight: 800;" name="hodi" id="hodi"></span></a></li>
								<li><a href="#">Date Effective<span class="pull-right" style="color: black  !important;font-weight: 800;"name="effdate" id="effdate"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
				</div>
				</div>
          </div>
          <!-- /.box -->
		   </div>
		   
		      </div>
			  </div>
      <!-- /.row -->
       </section>		  

 
        </div>
 
   
  
  <!-- Content Wrapper. Contains page content -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong><a href="#">Acurus Solutions Private Limited1</a>.</strong>
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
<script type="text/javascript">
function showval(that) {
	   var reviewvalue= that;
           $.ajax({
               type: "POST",
               url: "EmployeeList.php",
               data: {
                   reviewvalue: reviewvalue,
               },
               success: function(html) {
                   $("#tablelst").html(html).show();
               }
           });
}
</script>
 <script>
  $(function() {
  $("#nxtdate,#datepicker1").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});	</script>

	<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close12")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
	$('.modal-backdrop').remove();
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
var modal1 = document.getElementById('myextModal');

// Get the button that opens the modal
var btn1 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span1 = document.getElementsByClassName("close13")[0];

// When the user clicks the button, open the modal 
btn1.onclick = function() {
    modal1.style.display = "block";
	$('.modal-backdrop').remove();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal1) {
        modal1.style.display = "none";
    }
}
</script>


<script>
$("#setextbtn").click(function() {
	if(document.getElementById('nxtdate').value =='')
	{}	
else
	{
	ajaxindicatorstart("Processing..Please Wait..");
	}
});
	</script>

<script>
$(function() {
  var bid, trid;
  $('#lsttable tr').click(function() {
       EmpIdvalue = $(this).find('.EmpId').val();
       Period = $(this).find('.Period').val();
       Att = $(this).find('.Att').val();
       Dept = $(this).find('.Dept').val();
       rept = $(this).find('.rept').val();
       hodi = $(this).find('.hodi').val();
       effdate = $(this).find('.effdate').val();
  
	    EmpDes = $(this).find(".EmpDes").val();
       EmpName = $(this).find(".EmpName").val();
       EmpImg = $(this).find(".EmpImg").val();
	   if(effdate=='')
	   {
		   effdate='NIL';
		   $("#effdate").css("color", "red");
	   }
       PmsIdvalue = $(this).find(".PMSId").val();
		$('#EmpIdvalue').val(EmpIdvalue);
		$('#Period').text(Period);
		$('#Att').text(Att);
		$('#Dept').text(Dept);
		$('#rept').text(rept);
		$('#hodi').text(hodi);
		$('#effdate').text(effdate);
		$('#PmsIdvalue').val(PmsIdvalue);
  
		$('#EmpDes').text(EmpDes);
		$('#EmpName').text(EmpName);
		 $('#EmpImg').attr("src",EmpImg );
  });
});
</script>
<script>
$(function() {
  var bid, trid;
  $('#lstacttable tr').click(function() {
       EmpIdvalue = $(this).find('.EmpId').val();
       Period = $(this).find('.Period').val();
       Att = $(this).find('.Att').val();
       Dept = $(this).find('.Dept').val();
       rept = $(this).find('.rept').val();
       hodi = $(this).find('.hodi').val();
       effdate = $(this).find('.effdate').val();
  
	    EmpDes = $(this).find(".EmpDes").val();
       EmpName = $(this).find(".EmpName").val();
       EmpImg = $(this).find(".EmpImg").val();
	   if(effdate=='')
	   {
		   effdate='NIL';
		   $("#effdate").css("color", "red");
	   }
       PmsIdvalue = $(this).find(".PMSId").val();
		$('#EmpIdvalue').val(EmpIdvalue);
		$('#Period').text(Period);
		$('#Att').text(Att);
		$('#Dept').text(Dept);
		$('#rept').text(rept);
		$('#hodi').text(hodi);
		$('#effdate').text(effdate);
		$('#PmsIdvalue').val(PmsIdvalue);
  
		$('#EmpDes').text(EmpDes);
		$('#EmpName').text(EmpName);
		 $('#EmpImg').attr("src",EmpImg );
  });
});
</script>
<script>
$(function() {
  var bid, trid;
  $('#lstexttable tr').click(function() {
       EmpIdvalue = $(this).find('.EmpId').val();
       Period = $(this).find('.Period').val();
       Att = $(this).find('.Att').val();
       Dept = $(this).find('.Dept').val();
       rept = $(this).find('.rept').val();
       hodi = $(this).find('.hodi').val();
       effdate = $(this).find('.effdate').val();
  
	    EmpDes = $(this).find(".EmpDes").val();
       EmpName = $(this).find(".EmpName").val();
       EmpImg = $(this).find(".EmpImg").val();
	   if(effdate=='')
	   {
		   effdate='NIL';
		   $("#effdate").css("color", "red");
	   }
       PmsIdvalue = $(this).find(".PMSId").val();
		$('#EmpIdvalue').val(EmpIdvalue);
		$('#Period').text(Period);
		$('#Att').text(Att);
		$('#Dept').text(Dept);
		$('#rept').text(rept);
		$('#hodi').text(hodi);
		$('#effdate').text(effdate);
		$('#PmsIdvalue').val(PmsIdvalue);
  
		$('#EmpDes').text(EmpDes);
		$('#EmpName').text(EmpName);
		 $('#EmpImg').attr("src",EmpImg );
  });
});
</script>
<script>
$(function() {
  var bid, trid;
  $('#lstreqtable tr').click(function() {
       EmpIdvalue = $(this).find('.EmpId').val();
       Period = $(this).find('.Period').val();
       Att = $(this).find('.Att').val();
       Dept = $(this).find('.Dept').val();
       rept = $(this).find('.rept').val();
       hodi = $(this).find('.hodi').val();
       effdate = $(this).find('.effdate').val();
  
	    EmpDes = $(this).find(".EmpDes").val();
       EmpName = $(this).find(".EmpName").val();
       EmpImg = $(this).find(".EmpImg").val();
	   if(effdate=='')
	   {
		   effdate='NIL';
		   $("#effdate").css("color", "red");
	   }
       PmsIdvalue = $(this).find(".PMSId").val();
		$('#EmpIdvalue').val(EmpIdvalue);
		$('#Period').text(Period);
		$('#Att').text(Att);
		$('#Dept').text(Dept);
		$('#rept').text(rept);
		$('#hodi').text(hodi);
		$('#effdate').text(effdate);
		$('#PmsIdvalue').val(PmsIdvalue);
  
		$('#EmpDes').text(EmpDes);
		$('#EmpName').text(EmpName);
		 $('#EmpImg').attr("src",EmpImg );
  });
});
</script>
<script>
	var checkitem = function() {
  var $this;
  $this = $("#myCarousel");
  if ($("#myCarousel .carousel-inner .item:first").hasClass("active")) {
    $this.children(".left").hide();
    $this.children(".right").show();
  } else if ($("#myCarousel .carousel-inner .item:last").hasClass("active")) {
    $this.children(".right").hide();
    $this.children(".left").show();
  } else {
    $this.children(".carousel-control").show();
  }
};

checkitem();

$("#myCarousel").on("slid.bs.carousel", "", checkitem);
	</script>
</body>
</html>
