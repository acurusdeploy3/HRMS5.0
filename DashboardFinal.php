<?php
include('config.php');
$userid = $_SESSION['login_user'];
$getPD = mysqli_query($db,"select * from employee_details where employee_id='$userid' and is_personal_data_filled='Y'");
if(mysqli_num_rows($getPD)>0)
{
?>
<?php

include('config.php');
session_start();
require_once('queries.php');
$userid = $_SESSION['login_user'];
$username =mysqli_query ($db," ");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$getDepartment = mysqli_query($db,"select count(employee_id) as emp_count,department from employee_details where department!='' and employee_id not in (3) and is_active='Y' group by department");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Acurus HRMS</title>
  <link rel="icon" href="Images\fevicon.png" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=0.30, maximum-scale=4.0, minimum-scale=0.25, user-scalable=yes" >
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Morris charts -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="dist/js/loader.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
  .date {
	display: block;
	width: 100px;
	height: 70px;
	margin: 0px auto;
	background: #fff;
	text-align: center;
	font-family: 'Helvetica', sans-serif;
	position: relative;
}

.date .binds {
	position: absolute;
	height: 15px;
	width: 60px;
	background: transparent;
	border: 2px solid #999;
	border-width: 0 5px;
	top: -6px;
	left: 0;
	right: 0;
	margin: auto;
}

.date .month {
	background: #3c8dbc;
	display: block;
	padding: 8px 0;
	color: #fff;
	font-size: 10px;
	font-weight: bold;
	border-bottom: 2px solid #333;
	box-shadow: inset 0 -1px 0 0 #666;
}

.date .day {
	display: block;
	margin: 0;
	padding: 10px 0;
	font-size: 14px;
	box-shadow: 0 0 3px #3c8dbc;
	position: relative;
}

.date .day::after {
	content: '';
	display: block;
	height: 100%;
	width: 96%;
	position: absolute;
	top: 3px;
	left: 2%;
	z-index: -1;
	box-shadow: 0 0 3px #ccc;
}

.date .day::before {
	content: '';
	display: block;
	height: 100%;
	width: 90%;
	position: absolute;
	top: 6px;
	left: 5%;
	z-index: -1;
	box-shadow: 0 0 3px #ccc;
}
  </style>
  <style>
#bdays
{
	max-height: 300px;
    overflow-y: scroll;
}
</style>
 <style>
#Holidays
{
	max-height: 470px;
    overflow-y: scroll;
}
</style>
 <style>
#TeamDetails
{
	max-height: 328px;
    overflow-y: scroll;
}
</style>
 <style>
#aTTDetails
{
	max-height: 420px;
    overflow-y: scroll;
}
</style>
 <style>
#PendingTasks
{
	max-height: 250px;
    overflow-y: scroll;
}
</style>
 <style>
#InfoTasks
{
	max-height: 290px;
    overflow-y: scroll;
}
</style>
 <style>
#announcements
{
	max-height: 250px;
    overflow-y: scroll;
}
</style>
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>
<style>
.no_birthday {
    background: #fff7ee;
    color: #d8740e;
    min-height: 25px;
    padding-top: 13px;
    padding-bottom: 9px;
    border: 1px solid #d3d3d3;
    text-align: center;
}
</style>
<style>
.main-header
{
    height:50px;
}
.content-wrapper
{
	max-height: 500px;
	overflow-y:scroll;
}
</style>
<script language="javascript">
i = 0
var speed = 1
function scroll() {
i = i + speed
var div = document.getElementById("announcements")
div.scrollTop = i
if (i > div.scrollHeight - 160) {i = 0}
t1=setTimeout("scroll()",100)
}
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Department', 'Employee %'],
  <?php
  while($getDeptCountRow = mysqli_fetch_assoc($getDepartment))
  {
  ?>
	 ['<?php echo $getDeptCountRow['department']; ?>', <?php echo $getDeptCountRow['emp_count']; ?>],

  <?php
  }
  ?>
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Employee(s), Department Wise', 'width':'100%', 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
</head>
<body class="hold-transition skin-blue sidebar-mini" onload="scroll()">
<div class="wrapper">

  <?php
 require_once('layouts/main-header.php');
 ?>
  <!-- Left side column. contains the logo and sidebar -->
    <?php
 require_once('layouts/main-sidebar.php');
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 align="center">

	  <?php
	  session_start();
	  $employeename = $_SESSION['Employee_Name'];

	  ?>
       Hey <?php  echo ' '.$employeename.'! '; ?>

      </h1>
	  <h4 style="color:purple;" align="center">
	 Have an Awesome Day!
	  </h4>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
<?php
include('config.php');
$employeeid = $_SESSION['login_user'];
$getUserRole = mysqli_query($db,"select job_role from employee_details where employee_id=$employeeid");
$getUserRoleRow = mysqli_fetch_array($getUserRole);
$UserRole = $getUserRoleRow['job_role'];
$getCheckinCheckout = mysqli_query($db,"select  date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,time(check_in) as checkin from attendance where check_in != '0001-01-01 00:00:00' and employee_id=$employeeid order by shift_Date desc limit 1;");
$checkinRow = mysqli_fetch_array($getCheckinCheckout);
$lastcheckin = $checkinRow['checkin'];
$lastdate = $checkinRow['shiftdate'];
$getCheckout = mysqli_query($db,"select  date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,time(check_out) as checkout from attendance where check_out != '0001-01-01 00:00:00' and employee_id=$employeeid order by shift_Date desc limit 1;");
$checkoutRow = mysqli_fetch_array($getCheckout);
$lastcheckout = $checkoutRow['checkout'];
$lastdateout = $checkoutRow['shiftdate'];

$lastsevenintime = mysqli_query($db,"select date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,date_format(shift_Date,'%W') as day,
if(time(check_in)='00:00:00','No In-Time',time(Check_in)) as checkin from attendance where shift_Date between DATE_SUB(curdate(), INTERVAL 8 DAY) and DATE_SUB(curdate(), INTERVAL 1 DAY) and employee_id=$employeeid
and date_format(shift_Date,'%W')!='Sunday'
 order by shift_Date desc");

 $lastsevenouttime = mysqli_query($db,"select date_format(date(shift_Date),'%d-%m-%Y') as shiftdate,date_format(shift_Date,'%W') as day,
if(time(check_out)='00:00:00','No Out-Time',time(check_out)) as checkout from attendance where shift_Date between DATE_SUB(curdate(), INTERVAL 8 DAY) and DATE_SUB(curdate(), INTERVAL 1 DAY) and employee_id=$employeeid
and date_format(shift_Date,'%W')!='Sunday'
 order by shift_Date desc");


 $leavebalance = mysqli_query($db,"SELECT cl_opening,sl_opening,pl_opening,cl_taken,sl_taken,pl_taken,cl_closing,sl_closing,pl_closing,comp_off_opening,comp_off_availed,comp_off_closing FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate()) and month=month(curdate())");
$leavestaken = mysqli_query($db,"SELECT sum(cl_taken) as cl_taken,sum(sl_taken) as sl_taken, sum(pl_taken) as pl_taken FROM `employee_leave_tracker` where employee_id=$employeeid and year=year(curdate());");
 $leavebalanceRow = mysqli_fetch_array($leavebalance);
$clOpening = $leavebalanceRow['cl_opening'];
$clavailed = $leavebalanceRow['cl_taken'];
$clbalance = $leavebalanceRow['cl_closing'];
$slOpening = $leavebalanceRow['sl_opening'];
$slavailed = $leavebalanceRow['sl_taken'];
$slbalance = $leavebalanceRow['sl_closing'];
$plOpening = $leavebalanceRow['pl_opening'];
$pltaken = $leavebalanceRow['pl_taken'];
$plbalance = $leavebalanceRow['pl_closing'];
$compoffopening = $leavebalanceRow['comp_off_opening'];
$compoffclosing = $leavebalanceRow['comp_off_closing'];
$compofftaken = $leavebalanceRow['comp_off_availed'];
$GetPCount = mysqli_query($db,"select is_comp_off_eligible from employee where employee_id=$employeeid");
$getPCountRow = mysqli_fetch_array($GetPCount);
$PCount = $getPCountRow['is_comp_off_eligible'];
if($PCount=='Y' || $PCount=='T')
{
	$TotalCount = 2;
}
else
{
	$TotalCount = 10;
}
$getAvailedCountSixty = mysqli_query($db,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$employeeid and leave_type='Permission' and duration='60' and cancled='N';");
$getAvailedCountOT = mysqli_query($db,"SELECT * FROM `leave_status` where month(date_availed)=month(curdate()) and year(date_availed)=year(curdate()) and employee_id=$employeeid and leave_type='Permission' and duration='120' and cancled='N';");
$AvailedCntSixty = mysqli_num_rows($getAvailedCountSixty);
$AvailedCntSixtyOT = mysqli_num_rows($getAvailedCountOT);
$AvailedCnt=$AvailedCntSixty+($AvailedCntSixtyOT*2);
$PermissionBalance = $TotalCount-$AvailedCnt;
$getDailyImage = mysqli_query($db,"select value,date_format(date(created_date_and_time),'%D %b, %Y') as created_date_and_time,created_by from application_configuration where config_type='DAILY_IMAGE'");
$getDailyImageRow = mysqli_fetch_array($getDailyImage);
$getDailyImageVal = $getDailyImageRow['value'];
$ImageAddedDate = $getDailyImageRow['created_date_and_time'];
$ImageAddedBY = $getDailyImageRow['created_by'];
$leavestakenRow = mysqli_fetch_array($leavestaken);
$cltake = $leavestakenRow['cl_taken'];
$sltake = $leavestakenRow['sl_taken'];
$pltake = $leavestakenRow['pl_taken'];
$totleaves = $cltake+$sltake+$pltake;

 $getLopCOunt = mysqli_query($db,"select sum(if(permission_type='',1,0.5)) as lop_count from lop_Details where employee_id=$employeeid");
$getLopCOuntRow = mysqli_fetch_array($getLopCOunt);
 $LopCount = $getLopCOuntRow['lop_count'];

 $getExtraHours = mysqli_query($db,"SELECT date,extra_hours FROM `extra_hours_tracker` where is_approved='N' and is_applied='N' and employee_id=$employeeid ;");

$getEmployeesofManager = mysqli_query($db,"select group_concat(employee_id) as employees from employee_details where reporting_manager_id=$employeeid and is_active='Y'");
$getEmployeesofManagerRow = mysqli_fetch_array($getEmployeesofManager);
$getGrpedEmployee = $getEmployeesofManagerRow['employees'];

$getReviewDates = mysqli_query($db,"select review_to_date,DATE_SUB(DATE_ADD(LAST_DAY(DATE_SUB(review_to_date,interval 11 month)),INTERVAL 1 day),INTERVAL 1 month) as from_Date from employee_performance_review_dates where employee_id=$employeeid");
$getReviewDatesRow = mysqli_fetch_Array($getReviewDates);
$PMSfromDate=$getReviewDatesRow['from_Date'];
$PMSToDate=$getReviewDatesRow['review_to_date'];
$getAttendancePercentage = mysqli_query($db,"select round(avg(present_per)) as avg from pms_summary where employee_id=$employeeid and str_to_Date(`from`,'%d-%M-%Y') between
'$PMSfromDate' and '$PMSToDate'");
$getAttendancePercentageRow = mysqli_fetch_array($getAttendancePercentage);
$GetPercentage = $getAttendancePercentageRow['avg'];
if($GetPercentage=='')
{
$GetPercentage='100';
}
 ?>
<input type="hidden" id="ClTaken" value="<?php echo $cltake; ?>" />
<input type="hidden" id="SlTaken" value="<?php echo $sltake; ?>" />
<input type="hidden" id="PlTaken" value="<?php echo $pltake; ?>" />
<?php
$getServiceCount = mysqli_query($db,"select * from employee_details where is_permanent='Y' and is_services_confirmed='N' and month(probation_end_date) =
month(curdate()) and year(probation_end_date)=year(curdate()) and is_active='Y'");
$getCount = mysqli_num_rows($getServiceCount);

?>
<?php
$CheckPendingResignation = mysqli_query($db,"select 'Pending Resignation to be processed for' as message, name , substring_index(pending_queue_id,'-',-1) as role
from(select r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id,r.process_queue
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by  resignation_id, num)as a where substring_index(pending_queue_id,'-',1)=$employeeid and process_queue not in ('HR_Manager_Process','HR_Manager_Approved')");

$getEmployeeLoginDetails =mysqli_query($db,"select job_role from employee_details where employee_id=$employeeid");
$getEmployeeLoginDetailsrow = mysqli_fetch_assoc($getEmployeeLoginDetails);
$getjobrole = $getEmployeeLoginDetailsrow['job_role'];

if($getjobrole == 'System Admin Manager' || $getjobrole =='System Admin')
{
	$queryparams = 'no_due_sysadmin_status';
	$CheckPendingResignationNodue = mysqli_query($db,"select 'Pending Resignation to be processed for' as message, name ,date_of_leaving, substring_index(pending_queue_id,'-',-1) as role
from(select distinct r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,date_of_leaving,no_due_sysadmin_status,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id,r.process_queue
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by  resignation_id, num)as a where substring_index(pending_queue_id,'-',1)=$employeeid and process_queue in ('HR_Manager_Process') and no_due_sysadmin_status='Y' group by employee_id");
}
if($getjobrole == 'Accounts Manager' || $getjobrole =='Accountant')
{
	$queryparams = 'no_due_acc_status';
$CheckPendingResignationNodue = mysqli_query($db,"select 'Pending Resignation to be processed for' as message, name ,date_of_leaving, substring_index(pending_queue_id,'-',-1) as role
from(select r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,date_of_leaving,no_due_acc_status,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id,r.process_queue
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by  resignation_id, num)as a where substring_index(pending_queue_id,'-',1)=$employeeid and process_queue in ('HR_Manager_Process') and no_due_acc_status='Y'");
}
if($getjobrole == 'Admin')
{
	$queryparams = 'no_due_admin_status';
	
$CheckPendingResignationNodue = mysqli_query($db,"select 'Pending Resignation to be processed for' as message, name ,date_of_leaving, substring_index(pending_queue_id,'-',-1) as role
from(select r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,date_of_leaving,no_due_admin_status,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id,r.process_queue
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by  resignation_id, num)as a where substring_index(pending_queue_id,'-',1)=$employeeid and process_queue in ('HR_Manager_Process') and no_due_admin_status='Y'");
}



$CheckPendingResignationEmployee = mysqli_query($db,"select 'Pending Action for your Resignation ' as message, name , substring_index(pending_queue_id,'-',-1) as role
from(select r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,exit_interview_status,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by
  resignation_id, num)as a  where substring_index(pending_queue_id,'-',1)=$employeeid and substring_index(pending_queue_id,'-',-1)='emp' and exit_interview_status='Y'");
  
  $CheckCancelRequestEmp = mysqli_query($db,"select 'Resignation Cancellation Request Received. Kindly Process' as message, name ,status, substring_index(pending_queue_id,'-',-1) as role
from(select r.employee_id,concat(r.employee_id,' - ',first_name,' ',last_name) as name,exit_interview_status,status,SUBSTRING_INDEX(SUBSTRING_INDEX(r.pending_queue_id, ',', numbers.num), ',', -1) as pending_queue_id
from numbers inner join employee_resignation_information r
  on CHAR_LENGTH(r.pending_queue_id)
     -CHAR_LENGTH(REPLACE(r.pending_queue_id, ',', ''))>=numbers.num-1
inner join employee_details d on r.employee_id=d.employee_id
where r.is_active='Y' and d.is_active='Y'
order by
  resignation_id, num)as a where substring_index(pending_queue_id,'-',1)=$employeeid and substring_index(pending_queue_id,'-',-1)='emp' and status='Request for Cancellation of Resignation';");
  
  
 $CheckPendingResignationEmployeeRow = mysqli_fetch_Array($CheckPendingResignationEmployee);
 $Message = $CheckPendingResignationEmployeeRow['message'];
 $Name = $CheckPendingResignationEmployeeRow['name'];
 
 $CheckPendingResignationEmployeeRow1 = mysqli_fetch_Array($CheckCancelRequestEmp);
 $Message1 = $CheckPendingResignationEmployeeRow1['message'];
 $Name1 = $CheckPendingResignationEmployeeRow1['name'];
?>












<!--  Actionable Tasks-->

<!--  Boarding Formalities-->

<?php
$getServiceCount = mysqli_query($db,"select employee_id,concat(first_name,' ',last_name,' ',MI) as Name from employee_details where is_permanent='Y' and is_services_confirmed='N' and month(probation_end_date) =
month(curdate()) and year(probation_end_date)=year(curdate()) and is_active='Y'");
$getSCount = mysqli_num_rows($getServiceCount);
?>
<?php

	$getAppIds= mysqli_query($db,"select group_concat(applicant_id) as applicant_id from employee_details");
	$getAppIdRow = mysqli_fetch_array($getAppIds);
	$AppIds = $getAppIdRow['applicant_id'];
	?>
	 <?php
			  include("config2.php");
			  include("ModificationFunc.php");
			  $getJoinees = mysql_query("select a.applicant_id,concat(b.first_name,' ',b.last_name,' ',b.mi) as Applicant_Name,a.position_applied,date_format(a.date_of_joining,'%D %b, %Y') as  date_of_joining,a.position_id from applicant_tracker a
							left join applicant_Details b on a.applicant_id=b.applicant_id
							where status='Selected' and date_of_joining<=curdate() and date_of_joining not in ('0000-00-00 00:00:00','0001-01-01 00:00:00')  and a.applicant_id not in ($AppIds) and a.position_id!='' order by date_of_joining desc");
			  $getECount = mysql_num_rows($getJoinees);
?>

<!-- Resource Management -->

<?php
include("config.php");
include("pages/LeaveMgmt/Attendance_Config.php");
$getPendingUploadLoa = mysqli_query($db,"SELECT * FROM `resource_management_table` where is_active='Y' and signed_loa_doc=''");
$getPendingUploadLoaCount = mysqli_num_rows($getPendingUploadLoa);
$getPendingUploadLoaName = mysqli_query($db,"SELECT a.employee_id,concat(B.First_Name,' ',B.Last_Name,' ',B.MI) as name

 FROM `resource_management_table` a left join employee_details b on a.employee_id=b.employee_id
 where a.is_active='Y' and signed_loa_doc='';");



$getJoinedEmployees = mysqli_query($db,"select a.employee_id,concat(B.First_Name,' ',B.Last_Name,' ',B.MI) as name,date_format(a.date_of_joining,'%d - %b - %Y') as
date_of_joining,concat(b.first_name,' ',b.last_name,' ',b.MI) as name,are_documents_uploaded,is_provisions_completed,is_designated,is_data_sheet_completed,b.is_personal_data_filled
from employee_boarding a
left join employee_details b on a.employee_id=b.employee_id

where is_formalities_completed='P' and b.is_active='Y'");
$getJoinedEmployeescnt = mysqli_num_rows($getJoinedEmployees);

$getAEDSEmployees = mysqli_query($db,"select a.employee_id,date_format(a.date_of_joining,'%d - %b - %Y') as
date_of_joining,concat(b.first_name,' ',b.last_name,' ',b.MI) as name,are_documents_uploaded,
is_provisions_completed,is_designated,is_data_sheet_completed,b.is_personal_data_filled
from employee_boarding a
left join employee_details b on a.employee_id=b.employee_id

where is_formalities_completed in ('N','P') and b.is_personal_data_filled='Y' and b.is_active='Y'");
$getAEDSEmployeescnt = mysqli_num_rows($getAEDSEmployees);
$getTrainerQueue = mysqli_query($db,"SELECT distinct(training_id) FROM `training_sessions` where trainer='$employeeid' and is_active='Y';");
$getTrainerCount = mysqli_num_rows($getTrainerQueue);

$getleaverequest = mysqli_query($db,"select req_id,a.employee_id,concat(a.employee_id,' - ',b.first_name,' ',b.last_name) as name,leave_type,number_of_days,status as action,if(leavE_for='','--',leavE_for) as leavE_for,reason,
date_format(a.created_date_and_time,'%D %b, %Y') as  date_of_request,
date_format(leave_from,'%D %b, %Y') as  leave_from,
date_format(leave_to,'%D %b, %Y') as  leave_to,
is_approved from leave_request a
left join employee_details b on a.employee_id=b.employee_id
 where allocated_to='$employeeid' and  is_approved not in ('Y','N') and a.is_active='Y' order by a.created_date_and_time desc ; ");
 
 $getExpiringCompoff = mysqli_query($att,"select employee_id ,id,comp_off_date,duration,unit,expiry_date,DATEDIFF(expiry_date,curdate()) as days_to_exp,
extended_by,date(extended_date) as extended_date
from comp_off_tracker
 where employee_id in (".$getGrpedEmployee.") and is_availed='N' and DATEDIFF(expiry_date,curdate())<=15 and extended_by=''");
 $getAdminFormalities= mysqli_query($db,"select a.employee_id,concat(a.first_name,' ',a.last_name,' ',a.mi) as name, employee_designation,department from employee_details a inner join employee_boarding b on a.employee_id=b.employee_id where mail_type!=' ' and is_login_created='No' and a.is_active='Y'");

    $getResChangeReq = mysqli_query($db,"select raised_by,concat(First_Name,' ',Last_Name,' ',Mi) as Name

 from resource_change_Request a inner join employee_details b on a.raised_by=b.employee_id where a.is_active='Y' and status='Request under Process';");
    
$getExpiringInsurance = mysqli_query($db,"select employee_id,first_name,insurance_expiry,timestampdiff(month,curdate(),insurance_expiry) from employee_details


where employee_id='$employeeid' and timestampdiff(month,curdate(),insurance_expiry) <=1 and


insurance_expiry not in ('0001-01-01','1970-01-01') and insurance_Expiry>curdate()");
$PendingCredentials = mysqli_query($db,"select a.id,category,physical_location,logical_location,user_name,comments,last_renewed_date,

timestampdiff(day,curdate(),next_renewal_date) as days,concat(First_Name,' ',last_name,' : ',employee_id) as created_by from credentials_master a

join employee_details b on a.created_by=b.employee_id
where a.is_active='Y'

and timestampdiff(day,curdate(),next_renewal_date) <=10 and a.id in (select item_id from credentials_ownership where owner_id='$employeeid')");
				$PendingCredentialsCount = mysqli_num_rows($PendingCredentials);
    ?>


 <?php 
    		$getBday = mysqli_query($db,"SELECT * FROM `birthdays_anniversaries` where month(date_of_event)=month(curdate())
and date_format(date_of_event,'%d')=date_format(curdate(),'%d') and employee_id='$employeeid'");
if(mysqli_num_rows($getBday)>0)
		{
				$getBdayRow = mysqli_fetch_array($getBday);
				$event = $getBdayRow['event_type'];
				if($event=='Birthday')
                {
                	$message = 'Happy Birthday '.$getBdayRow['name'];
                }
				else
                {
                	$message = 'Happy Anniversary '.$getBdayRow['name'];
                }
    	?>
    						<div class="alert alert-success alert-block fade in" style="color: #31708f!important;background-color: #d9edf7!important;font-family: 'Open Sans', sans-serif!important;border-color: #008080!important;">
                                  <button data-dismiss="alert" class="close close-sm" type="button">
                                      <i class="fa fa-times"></i>
                                  </button>
                                  <h4>
                                      <i class="fa fa-ok-sign"></i><i class="fa fa fa-gift"></i><?php echo ' '.$message.' !'; ?></h4>
                                  <p>Wishing you a Happy Year Ahead! <a href="#" target="_blank" style="color: black;" data-toggle="modal" data-target="#modal-default-Empl1" >Click </a> to view wishes from your Colleagues!</p>
                              </div>
    <?php } ?>
<?php  
$profPerc = 0;
$getKYEDetails = mysqli_query($db,"select * from kye_details where is_active='Y' and employee_id='$employeeid' and document_type in ('AADHAAR ','PERMANANT ACCOUNT NUMBER','12th CERTIFICATE ','QUALIFICATION DOCUMENT','10th CERTIFICATE')"); 
if(mysqli_num_rows($getKYEDetails)>=3)
{
		$profPerc=$profPerc+25;
}
$getFamilyHistory = mysqli_query($db,"select * from employee_family_particulars where employee_id='$employeeid' and is_active='Y'");
if(mysqli_num_rows($getFamilyHistory)>=1)
{
		$profPerc=$profPerc+25;
}
$getEducationHistory = mysqli_query($db,"select * from employee_qualifications where employee_id='$employeeid' and is_active='Y'");
if(mysqli_num_rows($getEducationHistory)>=1)
{
		$profPerc=$profPerc+25;
}
$getSkillsHistory = mysqli_query($db,"select * from employee_skills where employee_id='$employeeid' and is_active='Y'");
if(mysqli_num_rows($getSkillsHistory)>=1)
{
		$profPerc=$profPerc+25;
}
?>
<div class="row">
        <div class="col-md-4 pull-right">
    <div class="progress-group">
                    <span class="progress-text">Profile Completion %</span>
                    <span class="progress-number"><b><?php echo $profPerc.' %';  ?></b></span>

                    <div class="progress sm" style="margin-bottom:8px">
                      <div class="progress-bar progress-bar-aqua" style="width:<?php echo $profPerc.'%';?>;background-color: teal;"></div>
                    </div>
   	 <?php  if ($profPerc<100) {?>
    					 <a href="myDetails.php" title="KYE Info, Education Info, Skills Info & Family Info are Mandatory!">Click to Complete</a>
    <?php } ?>
                  </div>
</div>
    </div>
<fieldset>


 <legend style="border-bottom: 1px solid #555"><h4>Actionable Tasks</h4></legend>
  <div class="box box-primary">
            <div class="box-header with-border" style="
    background-color: #00a65a;
    color: white;">
              <i class="fa fa-fw fa-tasks"></i>

              <h3 class="box-title">Pending Tasks</h3>


            </div>
            <div class="box-body" id="PendingTasks">
               <div class="table-responsive">
                <table class="table no-margin">
				 <h5 class="box-title">Tasks awaiting your action will show up here!</h5>
                  <thead>
                  <tr>
                    <th>Module</th>
                    <th>Pending Tasks</th>
					<th>View</th>

                  </tr>
                  </thead>
                  <tbody>

					<?php
					if(($userRole=='HR Manager' || $userRole=='HR') && $getECount>0)
					{
					?>

					 <?php
					 while($getJoineeRow = mysql_fetch_assoc($getJoinees))
					 {
					 ?>
					  <tr>
						<td>Boarding Formalities</td>
						<td><strong><?php echo $getJoineeRow['Applicant_Name'].' ' ?></strong> is Scheduled to join on  <?php echo $getJoineeRow['date_of_joining'].'.'  ?></td>
						<td><a href="pages/onBoarding/BoardingHome.php">View</a></td>
						</tr>
					<?php
					}
					?>



					<?php
					}
					?>
					<?php
					if(($userRole=='HR Manager' || $userRole=='HR') && $getJoinedEmployeescnt>0)
					{
					?>
					<?php
					 while($getJoinedEmployeesRow = mysqli_fetch_assoc($getJoinedEmployees))
					 {
					 ?>
					 <tr>
						<td>Boarding Formalities</td>
						<td>Boarding Formalities left Incomplete for <strong><?php echo ' '.$getJoinedEmployeesRow['employee_id'].' : '.$getJoinedEmployeesRow['name'];  ?></strong></td>

						<td><a href="pages/onBoarding/CompleteFormalities.php?id=<?php echo $getJoinedEmployeesRow['employee_id']; ?>">View</a></td>
					</tr>
					<?php
					}
					?>
					<?php
					}
					?>
					<?php
					if(($userRole=='HR Manager' || $userRole=='HR') && $getAEDSEmployeescnt>0)
					{
					?>
					<?php
					 while($getAEDSEmployeesRow = mysqli_fetch_assoc($getAEDSEmployees))
					 {
					 ?>
					 <tr>
						<td>Boarding Formalities</td>
						<td><strong><?php echo $getAEDSEmployeesRow['employee_id'].' - '.$getAEDSEmployeesRow['name'].' ' ?></strong> has filled his / her AEDS Form.</td>

						<td><a href="pages/onBoarding/EmployeeDataSheet.php?id=<?php echo $getAEDSEmployeesRow['employee_id']; ?>">View</a></td>
					</tr>
					<?php
					}
					?>
					<?php
					}
					?>
					<?php
					if(($userRole=='HR Manager' || $userRole=='HR') && $getSCount > 0)
					{
					?>
					<?php
					 while($getServiceCountRow = mysqli_fetch_assoc($getServiceCount))
					 {
					 ?>
					 <tr>
						<td>Confirmation of Services</td>
						<td><strong><?php echo $getServiceCountRow['employee_id'].' - '.$getServiceCountRow['Name'].' ' ?></strong> awaits Confirmation of his/ her Services.</td>

						<td><a href="pages/onBoarding/ConfirmServices.php">View </a></td>


					</tr>
					<?php
					}
					?>
					<?php
					}
					?>
					<?php
					if(($userRole=='HR Manager' || $userRole=='HR') && $getPendingUploadLoaCount>0)
					{
						while($getNames = mysqli_fetch_assoc($getPendingUploadLoaName))
						{
					?>
					 <tr>
						<td>Resource Management</td>
						<td>Pending Office Order upload for <strong><?php echo ' '.$getNames['employee_id'].' - '.$getNames['name'].' ' ?></strong> </td>

						<td><a href="pages/tables/ViewResource.php?id=<?php echo $getNames['employee_id'] ?>">View </a></td>


					</tr>

					<?php
						}
					}
					?>
					<?php
					if(($userRole=='System Admin Manager' || $userRole=='System Admin') && $getAdminFormalities>0)
					{
						while($getAdminFormalitiesRow = mysqli_fetch_assoc($getAdminFormalities))
						{
					?>
					 <tr>
						<td>Employee Boarding</td>
						<td>Email & Login Creation Request Received for<strong><?php echo ' '.$getAdminFormalitiesRow['employee_id'].' - '.$getAdminFormalitiesRow['name'].'. ' ?></strong> Kindly Process.</td>

						<td><a href="pages/onBoarding/BoardingHome.php?id=<?php echo $getAdminFormalitiesRow['employee_id'] ?>">View </a></td>


					</tr>

					<?php
						}
					}
					?>
                  
                  
                  <?php
					if(($userRole=='HR Manager' || $userRole=='HR') && mysqli_num_rows($getResChangeReq)>0)
					{
						while($getResChangeReqRow = mysqli_fetch_assoc($getResChangeReq))
						{
					?>
					 <tr>
						<td>Resource Management</td>
						<td>Resource Change Request Received from <strong><?php echo ' '.$getResChangeReqRow['raised_by'].' - '.$getResChangeReqRow['Name'].'. ' ?></strong> Kindly Process.</td>

						<td><a href="pages/tables/ResourceChangeRequest.php">View </a></td>


					</tr>

					<?php
						}
					}
					?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
					<?php
					if(mysqli_num_rows($getTrainerQueue)>0)
					{
					?>
					 <tr>

						<td>Training Management</td>
						<?php
						?>
						<td><?php echo mysqli_num_rows($getTrainerQueue).' ' ?> Trainings under Your queue.</td>

						<td><a href="pages/TrainingMgmt/TrainerQueue.php">View</a></td>
					</tr>

					<?php
					}
					?>
                  
                  
                  <?php
					if(mysqli_num_rows($getExpiringInsurance)>0)
					{
					?>
					 <tr>

						<td>Employee Info</td>
						<?php
						?>
						<td>Your Insurance is About to Expire. Kindly Renew the Same ASAP.</td>

						<td><a href="personalInfo.php">View</a></td>
					</tr>

					<?php
					}
					?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
					

					<?php
					if(mysqli_num_rows($CheckPendingResignation) > 0)
					{
					?>
					<?php
					while($CheckPendingResignationRow = mysqli_fetch_assoc($CheckPendingResignation))
					{
						?>
						<?php
						if($CheckPendingResignationRow['role']=='hrm')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/resignationprocessingform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationRow['role']=='rep')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/resignationapprovalform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationRow['role']=='hod')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/departmentresignationform.php">View</a></td>
						</tr>
						<?php
							}
						?>
					<?php
					}
					?>

					<?php
					}
					?>
					
					
					<?php
					if(mysqli_num_rows($CheckPendingResignationNodue) > 0)
					{
					?>
					<?php
					while($CheckPendingResignationNodueRow = mysqli_fetch_assoc($CheckPendingResignationNodue))
					{
						if($CheckPendingResignationNodueRow['role']=='hr')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/noduehrform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationNodueRow['role']=='hrm')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/resignationprocessingform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationNodueRow['role']=='rep')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/resignationapprovalform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationNodueRow['role']=='hod')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/departmentresignationform.php">View</a></td>
						</tr>
						<?php
							}
						?>
						<?php
						if($CheckPendingResignationNodueRow['role']=='acc')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/nodueaccform.php">View</a></td>
						</tr>
						<?php
							}
						?>
                  		
                        <?php
						if($CheckPendingResignationNodueRow['role']=='hdm')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/noduehradmform.php">View</a></td>
						</tr>
						<?php
							}
						?>

						<?php
						if($CheckPendingResignationNodueRow['role']=='adm')
							{
						?>

					   <tr>

						 <td>Resignation Management</td>
						 <?php
						 ?>
						 <td><?php echo $CheckPendingResignationNodueRow['message'].' : ' ?><strong><?php echo $CheckPendingResignationNodueRow['name'] ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/nodueadminform.php">View</a></td>
						</tr>
						<?php
							}
						?>
					<?php
					}
					?>

					<?php
					}
					?>
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					<?php
					if(mysqli_num_rows($CheckPendingResignationEmployee) > 0)
					{
					?>

						  <tr>

						 <td>Resignation</td>
						 <?php
						 ?>
						 <td><?php echo $Message.' : ' ?><strong><?php echo $Name ?> </strong></td>

						 <td><a href="pages/ResignationMgmt/employeeresignationform.php">View</a></td>
						</tr>
					<?php
					}
					?>
					
					<?php
					if(mysqli_num_rows($CheckCancelRequestEmp) > 0)
					{
					?>

						  <tr>

						 <td>Resignation</td>
						 <?php
						 ?>
						 <td><?php echo $Message1;  ?></td>

						 <td><a href="pages/ResignationMgmt/employeeresignationform.php">View</a></td>
						</tr>
					<?php
					}
					?>					
						<?php
					if(mysqli_num_rows($getleaverequest) > 0)
					{
					?>
						<?php
						while($getleaverequestRow = mysqli_fetch_assoc($getleaverequest))
						{
						?>
						  <tr>

						 <td>Attendance Management</td>
						 <?php
						 ?>
						 <td><strong><?php echo $getleaverequestRow['name'].' '?></strong> has raised a new request. Kindly Process. </td>

						 <td><a href="pages/LeaveMgmt/TeamLeaveRequest.php">View</a></td>
						</tr>
					<?php
						}
					}
					?>
                    <?php
					if(mysqli_num_rows($getExpiringCompoff) > 0)
					{
					?>
						<?php
						while($getExpiringCompoffRow = mysqli_fetch_assoc($getExpiringCompoff))
						{
						?>
						  <tr>

						 <td>Comp off Expiring</td>
						 <?php
						 ?>
						 <td>Comp Off for <strong>
						 <?php 
						 $getCofEmpName = mysqli_query($db,"select concat(First_name,' ',last_name,' -' ,employee_id) as name from employee_details where employee_id='".$getExpiringCompoffRow['employee_id']."'");
						 $getCofEmpNameRow = mysqli_fetch_Array($getCofEmpName);
						 $CofEmpName = $getCofEmpNameRow['name'];
						 echo $CofEmpName.' ';
						 ?>
						 
						 </strong> is about to expire in <?php echo ' <strong>'.$getExpiringCompoffRow['days_to_exp'].'</strong>  Days. Kindly Check.'; ?>

						 </td>

						 <td><a href="pages/LeaveMgmt/CompOffRequest.php?id=<?php echo $getExpiringCompoffRow['employee_id'] ?>">View</a></td>
						</tr>
					<?php
						}
					}
					?>
                   <?php
					if(mysqli_num_rows($PendingCredentials) > 0)
					{
					?>
						<?php
						while($PendingCredentialsRow = mysqli_fetch_assoc($PendingCredentials))
						{
							if($PendingCredentialsRow['days']>0)
							{
									$expiryitem = 'is about to expire in  '.$PendingCredentialsRow['days'].' day(s). Kindly check.';
							}
							elseif($PendingCredentialsRow['days']<0)
							{
									$expiryitem = 'has expired. Kindly check.';
							}
							else
							{
								$expiryitem = 'will be expired today. Kindly check.';
							}
						?>
						  <tr>

						 <td>Password Management</td>
						 <?php
						 ?>
						 <td><strong> <?php echo $PendingCredentialsRow['category'].' ( '.$PendingCredentialsRow['physical_location'].' ) item '; ?>
						 
						 </strong>  <?php echo ' '.$expiryitem; ?>

						 </td>

						 <td><a href="pages/Credentials/AllCredentials.php">View</a></td>
						</tr>
					<?php
						}
					}
					?>
                  </tbody>


                </table>
              </div>
            </div>
            <!-- /.box-body-->
          </div>


	<br>

</fieldset>






























 <fieldset>


  <legend style="border-bottom: 1px solid #555"><h4>Your Attendance</h4></legend>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
				<div class="inner">
					<h3><?php echo $lastcheckin ?></h3>
					<p>Last In-Time on <?php echo $lastdate ?></p>
				</div>
  <div class="icon">
              <i class="fa fa-fw fa-clock-o"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
				<div class="inner">
					<h3><?php echo $lastcheckout ?></h3>
					<p>Last Out-Time on <?php echo $lastdateout ?> </p>
				</div>
	<div class="icon">
              <i class="fa fa-fw fa-clock-o"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $GetPercentage.' %'; ?></h3>

              <p>Attendance % for this Appraisal Year</p>
            </div>

          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">

 <a href="#" id="additionalMod" title="Click to View LOP Details" data-toggle="modal" data-target="#modal-default-Lop"> <h3 style="color:white;"><?php if($LopCount=='') { echo '0' ; } else { echo $LopCount; } ?></h3></a>

              <p>Loss of Pay for <?php echo ' '.date('F'); ?></p>
            </div>

          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- Small boxes (Stat box) -->
     <div class="row">
          <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">In-Time for Last 7 Days</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
			<div class="box-body">
			  <div class="box">
            <div class="box-header with-border">

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th>Shift Date</th>

                  <th>In-Time</th>
                </tr>
              <?php
				while ($lastsevenrow = mysqli_fetch_assoc($lastsevenintime))
			{
			?>
                <tr>
                  <td><?php  echo  $lastsevenrow['shiftdate']; ?></span></td>
                  <td><span class="badge bg-blue"><?php  echo  $lastsevenrow['checkin']; ?></span></td>
                </tr>

				<?php

			}
				?>



              </table>
            </div>
            <!-- /.box-body -->
          </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		</div>
        <!-- /.col -->
      <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Out-Time for Last 7 Days</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <div class="box">
            <div class="box-header with-border">

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th>Shift Date</th>

                  <th>Out-Time</th>
                </tr>
            <?php
			while ($lastsevenoutrow = mysqli_fetch_assoc($lastsevenouttime))
			{
			?>
                <tr>
                  <td><?php  echo  $lastsevenoutrow['shiftdate']; ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $lastsevenoutrow['checkout']; ?></span></td>
                </tr>

				<?php

			}
				?>


              </table>
            </div>
            <!-- /.box-body -->
          </div>
            </div>


            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
         <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Leave Balance</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

            <!-- /.box-header -->
            <div style="padding: 0px;" class="box-body">
			  <div class="box">
            <div class="box-header with-border">

            </div>
            <!-- /.box-header -->
            <div style="padding: 0px;"  class="box-body">
              <table id="leaveTable" style="padding: 0px;" class="table table-bordered">
                <tr>
                  <th>Leave</th>

                  <th>Total</th>
                  <th>Taken</th>
                  <th>Balance</th>
                </tr>
           <tr>
                  <td>CL</td>
                  <td><span class="badge bg-blue"><?php  echo  $clOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $clavailed ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $clbalance ?></span></td>
                </tr>
                 <tr>
                  <td>PL</td>
                  <td><span class="badge bg-blue"><?php  echo  $plOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $pltaken ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $plbalance ?></span></td>
                </tr>
                <tr>
                  <td>SL</td>
                  <td><span class="badge bg-blue"><?php  echo  $slOpening ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $slavailed ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $slbalance ?></span></td>
                </tr>
				<?php
				if($PCount=='Y')
				{
				?>
				 <tr>
                  <td>Comp-Off</td>
                  <td><span class="badge bg-blue">0</span></td>
                  <td><span class="badge bg-red"><?php  echo  $compofftaken ?></span></td>
                  
                  <td><span class="badge bg-green"><?php  echo  $compoffclosing ?></span></td>
                </tr>
				<?php
				}
				?>
				<tr>
                  <td>Permission</td>
                  <td><span class="badge bg-blue"><?php  echo  $TotalCount ?></span></td>
                  <td><span class="badge bg-red"><?php  echo  $AvailedCnt ?></span></td>
                  <td><span class="badge bg-green"><?php  echo  $PermissionBalance ?></span></td>
                </tr>

              </table>
            </div>
            <!-- /.box-body -->
          </div>
			  </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
         <div class="col-md-3">
          <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Your Extra Hours</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              	 <table class="table table-striped">
                <tr>

                  <th>Date</th>
                  <th>Extra Hours</th>

                </tr>
		<?php
				while ($getExtraHoursRow = mysqli_fetch_assoc($getExtraHours))
			{
			?>
                <tr>
                  <td><?php  echo  $getExtraHoursRow['date']; ?></span></td>
                  <td><span class="badge bg-blue"><?php  echo  $getExtraHoursRow['extra_hours']; ?></span></td>
                </tr>

				<?php

			}
				?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
    </fieldset>
<br>
<?php
$getEmployeeDesignation = mysqli_query($db,"SELECT concat(Band,' ',designation,' ',level) as desn,department,reporting_manager,date_format(date(effective_from),'%D %b, %Y') as date FROM `resource_management_table` where effective_from >curdate() and employee_id='$employeeid';");
$getEmployeeDesignationRow = mysqli_fetch_array($getEmployeeDesignation);
$EmployeeNewDesign = $getEmployeeDesignationRow['desn'];
$EmployeeNewDesignFrom = $getEmployeeDesignationRow['date'];
$EmployeeNewDepartment = $getEmployeeDesignationRow['department'];
$EmployeeNewMgmr = $getEmployeeDesignationRow['reporting_manager'];
$getReportingManagerName = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as name from employee_details where employee_id ='$EmployeeNewMgmr'");
$getReportingManagerNameRow = mysqli_fetch_array($getReportingManagerName);
$ReportingManager = $getReportingManagerNameRow['name'];


$getReportingTeam = mysqli_query($db,"select group_concat(employee_id) as team_employees from employee_Details where reporting_manager_id='$employeeid'");
$getReportingTeamRow = mysqli_fetch_array($getReportingTeam);
$ReportingTeam =$getReportingTeamRow['team_employees'];
$getFYI = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id='$employeeid' and module_name='Resignation Management' and emp_ack='Y'");
$getFYIRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id in ($ReportingTeam) and module_name='Resignation Management' and rep_ack='Y'");
$getFYIHR = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Resignation Management' and hr_ack='Y' and employee_id!='$employeeid'");
$getFYIHRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Resignation Management' and hrm_ack='Y' and employee_id!='$employeeid'");


$getFYIRes = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id='$employeeid' and module_name='Resource Management' and emp_ack='Y'");
$getFYIRMRes = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id in ($ReportingTeam) and module_name='Resource Management' and rep_ack='Y'");
$getFYIHRRes = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Resource Management' and hr_ack='Y' and employee_id!='$employeeid'");
$getFYIHRMRes = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Resource Management' and hrm_ack='Y' and employee_id!='$employeeid'");

$getFYIBoarding = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id='$employeeid' and module_name='Boarding' and emp_ack='Y'");
$getFYIBoardingRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where  employee_id in ($ReportingTeam) and module_name='Boarding' and rep_ack='Y'");
$getFYIBoardingHR = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Boarding' and hr_ack='Y' and employee_id!='$employeeid'");
$getFYIBoardingHRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Boarding' and hrm_ack='Y' and employee_id!='$employeeid'");

$getFYIDetails = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id='$employeeid' and module_name='Employee Info' and emp_ack='Y'");

$getFYIDetailsRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where  employee_id in ($ReportingTeam) and module_name='Employee Info' and rep_ack='Y'");

$getFYIDetailsHR = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Employee Info' and hr_ack='Y' and employee_id!='$employeeid'");
$getFYIDetailsHRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where module_name='Employee Info' and hrm_ack='Y' and employee_id!='$employeeid'");


$getFYIDetailsLeave = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where employee_id='$employeeid' and module_name in ('Attendance Management','Leave Management') and emp_ack='Y'");

$getFYIDetailsLeaveRM = mysqli_query($db,"select transaction_id,employee_id,employee_name,transaction,message,module_name,date_format(date(date_of_message),'%D %b, %Y') as date,days_count from fyi_transaction where  employee_id in ($ReportingTeam) and module_name in ('Attendance Management','Leave Management') and rep_ack='Y'");
?>
<fieldset>
 <legend style="border-bottom: 1px solid #555"><h4>General Info.</h4></legend>
  <div class="box box-primary">
              <div class="box-header with-border" style="background-color: 
   lightblue;
    color: black;">
              <i class="fa fa-fw fa-info-circle"></i>
              <h3 class="box-title">FYI 
			  </h3>
 <button id="AckAll" type="button" style="background-color: darkblue;" class="btn btn-success pull-right">Acknowledge All</button>
            </div>
            <div class="box-body" id="InfoTasks">
               <div class="table-responsive">
			   <?php
			   if(mysqli_num_rows($getFYI)>0)
			   {
			   ?>
			     <h5 class="box-title">Your Info : Resignation Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>

					<th>Status</th>
					<th>Date</th>
					<th># Days in this Status</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIRow = mysqli_fetch_assoc($getFYI))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIRow['transaction']?></td>
					<td><?php echo $getFYIRow['message'].' : '.$getFYIRow['date']?></td>
					<td><?php echo $getFYIRow['days_count']?></td>
					<td><a href="AcknowledgeInfo.php?id=<?php echo $getFYIRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
 </tbody>
				    </table>
				<?php
			   }
				?>
				
                
                 <?php
			   if(mysqli_num_rows($getFYIDetailsLeave)>0)
			   {
			   ?>
			     <h5 class="box-title">Your Info : Attendance Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>

					<th>Transaction</th>
					<th>Date of Message</th>
					<th>Message</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIDetailsLeaveRow = mysqli_fetch_assoc($getFYIDetailsLeave))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIDetailsLeaveRow['transaction']?></td>
					<td><?php echo $getFYIDetailsLeaveRow['date']?></td>
					<td><?php echo $getFYIDetailsLeaveRow['message']?></td>
					<td><a href="AcknowledgeInfo.php?id=<?php echo $getFYIDetailsLeaveRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
			</tbody>
				    </table>
				<?php
			   }
				?>
                
                
				<?php
			   if(mysqli_num_rows($getFYIRM)>0 && $ReportingTeam!='' && $userRole!='HR Manager')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">Your Team Info : Resignation Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Status</th>
					<th>Date</th>
					<th># Days in this Status</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYITeamRow = mysqli_fetch_assoc($getFYIRM))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYITeamRow['employee_id']?></td>
					<td><?php echo $getFYITeamRow['employee_name']?></td>
					<td><?php echo $getFYITeamRow['transaction']?></td>
					<td><?php echo $getFYITeamRow['message'].' : '.$getFYITeamRow['date']?></td>
					<td><?php echo $getFYITeamRow['days_count']?></td>
					<td><a href="AcknowledgeInfoRep.php?id=<?php echo $getFYITeamRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
 </tbody>
				    </table>
				<?php
			   }
				?>
                
                <?php
			   if(mysqli_num_rows($getFYIDetailsLeaveRM)>0 && $ReportingTeam!='')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">Your Team Info : Attendance Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Date of Message</th>
					<th>Message</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIDetailsLeaveRMRow = mysqli_fetch_assoc($getFYIDetailsLeaveRM))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIDetailsLeaveRMRow['employee_id']?></td>
					<td><?php echo $getFYIDetailsLeaveRMRow['employee_name']?></td>
					<td><?php echo $getFYIDetailsLeaveRMRow['date']?></td>
					<td><?php echo $getFYIDetailsLeaveRMRow['message'];?></td>
					<td><a href="AcknowledgeInfoRep.php?id=<?php echo $getFYIDetailsLeaveRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
		</tbody>
				    </table>
				<?php
			   }
				?>
                				  
				<?php
			   if(mysqli_num_rows($getFYIHR)>0 && $userRole=='HR')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">All Employee Info : Resignation Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Status</th>
					<th>Date</th>
					<th># Days in this Status</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIHRRow = mysqli_fetch_assoc($getFYIHR))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIHRRow['employee_id']?></td>
					<td><?php echo $getFYIHRRow['employee_name']?></td>
					<td><?php echo $getFYIHRRow['transaction']?></td>
					<td><?php echo $getFYIHRRow['message'].' : '.$getFYIHRRow['date']?></td>
					<td><?php echo $getFYIHRRow['days_count']?></td>
					<td><a href="AcknowledgeInfoHR.php?id=<?php echo $getFYIHRRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
                  </tbody>
                </table>
				<?php
			   }
				?>

				<?php
			   if(mysqli_num_rows($getFYIHRM)>0 && $userRole=='HR Manager')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">All Employee Info : Resignation Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Status</th>
					<th>Date</th>
					<th># Days in this Status</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIHRMRow = mysqli_fetch_assoc($getFYIHRM))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIHRMRow['employee_id']?></td>
					<td><?php echo $getFYIHRMRow['employee_name']?></td>
					<td><?php echo $getFYIHRMRow['transaction']?></td>
					<td><?php echo $getFYIHRMRow['message'].' : '.$getFYIHRMRow['date']?></td>
					<td><?php echo $getFYIHRMRow['days_count']?></td>
					<td><a href="AcknowledgeInfoHRM.php?id=<?php echo $getFYIHRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
                  </tbody>
                </table>
				<?php
			   }
				?>



				<br>
				 <?php
			   if(mysqli_num_rows($getFYIRes)>0)
			   {
			   ?>
			     <h5 class="box-title">Your Info : Designation & Projects</h5>
                <table class="table table-striped">
				<thead>
                  <tr>

					<th>Transaction</th>
					<th>Modified Value</th>
					<th>Effective From</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIResRow = mysqli_fetch_assoc($getFYIRes))
				 {
				 ?>

					<tr>

					<td><?php echo $getFYIResRow['transaction']?></td>
					<td><?php echo $getFYIResRow['message']?></td>
					<td><?php echo $getFYIResRow['date']?></td>
					<td><a href="AcknowledgeInfo.php?id=<?php echo $getFYIResRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>


				 </tbody>
				    </table>
            <?php
             }
            ?>

            <?php
            if(mysqli_num_rows($getFYIBoarding)>0)
            {
            ?>
              <h5 class="box-title">Your Info : Services Confirmation</h5>
                   <table class="table table-striped">
           <thead>
                     <tr>

             <th>Confirmation / Extenstion</th>
             <th>Message</th>
             <th>Effective From</th>
             <th>Acknowledge</th>

                     </tr>
               </thead>
             <tbody>
             <?php
            while($getFYIBoardingRow = mysqli_fetch_assoc($getFYIBoarding))
            {
            ?>

             <tr>

             <td><?php echo $getFYIBoardingRow['transaction']?></td>
             <td><?php echo $getFYIBoardingRow['message']?></td>
             <td><?php echo $getFYIBoardingRow['date']?></td>
             <td><a href="AcknowledgeInfo.php?id=<?php echo $getFYIBoardingRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
               </tr>

           <?php
            }
           ?>


            </tbody>
               </table>
               <?php
                }
               ?>

 <?php
            if(mysqli_num_rows($getFYIDetails)>0)
            {
            ?>
              <h5 class="box-title">Your Info : Employee Details</h5>
                   <table class="table table-striped">
           <thead>
                     <tr>

             <th>Transaction</th>
             <th>Message</th>
             <th>Date of Modification</th>
             <th>Acknowledge</th>

                     </tr>
               </thead>
             <tbody>
             <?php
            while($getFYIDetailsRow = mysqli_fetch_assoc($getFYIDetails))
            {
            ?>

             <tr>

             <td><?php echo $getFYIDetailsRow['transaction']?></td>
             <td><?php echo $getFYIDetailsRow['message']?></td>
             <td><?php echo $getFYIDetailsRow['date']?></td>
             <td><a href="AcknowledgeInfo.php?id=<?php echo $getFYIDetailsRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
               </tr>

           <?php
            }
           ?>


            </tbody>
               </table>
               <?php
                }
               ?>


				<?php
			   if(mysqli_num_rows($getFYIRMRes)>0 && $ReportingTeam!='' && $userRole!='HR Manager')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">Your Team Info : Resource Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Transaction</th>
					<th>Modified Value</th>
					<th>Effective From</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIRMResRow = mysqli_fetch_assoc($getFYIRMRes))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIRMResRow['employee_id']?></td>
					<td><?php echo $getFYIRMResRow['employee_name']?></td>
					<td><?php echo $getFYIRMResRow['transaction']?></td>
					<td><?php echo $getFYIRMResRow['message']?></td>
					<td><?php echo $getFYIRMResRow['date']?></td>
					<td><a href="AcknowledgeInfoRep.php?id=<?php echo $getFYIRMResRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>


				   </tbody>
				    </table>

            <?php
             }
            ?>


            <?php
             if(mysqli_num_rows($getFYIBoardingRM)>0 && $ReportingTeam!='' && $userRole!='HR Manager')
             {
             ?>
             <br>
               <h5 class="box-title">Your Team Info : Boarding</h5>
                    <table class="table table-striped">
            <thead>
                      <tr>
                      <th>Employee ID</th>
                      <th>Employee Name</th>
              <th>Confirmation / Extension</th>
              <th>Effective From</th>
              <th>Acknowledge</th>

                      </tr>
                </thead>
              <tbody>
              <?php
             while($getFYIBoardingRMRow = mysqli_fetch_assoc($getFYIBoardingRM))
             {
             ?>

              <tr>
              <td><?php echo $getFYIBoardingRMRow['employee_id']?></td>
              <td><?php echo $getFYIBoardingRMRow['employee_name']?></td>
              <td><?php echo $getFYIBoardingRMRow['transaction']?></td>
              <td><?php echo $getFYIBoardingRMRow['date']?></td>
              <td><a href="AcknowledgeInfoRep.php?id=<?php echo $getFYIBoardingRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
                </tr>

            <?php
             }
            ?>


               </tbody>
                </table>

                <?php
                 }
                ?>


				 <?php
             if(mysqli_num_rows($getFYIDetailsRM)>0 && $ReportingTeam!='' && $userRole!='HR Manager')
             {
             ?>
             <br>
               <h5 class="box-title">Your Team Info : Employee Details</h5>
                    <table class="table table-striped">
            <thead>
                      <tr>
                      <th>Employee ID</th>
                      <th>Employee Name</th>
              <th>Transaction</th>
              <th>Message</th>
              <th>Date of Modification</th>
              <th>Acknowledge</th>

                      </tr>
                </thead>
              <tbody>
              <?php
             while($getFYIDetailsRMRow = mysqli_fetch_assoc($getFYIDetailsRM))
             {
             ?>

              <tr>
              <td><?php echo $getFYIDetailsRMRow['employee_id']?></td>
              <td><?php echo $getFYIDetailsRMRow['employee_name']?></td>
              <td><?php echo $getFYIDetailsRMRow['transaction']?></td>
              <td><?php echo $getFYIDetailsRMRow['message']?></td>
              <td><?php echo $getFYIDetailsRMRow['date']?></td>
              <td><a href="AcknowledgeInfoRep.php?id=<?php echo $getFYIDetailsRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
                </tr>

            <?php
             }
            ?>


               </tbody>
                </table>

                <?php
                 }
                ?>





				<?php
			   if(mysqli_num_rows($getFYIHRRes)>0 && $userRole=='HR')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">All Employee Info : Resource Management</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                     <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Transaction</th>
					<th>Modified Value</th>
					<th>Effective From</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIHRResRow = mysqli_fetch_assoc($getFYIHRRes))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIHRResRow['employee_id']?></td>
					<td><?php echo $getFYIHRResRow['employee_name']?></td>
					<td><?php echo $getFYIHRResRow['transaction']?></td>
					<td><?php echo $getFYIHRResRow['message']?></td>
					<td><?php echo $getFYIHRResRow['date']?></td>
					<td><a href="AcknowledgeInfoHR.php?id=<?php echo $getFYIHRResRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
                  </tbody>
                </table>
				<?php
			   }
				?>

        <?php
         if(mysqli_num_rows($getFYIBoardingHR)>0 && $userRole=='HR')
         {
         ?>
         <br>
           <h5 class="box-title">All Employee Info : Boarding</h5>
                <table class="table table-striped">
        <thead>
                  <tr>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Confirmation / Extension</th>
          <th>Effective From</th>
          <th>Acknowledge</th>

                  </tr>
            </thead>
          <tbody>
          <?php
         while($getFYIBoardingHRRow = mysqli_fetch_assoc($getFYIBoardingHR))
         {
         ?>

          <tr>
          <td><?php echo $getFYIBoardingHRRow['employee_id']?></td>
          <td><?php echo $getFYIBoardingHRRow['employee_name']?></td>
          <td><?php echo $getFYIBoardingHRRow['transaction']?></td>
          <td><?php echo $getFYIBoardingHRRow['date']?></td>
          <td><a href="AcknowledgeInfoHR.php?id=<?php echo $getFYIBoardingHRRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
            </tr>

        <?php
         }
        ?>
                  </tbody>
                </table>
        <?php
         }
        ?>
		
		
		<?php
         if(mysqli_num_rows($getFYIDetailsHR)>0 && $userRole=='HR')
         {
         ?>
         <br>
           <h5 class="box-title">All Employee Info : Details Modification</h5>
                <table class="table table-striped">
        <thead>
                  <tr>
          <th>Employee ID</th>
          <th>Employee Name</th>
          <th>Message</th>
          <th>Date of Modification</th>
          <th>Acknowledge</th>

                  </tr>
            </thead>
          <tbody>
          <?php
         while($getFYIDetailsHRRow = mysqli_fetch_assoc($getFYIDetailsHR))
         {
         ?>

          <tr>
          <td><?php echo $getFYIDetailsHRRow['employee_id']?></td>
          <td><?php echo $getFYIDetailsHRRow['employee_name']?></td>
          <td><?php echo $getFYIDetailsHRRow['message']?></td>
          <td><?php echo $getFYIDetailsHRRow['date']?></td>
          <td><a href="AcknowledgeInfoHR.php?id=<?php echo $getFYIDetailsHRRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
            </tr>

        <?php
         }
        ?>
                  </tbody>
                </table>
        <?php
         }
        ?>


				<?php
			   if(mysqli_num_rows($getFYIBoardingHRM)>0 && $userRole=='HR Manager')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">All Employee Info : Boarding</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                     <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Extension / Confirmation</th>
					<th>Effective From</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIBoardingHRMRow = mysqli_fetch_assoc($getFYIBoardingHRM))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIBoardingHRMRow['employee_id']?></td>
					<td><?php echo $getFYIBoardingHRMRow['employee_name']?></td>
					<td><?php echo $getFYIBoardingHRMRow['transaction']?></td>
					<td><?php echo $getFYIBoardingHRMRow['date']?></td>
					<td><a href="AcknowledgeInfoHRM.php?id=<?php echo $getFYIBoardingHRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
                  </tbody>
                </table>
				<?php
			   }
				?>

				
		<?php
			   if(mysqli_num_rows($getFYIDetailsHRM)>0 && $userRole=='HR Manager')
			   {
			   ?>
			   <br>
			     <h5 class="box-title">All Employee Info : Details Modification</h5>
                <table class="table table-striped">
				<thead>
                  <tr>
                     <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Message</th>
					<th>Effective From</th>
					<th>Acknowledge</th>

                  </tr>
				    </thead>
					<tbody>
				  <?php
				 while($getFYIDetailsHRMRow = mysqli_fetch_assoc($getFYIDetailsHRM))
				 {
				 ?>

					<tr>
					<td><?php echo $getFYIDetailsHRMRow['employee_id']?></td>
					<td><?php echo $getFYIDetailsHRMRow['employee_name']?></td>
					<td><?php echo $getFYIDetailsHRMRow['message']?></td>
					<td><?php echo $getFYIDetailsHRMRow['date']?></td>
					<td><a href="AcknowledgeInfoHRM.php?id=<?php echo $getFYIDetailsHRMRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
				    </tr>

				<?php
			   }
				?>
                  </tbody>
                </table>
				<?php
			   }
				?>		
				

        <?php
         if(mysqli_num_rows($getFYIHRMRes)>0 && $userRole=='HR Manager')
         {
         ?>
         <br>
           <h5 class="box-title">All Employee Info : Resource Management</h5>
                <table class="table table-striped">
        <thead>
                  <tr>
                     <th>Employee ID</th>
                    <th>Employee Name</th>
          <th>Transaction</th>
          <th>Modified Value</th>
          <th>Effective From</th>
          <th>Acknowledge</th>

                  </tr>
            </thead>
          <tbody>
          <?php
         while($getFYIHRMResRow = mysqli_fetch_assoc($getFYIHRMRes))
         {
         ?>

          <tr>
          <td><?php echo $getFYIHRMResRow['employee_id']?></td>
          <td><?php echo $getFYIHRMResRow['employee_name']?></td>
          <td><?php echo $getFYIHRMResRow['transaction']?></td>
          <td><?php echo $getFYIHRMResRow['message']?></td>
          <td><?php echo $getFYIHRMResRow['date']?></td>
          <td><a href="AcknowledgeInfoHRM.php?id=<?php echo $getFYIHRMResRow['transaction_id'] ?>"><img alt="user" height="18px" width="18px" src="images/ack.png"> </a></td>
            </tr>

        <?php
         }
        ?>
                  </tbody>
                </table>
        <?php
         }
        ?>



<?php
$getteamLop = mysqli_query($db,"SELECT a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.mi) as name,
date,check_in,check_out,a.Remarks FROM `lop_details` a inner join employee_details b on a.employee_id=b.employee_id

 where a.employee_id in ($ReportingTeam)");
?>
		 <?php
				if($ReportingTeam!='' && mysqli_num_rows($getteamLop)>0)
				{
				?>
				 <h5 class="box-title">Team Info : <strong>LOP Details for <?php  echo ' '.date('F'); ?></strong></h5>
				 <table class="table table-striped">
				  <thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
					<th>Date</th>
					<th>Check-in</th>
					<th>Check-Out</th>
					<th>Remarks</th>

                  </tr>
                  </thead>
				 <tbody>
				 <?php
				 while($getteamLopRow = mysqli_fetch_assoc($getteamLop))
				 {
				 ?>
					<tr>
					<td><?php echo $getteamLopRow['employee_id']?></td>
					<td><?php echo $getteamLopRow['name']?></td>
					<td><?php echo $getteamLopRow['date']?></td>
					<td><span class="badge bg-blue"><?php echo $getteamLopRow['check_in']?></td>
					<td><span class="badge bg-blue"><?php echo $getteamLopRow['check_out']?></td>
					<td><?php echo $getteamLopRow['Remarks']?></td>
				  </tr>

				  <?php
				 }
				  ?>
				    </tbody>
                </table>
				 <?php
				}
				 ?>
              </div>
            </div>
            <!-- /.box-body-->
          </div>
</fieldset>

	<?php
if($UserRole=='HR Manager' || $UserRole=='HR')
{
?>

	   <div class="row">
        <div class="col-md-6">
          <!-- Line chart -->
         <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Employee(s) By Department</h3>
               <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">



                 <div id="piechart"></div>

                  <!-- ./chart-responsive -->

                <!-- /.col -->

                <!-- /.col -->

              <!-- /.row -->
            </div>
            <!-- /.box-body -->

            <!-- /.footer -->
          </div>
          <!-- /.box -->

          <!-- Area chart -->
          <!-- /.box -->

        </div>
        <!-- /.col -->

        <div class="col-md-6">
		<?php
		$getShiftAttendance = mysqli_query($db,"SELECT present,total,a.shift_code,concat(b.shift_code,' (',start_time,' - ',end_time,' )') as Shift_Time

FROM `present_status_tbl` a left join shift b on a.shift_code=b.shift_code;");

$TotalAttendance = mysqli_query($db,"select sum(present) as Present,sum(total) as Total from present_status_tbl");
$TotalAttendanceRow = mysqli_fetch_array($TotalAttendance);
$PresentEmp = $TotalAttendanceRow['Present'];
$TotalEmp = $TotalAttendanceRow['Total'];





		?>

          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Attendance : Present: <strong><?php echo $PresentEmp.' '; ?></strong>Total:  <strong><?php echo $TotalEmp.'. '; ?></strong></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
             <div class="box-body" id="aTTDetails">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Shift Code</th>
                    <th>Total Employees</th>
                    <th>Present</th>
					<th>Absent</th>

                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  while($getShiftAttendanceRow = mysqli_fetch_assoc($getShiftAttendance))
				  {
				  ?>
					 <tr>
						<td><?php 
						if($getShiftAttendanceRow['Shift_Time']!='') 
						{
							echo $getShiftAttendanceRow['Shift_Time'];
					} 
					else
							{
								echo 'OS7 (10:00:00 - 19:00:00)';
							}?>   </td>
						<td><span class="badge bg-blue"><?php echo $getShiftAttendanceRow['total']; ?></span></td>
						<td><span class="badge bg-green"><?php echo $getShiftAttendanceRow['present']; ?></span></td>
						<td><span class="badge bg-red"><?php
							$absent = $getShiftAttendanceRow['total']-$getShiftAttendanceRow['present'];
						echo $absent; ?></span></td>
					</tr>
					
					<?php
				  }
					?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
      </div>

<?php
}
?>
		<?php
	$GetTeamCount = mysqli_query($db,"select * from employee_Details where reporting_manager_id='$userid'");
$TeamCount = mysqli_num_rows($GetTeamCount);
if($TeamCount>0)
{
	$HasTeam='Y';
}
if($HasTeam=='Y')
			{
?>
	  <div class="row">
        <!-- Left col -->
        <div class="col-md-12">    <!-- /.row -->
 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your Team</h3>
            </div>
		<?php
			include("pages/LeaveMgmt/Attendance_Config.php");
			$getTeamMem = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as Name,a.employee_id,employee_designation,shift_code,if(check_in='0001-01-01 00:00:00','Absent','Present') as status from

employee_details a inner join employee_shift b on a.employee_id=b.employee_id

inner join attendance c on a.employee_id=c.employee_id

where reporting_manager_id=$employeeid and curdate() between start_date and end_date

and c.shift_date=curdate() and a.is_active='Y'");
			?>
            <!-- /.box-header -->
            <div class="box-body" id="TeamDetails">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
					<th>Designation</th>
					<th>Current Shift Code</th>
					<th>Today's Status</th>

                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  
				  while($getTeamMemRow = mysqli_fetch_assoc($getTeamMem))
				  {
				  ?>
                  <tr>
                    <td><?php echo $getTeamMemRow['employee_id']; ?></td>
                    <td><?php echo $getTeamMemRow['Name']; ?></td>
                    <td><?php echo $getTeamMemRow['employee_designation']; ?></td>
					<td><?php echo $getTeamMemRow['shift_code']; ?></td>
					<?php
					if($getTeamMemRow['status']=='Present')
					{
					?>

					<td><span class="label label-success">Checked-in</span></td>
                   <?php
					}
					else
					{
                    	$status='';
                    	$getLeaveRows = mysqli_query($db,"select * from leave_Status where employee_id='".$getTeamMemRow['employee_id']."' and date_availed=curdate()");
                       $getLeaveReqRows = mysqli_query($db,"select * from leave_request where employee_id='".$getTeamMemRow['employee_id']."' and curdate() between leave_from and leave_to and is_active='Y'");

                    	if(mysqli_num_rows($getLeaveRows)>0)
                        {
                        	$getLeaveRowsRow = mysqli_fetch_array($getLeaveRows);
                        	$status = $getLeaveRowsRow['Leave_type'].'';
                        }
                    	if(mysqli_num_rows($getLeaveReqRows)>0)
                        {
                        	$getLeaveReqRowsRow = mysqli_fetch_array($getLeaveReqRows);
                        	if($getLeaveReqRowsRow['leave_type']=='Sick')
                            {
                            	$status = 'SL Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='Casual')
                            {
                            	$status = 'CL Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='Privilege')
                            {
                            	$status = 'PL Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='Casual & Sick')
                            {
                            	$status = 'CL & SL Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='Privilege & Sick')
                            {
                            	$status = 'Pl & SL Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='Compensatory-Off')
                            {
                            	$status = 'C-Off Requested';
                            }
                        	if($getLeaveReqRowsRow['leave_type']=='On-Duty')
                            {
                            	$status = 'OD Requested';
                            }
                        }
                    if($status=='')
                    {
				   ?>
				    <td><span class="label label-danger">Absent</span></td>
				  <?php
                    }
                    else
                    {
                    
                    ?>
                  <td><span class="label label-warning"><?php echo $status ?></span></td>
                    <?php
                    }
					}
				  ?>
                  </tr>

				  <?php
				  }
				  ?>

                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>
		  </div>
	  </div>

	<?php
}
?>

<fieldset>
  <legend style="border-bottom: 1px solid #555"><h4>Events & Announcements</h4></legend>
    <div class="row">
        <!-- Left col -->
        <div class="col-md-8">    <!-- /.row -->
 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Day's Thought  :<small><?php echo '  Added by <strong>'.$ImageAddedBY.'</strong> on <strong>'.$ImageAddedDate.'</strong>.' ?> </small> </h3>
            </div>
			<?php
			$getAnnouncements = mysqli_query($db,"select id,date_format(date_of_news,'%D %M, %Y') as `date`, news_content,status,business_unit from company_announcements where month(date_of_news)=month(curdate()) and year(date_of_news)=year(curdate()) order by date_of_news desc");
			$getDailyInfo = mysqli_query($db,"select value from application_configuration where config_type='DAILY_IMAGE' and module='DASHBOARD'");
			$getDailyInforRow = mysqli_fetch_array($getDailyInfo);
			$DailyInfo = $getDailyInforRow['value'];
			$DailyInfoArray = explode("-",$DailyInfo);			
 		?>
            <!-- /.box-header -->
            <div class="box-body" id="announcements1" style="height:450px;">
               <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="height: 100%;">
                
                <ol class="carousel-indicators">
                <?php  
					$cntval =0;
					foreach($DailyInfoArray as $row)
                    { 
                    	if($row!='')
    					{
                     	if($cntval==0)
                        {
				?>
                  <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cntval; ?>" class="active"></li>
                
                <?php 
                        }
                    else
                    {
                 ?>
                 <li data-target="#carousel-example-generic"  data-slide-to="<?php echo $cntval; ?>" class=""></li>	
                <?php
                    }
                        $cntval++;
                        }
                    
                    }      
                  ?>
                </ol>
                <div class="carousel-inner" style="height: 100%;">
                  <?php  
					$cntval =0;
					foreach($DailyInfoArray as $row)
                    { 
                    	if($row!='')
    					{
                     	if($cntval==0)
                        {
					?>
                	
                  <div class="item active">
                    <img src="<?php echo $row; ?>" style="width:100%;height: -webkit-fill-available;" alt="<?php echo $cntval; ?>">
                  </div>
                	 <?php 
                        }
                    else
                    {
                 ?>
                
                  <div class="item">
                    <img src="<?php echo $row; ?>" style="width:100%;height: -webkit-fill-available;" alt="<?php echo $cntval; ?>">
                  </div>
                <?php
                    }
                        $cntval++;
                        }
                    
                    }      
                  ?>
                
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
			<?php
			if($UserRole=='HR' || $UserRole=='HR Manager')
			{
			?>
            <div class="box-footer clearfix">
			<a href="#" class="btn btn-sm btn-info btn-flat pull-left" id="additionalMod" title="Click to Add New Image" data-toggle="modal" data-target="#modal-default-Image">Add New</a>
            </div>

			<?php
			}
			else
			{
			?>
			 <div class="box-footer clearfix">

            </div>


			<?php
			}
			?>
            <!-- /.box-footer -->
          </div>
		  </div>
		    <div class="col-md-4">
			<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Holiday's for <?php echo ' '.date('Y'); ?></h3>
			<?php
	   $getHolidays =

	   mysqli_query($db,"SELECT date_format(date,'%M')as month,date_format(date,'%D') as date_alone,day,date,is_applied_to_india,is_applied_to_us,is_applied_to_hyd,is_applied_to_noida,description
 FROM `holiday_list`
where year(date)=year(curdate()) and remarks not in ('1st Saturday','2nd Saturday','3rd Saturday','4th Saturday','Sunday','5th Saturday')
 order by date asc;");
			?>

            </div>
            <!-- /.box-header -->
            <div class="box-body" id="Holidays">
              <ul class="products-list product-list-in-box">
			<?php
			while($getHolidayRow = mysqli_fetch_assoc($getHolidays))
			{

			?>
                <li class="item">
                  <div class="product-img">
					<div class="date">
						<span class="binds"></span>
						<span class="month"><?php echo $getHolidayRow['month'];  ?></span>
						<h1 class="day"><?php echo $getHolidayRow['date_alone'];  ?></h1>
						</div>
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" style="padding: 5px;" class="product-title"><?php echo $getHolidayRow['description'];  ?>




			<?php
			if($getHolidayRow['is_applied_to_india'] =='Y' && $getHolidayRow['is_applied_to_us']=='Y' && $getHolidayRow['is_applied_to_hyd']=='Y' && $getHolidayRow['is_applied_to_noida']=='Y')
			{
			?>
					  <span class="label label-success pull-right">All Business Units</span></a>
			<?php
			}
			if($getHolidayRow['is_applied_to_india'] =='Y' && $getHolidayRow['is_applied_to_us']=='N' && $getHolidayRow['is_applied_to_hyd']=='Y' && $getHolidayRow['is_applied_to_noida']=='Y')
			{
			?>
				<span class="label label-info pull-right">All Indian Business Units</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='N' && $getHolidayRow['is_applied_to_us']=='Y' && $getHolidayRow['is_applied_to_hyd']=='N' && $getHolidayRow['is_applied_to_noida']=='N')
			{
			?>
			  <span class="label label-info pull-right">All US Business Units</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='Y' && $getHolidayRow['is_applied_to_hyd']=='N' && $getHolidayRow['is_applied_to_noida']=='N')
			{
			?>
			   <span class="label label-warning pull-right">Chennai</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='N' && $getHolidayRow['is_applied_to_hyd']=='Y' && $getHolidayRow['is_applied_to_noida']=='N')
			{
			?>
			   <span class="label label-warning pull-right">Hyderabad</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='N' && $getHolidayRow['is_applied_to_hyd']=='N' && $getHolidayRow['is_applied_to_noida']=='Y')
			{
			?>
			   <span class="label label-warning pull-right">Noida</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='Y' && $getHolidayRow['is_applied_to_hyd']=='Y' && $getHolidayRow['is_applied_to_noida']=='N')
			{
			?>
			   <span class="label label-info pull-right">Chennai & Hyderabad</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='Y' && $getHolidayRow['is_applied_to_hyd']=='N' && $getHolidayRow['is_applied_to_noida']=='Y')
			{
			?>
			   <span class="label label-info pull-right">Chennai & Noida</span></a>
			<?php
			}
			?>
			<?php
			if($getHolidayRow['is_applied_to_india'] =='N' && $getHolidayRow['is_applied_to_hyd']=='Y' && $getHolidayRow['is_applied_to_noida']=='Y')
			{
			?>
			   <span class="label label-info pull-right">Hyderabad & Noida</span></a>
			<?php
			}

			?>









                    <span style="padding: 5px;" class="product-description">
                        <?php echo $getHolidayRow['day']; ?>
                        </span>
                  </div>
                </li>



			<?php
			}
			?>

                <!-- /.item -->

                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
           <!-- /.box-footer -->
          </div>
			</div>
	  </div>
 <div class="row">
        <!-- Left col -->
        <div class="col-md-8">    <!-- /.row -->
 <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Company Announcements</h3>
            </div>
			<?php
			$getAnnouncements = mysqli_query($db,"select id,date_format(date_of_news,'%D %M, %Y') as `date`, news_content,status,business_unit,created_by from company_announcements where month(date_of_news)=month(curdate()) and year(date_of_news)= year(curdate())  order by date_of_news desc");
			?>
            <!-- /.box-header -->
            <div class="box-body" id="announcements">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Posted Date</th>
                    <th>Title</th>
        		     <th>Posted By</th>
					<th>Business Unit</th>
					<th>View Content</th>

                  </tr>
                  </thead>
                  <tbody>
				  <?php
				  while($getAnnouncementRow = mysqli_fetch_assoc($getAnnouncements))
				  {
				  ?>
                  <tr>
                    <td><?php echo $getAnnouncementRow['date']; ?></td>
                    <td><?php echo $getAnnouncementRow['news_content']; ?></td>
                  <td><?php echo $getAnnouncementRow['created_by']; ?></td>
					<td><span class="label label-success"><?php echo $getAnnouncementRow['business_unit']; ?></span></td>
					<td><a href="ViewAnnouncement.php?id=<?php echo $getAnnouncementRow['id'] ?>">View</a></td>
                  </tr>
				  <?php
				  }
				  ?>

                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
			<?php
			if($UserRole=='HR' || $UserRole=='HR Manager')
			{
			?>
            <div class="box-footer clearfix">
			<a href="#" class="btn btn-sm btn-info btn-flat pull-left" id="additionalMod" title="Click to Add New Announcements" data-toggle="modal" data-target="#modal-default-Dept">Add New</a>
            </div>

			<?php
			}
			else
			{
			?>
			 <div class="box-footer clearfix">

            </div>


			<?php
			}
			?>
            <!-- /.box-footer -->
          </div>
		  </div>
		   <div class="col-md-4">
			<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Birthday / Anniversary Folks</h3>
			<?php
	   $getevents = mysqli_query($db,"select id,employee_id,concat(Name,' : ',employee_id) as name,event_type,event_content,date_format(date_of_event,'%W') as day_of_event,
date_format(date_of_event,'%D %M') as date_of_event from birthdays_anniversaries where
 month(date_of_event)=month(curdate()) order by date_format(date_of_event,'%d-%M-%Y') asc;");
			?>

            </div>
            <!-- /.box-header -->
            <div class="box-body" id="bdays" style="height:300px;">
              <ul class="products-list product-list-in-box">
			  <?php
			  if(mysqli_num_rows($getevents)>0)
			  {
			  while ($EventsRow = mysqli_fetch_assoc($getevents))
			  {
			  ?>
                 <li class="item">
                  <div class="product-img">
				 <?php
					if($EventsRow['event_type']=='Birthday')
					{
					?>
                    <img src="dist/img/birthday-icon-png.png" alt="Product Image">
					 <?php
					}
					else

						{
					  ?>
					   <img src="dist/img/49504826-blank-anniversary-icon-add-your-number.jpg" alt="Product Image">
					 <?php
					}
					?>
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title"><?php echo $EventsRow['name'];  ?>
                    <?php  if($_SESSION['login_user']!=$EventsRow['employee_id']) { ?>
                     <span class="label label-info pull-right sendMessage" data-src="<?php echo $EventsRow['id'];  ?>" data-toggle="modal" data-target="#modal-default-Message">Send Message</span>
                    <?php } ?>
				</a>
                    <span class="product-description">
                         <?php echo $EventsRow['date_of_event'].' - '.$EventsRow['event_content'];  ?>
                        </span>
                  </div>
                </li>

			  <?php
			  }
			  }
			  else
			  {
				?>

				<DIV id="today_bday_name" class="to_daybirth no_birthday"> No Birthday / Anniversary Today. </DIV>

				<?php

			  }
				?>
                <!-- /.item -->

                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
           <!-- /.box-footer -->
          </div>
			</div>
	  </div>





</fieldset>

 <div class="modal fade" id="modal-default-Dept">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Announcement</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">

            <form id="AnnouncementForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="AddAnnouncement.php">
              <div class="box-body">
                <div class="form-group">

               <label for="inputEmail3" class="col-sm-2 control-label">Title</label>

                  <div class="col-sm-10">
                    <input type="text"  class="form-control" id="inputDept" name="inputDept" placeholder="Enter Title" required="required"/>

                  </div>




              </div>
			    <div class="form-group">

               <label for="inputEmail3" class="col-sm-2 control-label">Business Unit</label>

                  <div class="col-sm-10">
                      <select class="form-control select2" id="BuSelect" name="BuSelect" style="width: 100%;" required>
						<option value="" selected disabled>Please Select From Below</option>
						<option value="Everyone">Everyone</option>
						<option value="Chennai">Chennai</option>
						<option value="Hyderabad">Hyderabad</option>
						<option value="Noida">Noida</option>
						<option value="Pomona">Pomona</option>
						<option value="Pomona">Pomona</option>
						<option value="Rancho Cucamonga">Rancho Cucamonga</option>
                    </select>
                  </div>


			</a>

              </div>
		<div class="form-group">
		 <div class="col-sm-10">
		  <label for="inputEmail3" class="col-sm-2 control-label">Content</label>
				<textarea rows="4" cols="88" id="ContentText" name="ContentText">

				</textarea>
		 </div>
		</div>
              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddProj" value="Add Announcement" class="btn btn-primary"  />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>

<?php 
			$getDailyInfo = mysqli_query($db,"select value from application_configuration where config_type='DAILY_IMAGE' and module='DASHBOARD'");
			$getDailyInforRow = mysqli_fetch_array($getDailyInfo);
			$DailyInfo = $getDailyInforRow['value'];
			$DailyInfoArray = explode("-",$DailyInfo);

?>




		 <div class="modal fade" id="modal-default-Image">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Image(s)</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">

            <form id="ProForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="AddImage.php">
              <div class="box-body">
                <div class="form-group">

               <label for="inputEmail3" class="col-sm-2 control-label">Choose Image File(s)</label>

                  <div class="col-sm-10">
                    <input type="file"  class="form-control" id="ResumeFileDoc" name="ResumeFileDoc[]" multiple="multiple" placeholder="Enter Title" required="required"/>
                	<br> <span class="text-red">*
    Hold Ctrl / Shift Button to Select Multiple Files</span><br>
<span class="text-red">*
    Accepted Formats : jpg, png, jpeg</span>  
                </div>
	<div class="col-md-12">
      <?php
        if(isset($DailyInfoArray))
        {
			$CntRec = count($DailyInfoArray);
       ?>
      <br>
      <br>
	  <input type="hidden" id="ReceiptCnt" name="ReceiptCnt" value="<?php echo $CntRec;  ?>">
      <p class="lead" style="font-weight: 700;font-size: 16px;"><strong>Existing Images</strong></p>
      <table id="uploadedFiles" style="width:100%" class="cell-border uploadedFiles table table-striped table1 table-bordered table-hover dataTable">
          <thead>
                <tr>
                  <th><strong>Image</strong></th>
                  <th><strong>Detach File</strong></th>
                </tr>
        </thead>
        <tbody id="uploadReceipts">
        <?php  foreach ($DailyInfoArray as $key => $value) { 
        		if($value!='')
    				{
        	?>
              		<tr data-src="<?php echo $value ?>" style="cursor:pointer">
					
					<td><a target="_blank" href="<?php echo $value ?>">
  						<img src="<?php echo $value ?>" style=" border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 135px;height: 120px;margin-left: 100px;" alt="Forest" style="width:150px"></a></td>
                    
					<td><a href="#" id="deleteReceipts" class="deleteReceipt"><i class="fa fa-trash-o" ></i></a></td>
	 </tr>	
        <?php } } ?>
        </tbody>
      </table>
        <br>
        <br>
          <?php
            }
       ?>
     </div>



              </div>


              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" onClick="window.location.reload();" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="AddProj" value="Add Image" class="btn btn-primary"  />
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>


	<div class="modal fade" id="modal-default-Message">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #00A8B3;color: white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send B'day / Anniversary Message</h4>
              </div>
            
            <div class="modal-body" style="font-family: 'Open Sans', sans-serif;">
               	<form class="form-horizontal" role="form" id="MessageForm" method="post">
                							<input type="hidden" id="MessageEmpId" name="MessageEmpId" value="">
                							<input type="hidden" id="MessageName" name="MessageName" value="">
                                              <div class="form-group">
                                                  <label class="col-lg-2 control-label">To</label>
                                                  <div class="col-lg-10">
                                                      <input type="text" class="form-control" id="toMail" name="toMail" placeholder="" readonly>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label class="col-lg-2 control-label">Subject</label>
                                                  <div class="col-lg-10">
                                                      <input type="text" class="form-control" id="Subject" name="Subject" placeholder="" readonly>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label class="col-lg-2 control-label">Message</label>
                                                  <div class="col-lg-10">
                                                      <textarea name="MessageText" id="MessageText" class="form-control" cols="30" rows="10"></textarea>
                                                  </div>
                                              </div>
                                          
            </div>
            
              </div>
          
              	<div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				  <input type="submit"  id="WishSubmit" value="Send Message" class="btn btn-primary"  />
                </form>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>

<a href="#" id="btnEMpl" style="display:none;" target="_blank" data-toggle="modal" data-target="#modal-default-Empl" class="btn btn-danger pull-right">Skip Upload</a>

<div class="modal fade" id="modal-default-Empl">
          <div class="modal-dialog">
            <div class="modal-content">
             <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Acurus HRMS</h4>
              </div>
            <div class="modal-body">
                <p>Message Sent Successfully!</p>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>

<div class="modal fade" id="modal-default-Empl1">
          <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
              
                <h4 class="modal-title">Acurus HRMS</h4>
              </div>
            <div class="modal-body" style="border: 1px solid black;">
                <?php include('BdayWishes.php'); ?>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="CloseNot" onclick="pagereload();" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                 
              </div>
            </div>
            <!-- /.modal-content -->
          </div>











		  <div class="modal fade" id="modal-default-Lop">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">LOP Details</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">

            <form id="ProForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="AddAnnouncement.php">
              <div class="box-body">
               <table class="table table-striped">
                <tr>

                  <th>Date of LOP</th>
                  <th>Shift Code</th>
                  <th>In-Time</th>
                  <th>Out-Time</th>
                  <th>Lop Type</th>
                  <th>Additional Remarks</th>
                </tr>
				<?php
				$getLopDetails = mysqli_query($db,"SELECT shift_code,date,remarks,time(check_in) as checkin,time(check_out) as checkout,remarks,permission_type FROM `lop_details` where employee_id=$employeeid");
				?>
              <?php
			  if(mysqli_num_rows($getLopDetails)>0)
			  {
				  while($getLopRow = mysqli_fetch_assoc($getLopDetails))
				  {
			  ?>
                <tr>
                  <td><?php echo $getLopRow['date']; ?></td>
                  <td><?php echo $getLopRow['shift_code']; ?></td>
                  <td><?php echo $getLopRow['checkin']; ?></td>
                  <td><?php echo $getLopRow['checkout']; ?></td>
              <?php
              if($getLopRow['permission_type']=='')
              {
              ?>
              
                <td><span class="badge bg-red">Full Day</td>
                  
              <?php
              }
                  else
                  {
              ?>
                <td><span class="badge bg-blue">Half Day</td>
              <?php
                  }
              ?>
              <td><?php echo $getLopRow['remarks']; ?></td>
                </tr>


			 <?php
			  }
		  }
			else
			{
			  ?>

			  <tr>
                  <td><span class="badge bg-green">Hooorayy! No LOP's this Month!</span></td>

				  </tr>
			 <?php
				}
			 ?>
              </table>

              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
              </div>
              <div class="modal-footer">
                <button type="button" id="closeRole" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>


 <div class="modal fade" id="modal-default-Config">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Application Configuration</h4>
              </div>
            <div class="modal-body">
               <div class="box box-info">
			<?php
			$getConfig = mysqli_query($db,"select parameter,value from application_configuration where parameter!='IMAGE'");
			?>
            <form id="AppForm" enctype="multipart/form-data" method="post" class="form-horizontal" action="AddAnnouncement.php">
              <div class="box-body">
			  <a href="#" class="btn btn-sm btn-info btn-flat pull-right" id="EditApp" title="Click to Add New Image">Enable Edit</a>
			  <br>
			  <br>
               <table id="ConfigTable" class="table table-striped">
                <tr>

                  <th>Parameter</th>
                  <th>Value</th>
                  <th>Save</th>
                </tr>
				
              <?php
			  if(mysqli_num_rows($getConfig)>0)
			  {
				  while($getConfigRow = mysqli_fetch_assoc($getConfig))
				  {
			  ?>
                <tr>
                  <td class="Parameter"><?php echo $getConfigRow['parameter']; ?></td>
                  <td class="value" contenteditable="false" id="<?php echo $getConfigRow['value']; ?>"><?php echo $getConfigRow['value']; ?></td>
				 <td><a href="#" class="saveButton"><i class='fa fa-save'></i></a></td>
                </tr>


			 <?php
			  }
		  }
	       ?>
              </table>
			  <br>
			  <br>
				<h5><strong>**Click on the Value Column to edit its Contents. Save to Update.</strong></h5>
              </div>
              <!-- /.box-body -->

              <!-- /.box-footer -->

          </div>
            </div>
			
              </div>
              <div class="modal-footer">
                <button type="button" id="closeAdd" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
















    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">

    <strong>Acurus Solutions Private Limited.</strong>
  </footer>

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script src="dist/js/canvasjs.min.js"></script>
<script src="bower_components/Flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="bower_components/Flot/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="bower_components/Flot/jquery.flot.pie.js"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="bower_components/Flot/jquery.flot.categories.js"></script>
<script src="bower_components/chart.js/Chart.js"></script>
<!-- page script -->
<script>
  $('#uploadedFiles').on('click', '.deleteReceipt', function(e)
  {
    debugger;
    var tr = $(this).closest('tr');
    ajaxindicatorstart("Please Wait..");
		$.ajax({
			url : "DeleteImg.php",
			method: "post",
			data : {
			id: tr.attr("data-src"),
			}
			}).done(function(data) {
			$("#uploadReceipts").html(data);
			//$("#nameModal_expenses").modal("show");
			ajaxindicatorstop();
		})
});
  </script>
<script>
$('.content-wrapper').on('click','.sendMessage', function() {
		var EmpId = $(this).attr("data-src");
  		$("#MessageEmpId").val($(this).attr("data-src"));
		$.ajax({
      url: 'getEmpDetails.php',
      type: 'post',
      dataType: "json",
      data: {
      	'empId' : EmpId,
      },
      success: function(data){
      		$('#MessageEmpId').val(data.result.employee_id);
			$('#MessageName').val(data.result.employee_name);
      		if(data.result.event_type=='Birthday')
            {
            		var sub = 'Birthday';
            		var quote = 'Wish you a very Happy Birthday '+data.result.employee_name+'. Have a great one!';
            }
      		if(data.result.event_type=='Wedding Anniversary')
                {
                	var sub = 'Wedding Anniversary';
                	var quote = 'Wish you a very Happy Wedding Anniversary '+data.result.employee_name+'. Have a great one!';
      			}
      if(data.result.event_type=='Work Anniversary')
                {
                	var sub = 'Work Anniversary';
                	var quote = 'Happy Work Anniversary '+data.result.employee_name+'. Wishing you a many more happy years at Acurus!';
      			}
      		$('#Subject').val('Happy '+sub+' '+data.result.employee_name+'!');
      		$('#toMail').val(data.result.contact_email);
            $('#MessageText').text('');
      		$('#MessageText').append(quote);
      		if(data.result.contact_email==null) {
               		$('#WishSubmit').prop('disabled', true);
               }
      		else
            {
            		$('#WishSubmit').prop('disabled', false);
            }
			ajaxindicatorstop();
      }
 	});
   		
  	});
</script>
<script>

	$(function() {
  $('#MessageForm').submit(function() {
  
  	  debugger;
      var mail = $('#toMail').val();
	  var Subject = $('#Subject').val();
	  var Msg = $('#MessageText').val();
      var MessageName = $('#MessageName').val();
  	  var MessageEmpId = $('#MessageEmpId').val();
	   $.ajax({
      url: 'sendMessage.php',
      type: 'post',
      data: {
      	'mail' : mail,
      	'Subject' : Subject,
       'Msg' : Msg,
      'MessageName' : MessageName,
      'MessageEmpId' : MessageEmpId,
      },
      success: function(response){
      $('#modal-default-Message').modal('hide');
			ajaxindicatorstop();
      $('#btnEMpl').click();
      }
 	});
	  
  });
});
</script>
<script>
$(document).ready(function() {
    $("#EditApp").click(function() {
		 var bool = $('.value').attr('contenteditable');
      if (bool == 'true') {
     $('.value').attr('contenteditable','false');
	 $("#EditApp").html('Enable Edit');
      }
      else {
	 $('.value').attr('contenteditable','true');
	 $("#EditApp").html('Disable Edit');
      }
    });
});
</script>
<script>

	$(function() {
  $('#ConfigTable tr').click(function() {
       parameter = $(this).find('.Parameter').text();
	   value = $(this).find(".value").text();
	 
	   $.ajax({
      url: 'UpdateConfig.php',
      type: 'post',
      data: {
      	'parameter' : parameter,
      	'value' : value,
      },
      success: function(response){
			ajaxindicatorstop();
      }
 	});
	  
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("form#AnnouncementForm").submit(function() {
      $('button[type=submit], input[type=submit]').prop('disabled',true);
        return true;
    });
});
</script>
<script type="text/javascript">
       $(document).on('click','#AckAll',function(e) {
		  debugger;
		   var data = $("#AnnouncementForm").serialize();
  ajaxindicatorstart("Please Wait..");
  $.ajax({
         data: data,
         type: "post",
         url: "AckAllInfo.php",
         success: function(data)
		 {
			location.reload();
			 ajaxindicatorstop(); 
         }
});
 });
    </script>
<script>
  $(function () {
    //"use strict";
	var cl = $('#ClTaken').val();
	var sl = $('#SlTaken').val();
	var pl = $('#PlTaken').val();
    //DONUT CHART
    var donut = new Morris.Donut({
      element: 'sales-chart',
      resize: true,
      colors: ["#3c8dbc", "#f56954", "#00a65a"],
      data: [
        {label: "Casual Leave", value: cl},
        {label: "Paid Leave", value: pl},
        {label: "Sick Leave", value: sl}
      ],
      hideHover: 'auto'
    });
     var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [

	  <?php  while($whrow = mysqli_fetch_assoc($workinghours))  { ?>

	  {y: '<?php echo $whrow['shift_Date'] ?>',a: <?php echo $whrow['wh']; ?> },
	  <?php  }   ?>
      ],
      barColors: ['#3c8dbc'],
      xkey: 'y',
      ykeys: ['a'],
	ymin: 0,
    ymax: 24,
    numLines: 7,
      labels: ['# of Working Hours'],
      hideHover: 'auto'
    });
  });
</script>
<script>
  $(function () {
    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 100

    function getRandomData() {

      if (data.length > 0)
        data = data.slice(1)

      // Do a random walk
      while (data.length < totalPoints) {

        var prev = data.length > 0 ? data[data.length - 1] : 50,
            y    = prev + Math.random() * 10 - 5

        if (y < 0) {
          y = 0
        } else if (y > 100) {
          y = 100
        }

        data.push(y)
      }

      // Zip the generated y values with the x values
      var res = []
      for (var i = 0; i < data.length; ++i) {
        res.push([i, data[i]])
      }

      return res
    }

    var interactive_plot = $.plot('#interactive', [getRandomData()], {
      grid  : {
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#3c8dbc'
      },
      lines : {
        fill : true, //Converts the line chart to area chart
        color: '#3c8dbc'
      },
      yaxis : {
        min : 0,
        max : 100,
        show: true
      },
      xaxis : {
        show: true
      }
    })

    var updateInterval = 500 //Fetch data ever x milliseconds
    var realtime       = 'on' //If == to on then fetch data every x seconds. else stop fetching
    function update() {

      interactive_plot.setData([getRandomData()])

      // Since the axes don't change, we don't need to call plot.setupGrid()
      interactive_plot.draw()
      if (realtime === 'on')
        setTimeout(update, updateInterval)
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === 'on') {
      update()
    }
    //REALTIME TOGGLE
    $('#realtime .btn').click(function () {
      if ($(this).data('toggle') === 'on') {
        realtime = 'on'
      }
      else {
        realtime = 'off'
      }
      update()
    })
    /*
     * END INTERACTIVE CHART
     */

    /*
     * LINE CHART
     * ----------
     */
    //LINE randomly generated data

    var sin = [], cos = []
    for (var i = 0; i < 14; i += 0.5) {
      sin.push([i, Math.sin(i)])
      cos.push([i, Math.cos(i)])
    }
    var line_data1 = {
      data : sin,
      color: '#3c8dbc'
    }
    var line_data2 = {
      data : cos,
      color: '#00c0ef'
    }
    $.plot('#line-chart', [line_data1, line_data2], {
      grid  : {
        hoverable  : true,
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0,
        lines     : {
          show: true
        },
        points    : {
          show: true
        }
      },
      lines : {
        fill : false,
        color: ['#3c8dbc', '#f56954']
      },
      yaxis : {
        show: true
      },
      xaxis : {
        show: true
      }
    })
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
      position: 'absolute',
      display : 'none',
      opacity : 0.8
    }).appendTo('body')
    $('#line-chart').bind('plothover', function (event, pos, item) {

      if (item) {
        var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2)

        $('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
          .css({ top: item.pageY + 5, left: item.pageX + 5 })
          .fadeIn(200)
      } else {
        $('#line-chart-tooltip').hide()
      }

    })
    /* END LINE CHART */

    /*
     * FULL WIDTH STATIC AREA CHART
     * -----------------
     */
    var areaData = [[2, 88.0], [3, 93.3], [4, 102.0], [5, 108.5], [6, 115.7], [7, 115.6],
      [8, 124.6], [9, 130.3], [10, 134.3], [11, 141.4], [12, 146.5], [13, 151.7], [14, 159.9],
      [15, 165.4], [16, 167.8], [17, 168.7], [18, 169.5], [19, 168.0]]
    $.plot('#area-chart', [areaData], {
      grid  : {
        borderWidth: 0
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#00c0ef'
      },
      lines : {
        fill: true //Converts the line chart to area chart
      },
      yaxis : {
        show: false
      },
      xaxis : {
        show: false
      }
    })

    /* END AREA CHART */

    /*
     * BAR CHART
     * ---------
     */


    /* END BAR CHART */

    /*
     * DONUT CHART
     * -----------
     */

    /*
     * END DONUT CHART
     */

  })

  /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
</script>
</body>
</html>
<?php
	mysqli_close($db);
}
else
{
	header("location: pages/forms/firstform.php"); 
 	mysqli_close($db);
}

?>