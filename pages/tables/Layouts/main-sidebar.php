<?php
include("config2.php");
session_start();
include("../../../queries.php");
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',Last_Name) as Name,Job_Role,employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userImage = $useridrow['employee_image'];
$imgPath = '../../uploads/'.$userImage;
$Trainer = mysqli_query ($db,"SELECT * FROM `all_trainers` where employee_id='$userid'");
$cnttrainer = mysqli_num_rows($Trainer);
$GetTeamCount = mysqli_query($db,"select * from employee_Details where reporting_manager_id='$userid' and is_active='Y' ");
$TeamCount = mysqli_num_rows($GetTeamCount);
$GetHodDetails = mysqli_query($db,"select * from all_hods where employee_id='$userid'");
$HodCount = mysqli_num_rows($GetHodDetails);
if($TeamCount>0)
{
	$HasTeam='Y';
}
if($HodCount > 0)
{
	$IsHod = 'Y';
}
if($cnttrainer == 0)
{
	$isTrainer = 'N';
}
else
{
	$isTrainer = 'Y';
}
$getFirstLineLookup = mysqli_query($db,"select * from pms_manager_lookup where manager_id='$userid'");
	if(mysqli_num_rows($getFirstLineLookup)>0)
	{
		$isFirstLine='Y';
	}
