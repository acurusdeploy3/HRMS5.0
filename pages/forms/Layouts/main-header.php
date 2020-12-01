<header class="main-header">
    <!-- Logo -->
    <a href="firstform.php" class="logo">
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
         
          
          <!-- Tasks: style can be found in dropdown.less -->
          
		  <?php
include("config.php");
session_start();
$userid=$_SESSION['login_user'];
$usergrp=$_SESSION['login_user_group'];
$username =mysqli_query ($db,"select concat(First_name,' ',MI,' ',Last_name) as Name,Job_Role,Employee_designation from employee_details where employee_id=$userid");
$useridrow = mysqli_fetch_assoc($username);
$usernameval = $useridrow['Name'];
$userRole = $useridrow['Job_Role'];
$userDes = $useridrow['Employee_designation'];
?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/avatar5.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php  echo $userid  ?></span>
            </a>
           <!-- <ul class="dropdown-menu">
             User image 
              <li class="user-header">
                <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">
					<p>
                  <?php // echo $usernameval  ?>
                  <small><?php // echo $userDes ?></small>
                </p>
              </li>
               Menu Body
              Menu Footer
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="pages/forms/MainLogin.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul> -->
          </li>
		   <li>
            <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
