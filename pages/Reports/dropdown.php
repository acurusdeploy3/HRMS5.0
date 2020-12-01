<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$userid = $_SESSION['login_user'];
include('config.php');
if (!empty($_POST["department"])) {
        $department = $_POST["department"]; 
        $location = $_POST["location"];
		if($userid == 1 || $userid == 2 || $userid == 1034)
		{
		if($department == 'ALL')
		{
			$reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details) and is_active='Y' order by employee_id ");
		}
		else {
		$reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details) and department='$department' and is_active='Y' order by employee_id ");
		}
		
		}
		else
		{
			if($department == 'ALL')
		{
			$reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details) and employee_id not in (1,2,3) and is_active='Y' 
			order by employee_id");
		}
		else {
		$reptodropdown = mysqli_query($db,"select concat(First_Name,' ',Last_Name) as Manager,employee_id,Department from employee_details where employee_id in (select distinct reporting_manager_id from employee_details) and department='$department' and employee_id not in (1,2,3) and is_active='Y' order by employee_id  ");
		}
		
		}
		?>
		 <option value="" selected>Select Reporting</option>  
		<?php
		        foreach ($reptodropdown as $val){
?>
            <option value="<?php echo $val["employee_id"];?>"><?php echo $val["Manager"];?>
    </option>       
<?php
        }
}
?>
</ul>

