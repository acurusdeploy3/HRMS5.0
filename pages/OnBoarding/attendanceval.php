<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
include("config2.php");
include("config6.php");
include("config5.php");
if (!empty($_POST["employeeid"])) {

$Employee_ID=$_POST['employeeid'];
					$RFD = $_POST['doj'];
					$RTD = $_POST['doc'];

$mydetailsquery=mysqli_query($db,"select concat(First_Name,' ',MI,' ',Last_Name,'-',Employee_ID) as person_name,employee_designation,department,employee_image from employee_details where employee_id ='$Employee_ID'");
$mydetailsrow=mysqli_fetch_array($mydetailsquery);
$mydetval = $mydetailsrow['person_name'];
$desgval = $mydetailsrow['employee_designation'];
$deptval = $mydetailsrow['department'];
$ProfPic = $mydetailsrow['employee_image'];
$profPicPath = '../../uploads/'.$ProfPic;

$mandetailsquery=mysqli_query($db,"select concat(First_Name,' ',MI,' ',Last_Name,'-',Employee_ID) as person_name from employee_details where employee_id in(
select manager_id from cos_master where employee_id ='$Employee_ID')");
$mandetailsrow=mysqli_fetch_array($mandetailsquery);
$mandetval = $mandetailsrow['person_name'];


$date=date_create($RFD);
$date1=date_create($RTD); 
?>
<div class="box box-widget widget-user" style="margin-bottom: 0px;">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active" style="height: 77px ! important;">
            <h3 class="widget-user-username"><?php echo $mydetval; ?><span style="float:right;">Attendance Details</span></h3>
              <h5 class="widget-user-desc"><?php echo $desgval." - ".$deptval; ?></h5>
</div>             
            <div class="box-footer" style="padding-top: 0px ! important;    height: 60px;background-color: #a9a9a929;">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">Period From</h5>
                    <span class="description-text"><?php echo date_format($date,"d M Y"); ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">Period To</h5>
                    <span class="description-text"><?php echo date_format($date1,"d M Y"); ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">Manager Details</h5>
                    <span class="description-text"><?php echo $mandetval ; ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
				<table class="table" id="atttable">					
					<thead style="color: white;">
						<th>Month</th>
						<th>Working Days</th>
						<th>Present</th>
						<th>Absent</th>
						<th>Present Percentage</th>
						<th>Absent Percentage</th>
					</thead>
					<?php 
					$employeeid = $_POST['employeeid'];
					$datejoined = $_POST['doj'];
					$datecompleted = $_POST['doc'];
					$attquery = mysqli_query($db5,"select j.*,Working_Days-Present_Days as Absent,round(((Working_Days-Present_Days)/Working_Days)*100,2) as Absent_Per,round((Present_Days/Working_Days)*100,2) as Present_Per,Mon_Year
					from(select sum(Working_Days) as Working_Days,emp_id,Name,sum(present_days) as Present_Days,Month,Mon_Year,Year from(select (q.Total_Days-p.Holidays) as Working_Days,Mon_Year, q.* from (select a.employee_id,count(a.date) as Holidays,month(a.date) as Month,date_format(a.date,'%M %Y') as Mon_Year,year(a.date) as Year from attendance_tracker a
					inner join holiday_list h on a.date=h.date and a.leave_type=h.description and a.remarks=h.remarks where  a.date between '$datejoined' and '$datecompleted' group by a.employee_id,month(a.date),year(a.date) ) p inner join (select emp_id,Name,month(Date) as Month,year(Date) as Year,count(Date) as Total_Days,sum(present_days) as present_days From (SELECT t.Date as Date,
					e.Employee_ID as emp_id, e.Employee_Name as name, e.Primary_Manager_Id as Manager_Id,if(Leave_Type in('WFH','OD','SPL','ML','Comp_Off') ,1,if(check_in<>'0001-01-01 00:00:00' and check_out<>'0001-01-01 00:00:00'
					and Leave_Type='',1,if(check_in<>'0001-01-01 00:00:00' and check_out<>'0001-01-01 00:00:00' and permission_type='Half Day',0.5,0))) as present_days  FROM `attendance_tracker` t left join employee e
					on t.employee_id=e.employee_id  where t.Date   between '$datejoined' and '$datecompleted' and  e.employee_id Like '$employeeid'  and e.Status ='A' group by emp_id,t.Date ) b group by emp_id,month(Date),year(Date)) q on p.employee_id=q.emp_id and p.Month=q.Month and p.Year=q.Year) as r group by emp_id,Month,Year) as j group by emp_id,Month,Year
					union
		        	select
					Working_Days,'Total','',Present_Days,Month,'Total',Year,Working_Days-Present_Days as Absent,round(((Working_Days-Present_Days)/Working_Days)*100,2) as Absent_Per,
					round((Present_Days/Working_Days)*100,2) as Present_Per,'Total' from(select sum(Working_Days) as Working_Days,emp_id,Name,sum(present_days) as Present_Days,Month,Mon_Year,Year from(select (q.Total_Days-p.Holidays) as Working_Days,Mon_Year, q.* from (select a.employee_id,count(a.date) as Holidays,month(a.date) as Month,date_format(a.date,'%M %Y') as Mon_Year,year(a.date) as Year from attendance_tracker a inner join holiday_list h on a.date=h.date and a.leave_type=h.description and a.remarks=h.remarks where  a.date between '$datejoined' and '$datecompleted' group by a.employee_id,month(a.date),year(a.date) ) p inner join (select emp_id,Name,month(Date) as Month,year(Date) as Year,count(Date) as Total_Days,sum(present_days) as present_days From (SELECT t.Date as Date,e.Employee_ID as emp_id, e.Employee_Name as name, e.Primary_Manager_Id as Manager_Id,if(Leave_Type in('WFH','OD','SPL','ML','Comp_Off') ,1,if(check_in<>'0001-01-01 00:00:00' and check_out<>'0001-01-01 00:00:00' and Leave_Type='',1,if(check_in<>'0001-01-01 00:00:00' and check_out<>'0001-01-01 00:00:00'
					and permission_type='Half Day',0.5,0))) as present_days  FROM `attendance_tracker` t left join employee e on t.employee_id=e.employee_id  where t.Date   between '$datejoined' and '$datecompleted' and  e.employee_id Like '$employeeid'
					and e.Status ='A' group by emp_id,t.Date ) b group by emp_id,month(Date),year(Date)) q on p.employee_id=q.emp_id and p.Month=q.Month and p.Year=q.Year) as r group by emp_id) as j group by emp_id");
					while($attqueryRow = mysqli_fetch_assoc($attquery)){ 
					if($attqueryRow['Mon_Year'] =='Total')
					{
						echo "<tr style='text-align:center;font-weight: bold;background-color: white;'><td>".$attqueryRow['Mon_Year']."</td>";
						echo "<td>".$attqueryRow['Working_Days']."</td>";
						echo "<td >".$attqueryRow['Present_Days']."
						</td>";
						echo "<td >".$attqueryRow['Absent']."</td>";
						echo "<td ><span class='badge bg-yellow' id='col1'>".$attqueryRow['Present_Per']."</span></td>";
						echo "<td ><span class='badge bg-yellow' id='col2'>".$attqueryRow['Absent_Per']."</span></td></tr>";
					}
					else
					{	
						echo "<tr style='text-align:center;'><td>".$attqueryRow['Mon_Year']."</td>";
						echo "<td>".$attqueryRow['Working_Days']."</td>";
						echo "<td>".$attqueryRow['Present_Days']."</td>";
						echo "<td>".$attqueryRow['Absent']."</td>";
						echo "<td>".$attqueryRow['Present_Per']."</td>";
						echo "<td>".$attqueryRow['Absent_Per']."</td></tr>";
					}
					}
					echo "</table>";					
}
?>