$getEligible = mysqli_query($db,"select * from application_configuration where config_type='ACCESS' and module='DSR' and value='$userid'");
if(mysqli_num_rows($getEligible)>0)
	{
		$isEligible='Y';
	}
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $imgPath; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php  echo $usernameval;  ?></p>
          <a href="#"><?php  echo $userRole  ?></a>
        </div>
      </div>
      <!-- search form -->

      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
		 <li >
          <a href="../../DashboardFinal.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
		<?php
			if($userRole=='HR' || $userRole=='HR Manager' || $userRole=='System Admin Manager' || $userRole== 'System Admin' || $HasTeam=='Y')
			{
			?>
		 <li class="treeview">
          <a href="#">
            <img src="Images/User-Add.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Employee Boarding</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
		  <?php if($userRole=='HR' || $userRole=='HR Manager' || $userRole=='System Admin Manager' || $userRole== 'System Admin') { ?>
		    <li><a href="../OnBoarding/BoardingHome.php"><i class="fa fa-circle-o"></i>Boarding Home</a></li>
		  <?php }?>
			<li><a href="../OnBoarding/ConfirmServices.php"><i class="fa fa-circle-o"></i>Confirmation of Services</a></li>
          </ul>

        </li>
		<?php
			}
		?>
		 <li class="treeview" id="EmployeeInfo">
          <a href="#">
            <img src="Images/Employee.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Employee Info</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
		    <li><a href="../../mydetails.php"><i class="fa fa-circle-o"></i>My Particulars</a></li>
			<?php
			if($HasTeam=='Y')
			{
			?>
			<li><a href="SearchTeam.php"><i class="fa fa-circle-o"></i>My Team Particulars</a></li>			
			<?php
			}
			?>
          <?php
			if($userRole=='HR' || $userRole=='HR Manager')
			{
			?>
            <li><a href="SearchEmployee.php"><i class="fa fa-circle-o"></i>Search Employee</a></li>
            <li><a href="SearchExits.php"><i class="fa fa-circle-o"></i>Search Exit Employee</a></li>
			<?php
			}
          ?>
          </ul>

        </li>
	<li class="treeview">
          <a href="">
            <img src="Images/time-and-attendance-management-icon.png" alt="Training" width='20px' height='23px'>&nbsp; <span>Attendance Management</span>
			 <span class="pull-right-container"> 
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
		  <ul class="treeview-menu">
		   <li><a href="../LeaveMgmt/LeaveRequest.php"><i class="fa fa-circle-o"></i>My Attendance</a></li>
		   <?php
			if($HasTeam=='Y')
			{
			?>
			  <li><a href="../LeaveMgmt/TeamLeaveRequest.php"><i class="fa fa-circle-o"></i>Employee Attendance</a></li>
			<?php
			}
			?>
		   
		  </ul>
        </li>
		<?php
			if($userRole=='HR' || $userRole=='HR Manager' || $isFirstLine=='Y')
			{
			?>
		<li class="treeview active">
          <a href="#">
            <img src="Images/Resource.png" alt="Training" width='20px' height='23px'>&nbsp; <span>Resource Management</span>
			 <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
		  <ul class="treeview-menu">
		   <li><a href="ResourceMgmtCount.php"><i class="fa fa-circle-o"></i>Summary</a></li>
		   
			  <li><a href="ResourceChangeRequest.php"><i class="fa fa-circle-o"></i>Request Status</a></li>	
           <li><a href="AvailableResource.php"><i class="fa fa-circle-o"></i>Resource Usage</a></li>		
		  </ul>
        </li>
		<?php
			}
		?>

	<li class="treeview">
          <a href="#">
            <img src="Images/training.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Training Management</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
		  <?php
			if($userRole=='HR Manager')
			{
		  ?>
		    <li><a href="../TrainingMgmt/NewTraining.php"><i class="fa fa-circle-o"></i>Create New Training</a></li>
			
			<?php
			}
			?>
			
		    <li><a href="../TrainingMgmt/TrainingMgmt.php"><i class="fa fa-circle-o"></i>Active Trainings</a></li>		
			
            <li><a href="../TrainingMgmt/MyTrainings.php"><i class="fa fa-circle-o"></i>Participating Trainings</a></li>
			
			<?php
			if($isTrainer == 'Y')
			{
			?>
			<li><a href="../TrainingMgmt/TrainerQueue.php"><i class="fa fa-circle-o"></i>My Trainings</a></li>
			
			<?php
			}
			?>
			<?php
			if($HasTeam=='Y')
			{
			?>
			<li><a href="../TrainingMgmt/TeamTrainings.php"><i class="fa fa-circle-o"></i>Team Trainings</a></li>
			
			<?php
			}
			?>
			<?php
			if($userRole=='HR' || $isTrainer=='Y' || $userRole =='HR Manager')
			{	
			?>
			<li><a href="../TrainingMgmt/CreatedTrainings.php"><i class="fa fa-circle-o"></i>Training Summary</a></li>
			<?php
			}
			?>
          </ul>

        </li>
		<li class="treeview">
          <a href="#">
            <img src="Images/exit.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Exit Management</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
		    <li><a href="../resignationMgmt/employeeresignationform.php"><i class="fa fa-circle-o"></i>My Resignation</a></li>
			<?php
			if($HasTeam=='Y')
			{
			?>		
            <li><a href="../resignationMgmt/resignationapprovalform.php"><i class="fa fa-circle-o"></i>My Team's Resignation</a></li>
			<?php } ?>
			<?php
			if($userRole=='HR Manager')
			{
			?>
			<li><a href="../resignationMgmt/resignationprocessingform.php"><i class="fa fa-circle-o"></i>Resignation of all employees</a></li>
			<?php } ?>
			<?php
			if($userRole=='HR')
			{
			?>
			<li><a href="../resignationMgmt/noduehrform.php"><i class="fa fa-circle-o"></i>Resignation of all employees</a></li>
			<?php } ?>
			<?php
			if($userRole=='Admin' && $userid !='3')
			{
			?>
			<li><a href="../resignationMgmt/nodueadminform.php"><i class="fa fa-circle-o"></i>Resignation of all employees</a></li>
			<?php } ?>
			<?php
			if($userRole=='Accounts Manager')
			{
			?>
			<li><a href="../resignationMgmt/nodueaccform.php"><i class="fa fa-circle-o"></i>Resignation of all employees</a></li>
			<?php } ?>
            <?php
			if($IsHod == 'Y')
			{
			?>
			<li><a href="../resignationMgmt/departmentresignationform.php"><i class="fa fa-circle-o"></i>My Department's Resignation</a></li>
			<?php } ?>
          </ul>

        </li>	
		<li class="treeview">
		<a href="#">
            <img src="Images/reports1.png" alt="Reports" width='20px' height='23px'>&nbsp; <span>Reports</span>	
			<span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span></a>
				<ul class="treeview-menu">
				<?php 
				if($userRole=='HR Manager')
				{
				?>
		 <li><a href="../reports/ReportsAllocationScreen.php"><i class="fa fa-circle-o"></i>Report Allocation</a>
		  </li>	
				<?php } ?>
		  <li><a href="../reports/ReportsScreen.php"><i class="fa fa-circle-o"></i>Report Generation</a>
		  </li>
        	</ul>
			</li>
      <li class="treeview">
			<a href="#">
				<img src="Images/reports1.png" alt="Reports" width='20px' height='23px'>&nbsp; <span>DSR Reports</span>	
				<span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
			</a>
			<ul class="treeview-menu">
            <?php
			if($HasTeam=='Y')
			{
			?>	
				<li><a href="../DSR/MyDSRReports.php"><i class="fa fa-circle-o"></i>My Team DSR</a></li>
            <?php }  else{ ?>
            <li><a href="../DSR/MyDSRReports.php"><i class="fa fa-circle-o"></i>My DSR</a></li>
            <?php } ?>
				<?php
			if($userRole=='HR' || $userRole =='HR Manager')
			{
			?>
			<li><a href="../DSR/HRview.php"><i class="fa fa-circle-o"></i>Employee DSR</a></li>
			
			<?php
			}
			?>
        	</ul>
		</li>
      <li class="treeview">
			<a href="#">
				<i class="fa fa-fw fa-list-ul"></i>&nbsp; <span>Password Management</span>	
				<span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
			</a>
			<ul class="treeview-menu ">
				<li><a href="../Credentials/AllCredentials.php"><i class="fa fa-circle-o"></i>All Passwords</a></li>
        	</ul>
		</li>
                <li>
          <a href="../EventMgmt/EventsHome.php">
            <img src="../EventMgmt/Images/CopEvent.png" alt="Reports" width='20px' height='23px'>&nbsp; <span>Events & Happenings</span>		
          </a>
        </li>
      <li id="EmployeeHandbook">
          <a href="SearchHandbook.php">
            <i class="fa fa-fw fa-book"></i> <span>Employee HandBook</span>
          </a>
        </li>
      
      </ul>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
