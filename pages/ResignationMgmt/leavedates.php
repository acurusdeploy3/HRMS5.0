<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
include('config.php');
if (isset($_POST['empidval'])) {
	$empidval=$_POST['empidval'];
	
	$getresvalue =mysqli_query($db,"select * from employee_resignation_information r where employee_id='$empidval' and is_active='Y'");
	$getresval = mysqli_fetch_array($getresvalue);
	$resid=$getresval['resignation_id'];
	$getifrows = mysqli_query($db,"select date(modified_date_and_time) from audit where module_name='Resignation Management' and employee_id ='$empidval' and module_id='$resid' and description='date_of_leaving'
	and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) and modified_by <>'$empidval'");

	 if(mysqli_num_rows($getifrows) <=0)
    {
    	$getleavequery=mysqli_query($db,"select l.Employee_id,date_availed,Leave_type,date_format(date(date_availed),'%d %b %Y') as dt,if(Permission_Type='','Full Day',Permission_Type) as Permission_Type FROM leave_status l inner join employee_resignation_information ei on l.employee_id=ei.employee_id where leave_type in ('CL','PL','SL') and cancled='N' and ei.employee_id='$empidval'
		and date(date_availed)>=date(date_of_submission_of_resignation)
        union
        select a.Employee_id,date,Leave_type,date_format(date,'%d %b %Y') as `dt`,if(Permission_Type='','Full Day',Permission_Type) as Permission_Type from attendance_tracker a inner join employee_resignation_information ei on a.employee_id=ei.employee_id where a.leave_type='LOP' and permission_type in ('','Half Day')  and ei.employee_id='$empidval'
and date >=date(date_of_submission_of_resignation) and date <= date_of_leaving
order by date_availed desc");
     
?>
			<table class="table" style="font-size:14px;">	
										<thead>
                                        	<th>S.No</th>
											<th>Employee ID</th>
											<th>Leave Type</th>
											<th>Date</th>
                                        <th>Permission Type</th>
										</thead>
            <?php $i=1; while($row7 = mysqli_fetch_assoc($getleavequery)){ ?>
    	<tr style='font-weight: 800;'>
        	<td><?php echo $i;?></td>
			<td style='width:20%'><?php echo $row7['Employee_id'];?></td>
			<td style='width:25%'><?php echo $row7['Leave_type'];?></td>
			<td style='width:15%'><?php echo $row7['dt'];?></td>
        	<td style='width:30%'><?php echo $row7['Permission_Type'];?></td>
		</tr>
            <?php $i++; } ?>
    </table>
    <?php } else {
	$getleavebfquery=mysqli_query($db,"SELECT l.Employee_ID,Date_availed,date_format(date(Date_availed),'%d %b %Y') as `Date`,Leave_Type,if(Permission_Type='','Full Day',Permission_Type) as Permission_Type FROM leave_status l inner join employee_resignation_information ei on l.employee_id=ei.employee_id where l.employee_id='".$empidval."' and date(date_availed) < (SELECT date(modified_date_and_time) FROM `audit` 
	where  description='date_of_leaving' and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) and date(date_availed) >= date(date_of_submission_of_resignation) and date(date_availed) <= date_of_leaving  and module_id ='".$resid."' order by id desc limit 1) and leave_type in ('CL','PL','SL') and cancled='N'
    union
    select a.Employee_ID,date,date_format(date,'%d %b %Y') as `Date`,Leave_Type,
if(Permission_Type='','Full Day',Permission_Type) as Permission_Type
from attendance_tracker a
inner join employee_resignation_information ei on a.employee_id=ei.employee_id
where a.employee_id='".$empidval."' and a.leave_type='LOP' and permission_type in ('','Half Day') and date <= ei.date_of_leaving
and date < (SELECT date(modified_date_and_time) FROM `audit`
where  description='date_of_leaving' and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
and parameter='HR_ID')) and date >= date(date_of_submission_of_resignation) and date <= date_of_leaving  and module_id ='".$resid."'
order by id desc limit 1)
order by Date_availed desc");
	$getleaveafquery=mysqli_query($db,"SELECT l.Employee_ID,Date_availed,date_format(date(Date_availed),'%d %b %Y') as `Date`,Leave_Type,if(Permission_Type='','Full Day',Permission_Type) as Permission_Type	FROM leave_status l inner join employee_resignation_information ei on l.employee_id=ei.employee_id where l.employee_id='".$empidval."' and date(date_availed) >= (SELECT date(modified_date_and_time) FROM `audit` where  description='date_of_leaving' and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
	and parameter='HR_ID')) and date(date_availed) >= date(date_of_submission_of_resignation) and date(date_availed) <= date_of_leaving  and
	module_id ='".$resid."' order by id desc limit 1) and leave_type in ('CL','PL','SL')
	and cancled='N' 
    union
    select a.Employee_ID,date,date_format(date,'%d %b %Y') as `Date`,Leave_Type,
if(Permission_Type='','Full Day',Permission_Type) as Permission_Type
from attendance_tracker a
inner join employee_resignation_information ei on a.employee_id=ei.employee_id
where a.employee_id='".$empidval."' and a.leave_type='LOP' and permission_type in ('','Half Day') and date <= ei.date_of_leaving
and date >= (SELECT date(modified_date_and_time) FROM `audit`
where  description='date_of_leaving' and modified_by in ((select substring_index(substring_index(substring_index(value,',',3),',',-2),',',1) from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
and parameter='HR_ID'),(select substring_index(substring_index(substring_index(value,',',3),',',-2),',',-1)from application_configuration where config_type='LIST_VIEW' and module ='RESIGNATION'
and parameter='HR_ID')) and date >= date(date_of_submission_of_resignation) and date <= date_of_leaving  and module_id ='".$resid."'
order by id desc limit 1)
order by Date_availed desc
    
    ");
										//$gethrcommentsrow = mysqli_fetch_array($gethrcommentsquery);
									?>
									<table class="table" style="font-size:14px;">	
										<thead>
                                        	<th>S.No</th>
											<th>Employee ID</th>
											<th>Leave Type</th>
											<th>Date</th>
                                        	<th>Permission Type</th>
                                        	<th>About</th>
										</thead>
                                    	<tbody>
										<?php $i=1; while($row5 = mysqli_fetch_assoc($getleavebfquery)){ ?>
										<tr style='font-weight: 800;'>
                                        	<td><?php echo $i;?></td>
											<td style='width:10%'><?php echo $row5['Employee_ID'];?></td>
											<td style='width:10%'><?php echo $row5['Leave_Type'];?></td>
											<td style='width:15%'><?php echo $row5['Date'];?></td>
                                        <td style='width:15%'><?php echo $row5['Permission_Type'];?></td>
                                        <td style='width:30%'><span>Leave taken before date of leaving was changed</span></td>
										</tr>
										<?php $i++; } ?>
										<?php $k=i; while($row6 = mysqli_fetch_assoc($getleaveafquery)){?>
										<tr style='font-weight: 800;color:tomato;'>
                                        	<td><?php echo $i;?></td>
											<td style='width:10%'><?php echo $row6['Employee_ID'];?></td>
											<td style='width:10%'><?php echo $row6['Leave_Type'];?></td>
											<td style='width:15%'><?php echo $row6['Date'];?></td>
                                        <td style='width:15%'><?php echo $row6['Permission_Type'];?></td>
                                        <td style='width:30%'><span>Leave taken after date of leaving was changed</span></td>
										</tr>
										<?php $k++; }  ?>
                                    </tbody>
									</table>
<h5>Note : Dates marked in Red are leaves taken after the date on which the date of leaving was changed </h5>

<?php }
}
?>