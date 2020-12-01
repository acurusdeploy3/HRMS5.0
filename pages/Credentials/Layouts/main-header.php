<header class="main-header">
    <!-- Logo -->

	  <?php
include("config.php");
include("session.php");
session_start();
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$notval =mysql_query ("select employee_to_be_notified,module,date_to_be_notified,notification_message,action_against_employee,link_href,notification_type from
employee_notification where employee_to_be_notified=$userid and date_to_be_notified=curDate() and action_taken='N'");


$count = mysql_query ("select count(*) as count from
employee_notification where employee_to_be_notified=$userid and date_to_be_notified=curDate() and action_taken='N';");
$notcnt = mysql_fetch_array($count);
$notcontval = $notcnt['count'];

		  ?>
    <a href="../../DashboardFinal.php" class="logo">
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
         
          <!-- Notifications: style can be found in dropdown.less -->
         
          <!-- Tasks: style can be found in dropdown.less -->
		  <?php
include("config2.php");
session_start();
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',Last_Name,' ',MI) as Name,Job_Role,Employee_designation,employee_image from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userDes = $useridrow['Employee_designation'];
$userImage = $useridrow['employee_image'];
$imgPath = '../../uploads/'.$userImage;
?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $imgPath; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php  echo $userid  ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $imgPath; ?>" class="img-circle" alt="User Icon">


                <p>
                  <?php  echo $usernameval  ?>
                  <small><?php  echo $userDes ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="../../changepassword.php" class="btn btn-default btn-flat">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="../../Logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
