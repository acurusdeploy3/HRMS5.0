<?php
include("config.php");
session_start();
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysql_query ("select concat(First_name,' ',Last_Name,' ',MI) as Name,Job_Role from employee_details where employee_id=$userid");
$useridrow = mysql_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
?>


<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">
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
		 <li class="active">
          <a href="EmployeeDashboard.html">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
		 <li class="treeview">
          <a href="#">
            <img src="pages/tables/Images/Employee.png" alt="Training" width='18px' height='18px'>&nbsp;  <span>Employee Info</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
		    <li><a href="#"><i class="fa fa-circle-o"></i>My Particulars</a></li>
			<?php
			if($usergrp=='HR')
			{
			?>
            <li><a href="#"><i class="fa fa-circle-o"></i>Search Employee</a></li>
			<?php
			}
			if($usergrp=='Manager')
			{
			?>
			<li><a href="#"><i class="fa fa-circle-o"></i>My Team Particulars</a></li>
			
			<?php
			}
			?>
          </ul>

        </li>
		<li>
          <a href="http://115.160.252.30:2017/attendance_portal/Login.html">
            <img src="pages/tables/Images/time-and-attendance-management-icon.png" alt="Training" width='20px' height='23px'>&nbsp; <span>Attendance Management</span>
          </a>
        </li>
		<?php
			if($usergrp=='HR')
			{
			?>
		<li>
          <a href="pages/tables/ResourceMgmtCount.php">
            <img src="pages/tables/Images/Resource.png" alt="Training" width='18px' height='18px'>&nbsp;&nbsp; <span>Resource Management</span>
          </a>
        </li>
		<?php
			}
		?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
