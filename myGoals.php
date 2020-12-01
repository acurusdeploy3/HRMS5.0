<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
if(isset($_SESSION['submittedReview']) && $_SESSION['submittedReview'] == 'Yes'){
  header("Location: employeeSubmit.php");
  exit();
}
//$employeeGoalsQuery = mysqli_query($db,"select * from performance_goals where employee_id = $empId and employee_review_date_id = ".$currentReviewId);
//$employeeDevPlansQuery = mysqli_query($db,"select * from performance_development_plans where employee_id = $empId and employee_review_date_id = ".$currentReviewId);
$employeeGoalsQuery = mysqli_query($db, "select goal_title, goal_plan, goal_plan_measure, goal_plan_period, goal_plan_status, goal_plan_completion_status from employee_goals a, employee_goal_plans b where a.employee_id = b.employee_id and a.goal_id = b.goal_id and b.employee_id = $empId and employee_review_date_id = $currentReviewId and a.is_Active = 'Y' and b.is_Active = 'Y'");
$employeeDevPlansQuery = mysqli_query($db, "select development_plan_title, development_plan, development_plan_measure, development_plan_period, development_plan_status, development_plan_completion_status from employee_development_plans_master a, employee_development_plans b where a.employee_id = b.employee_id and a.development_plan_master_id = b.development_plan_master_id and b.employee_id = $empId and employee_review_date_id = $currentReviewId and a.is_Active = 'Y' and b.is_Active = 'Y'");
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PMS
        <small>Employee Self Assessment</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PMS</a></li><li class="active">My Goals</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">My Goals</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr><th colspan='6' class="pms_head">Goals for current appraisal</th></tr>
                <tr>
                  <th>Goal Name</th>
                  <th>Goal Plan</th>
                  <th>Goal Measure</th>
                  <th>Period</th>
                  <th>Status</th>
                  <th>Completion</th>
                </tr>
                <?php
                if(mysqli_num_rows($employeeGoalsQuery) < 1){
                  echo "<tr><td colspan='6'> No Goals Found</td></tr>";
                }else{
                  while($employeeGoalsData = mysqli_fetch_assoc($employeeGoalsQuery)){
                    echo "<tr><td>".$employeeGoalsData['goal_title']."</td><td>".$employeeGoalsData['goal_plan']."</td><td>".$employeeGoalsData['goal_plan_measure']."</td><td>".$employeeGoalsData['goal_plan_period']."</td><td>".$employeeGoalsData['goal_plan_status']."</td><td>".$employeeGoalsData['goal_plan_completion_status']."</td></tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr><th colspan='6' class="pms_head">Devlopment plan for current appraisal</th></tr>
                <tr>
                  <th>Development Name</th>
                  <th>Development Plan</th>
                  <th>Development Measure</th>
                  <th>Period</th>
                  <th>Status</th>
                  <th>Completion</th>
                </tr>
                <?php
                if(mysqli_num_rows($employeeDevPlansQuery) < 1){
                  echo "<tr><td colspan='6'> No Development Plans Found</td></tr>";
                }else{
                  while($employeeDevPlansData = mysqli_fetch_assoc($employeeDevPlansQuery)){
                    echo "<tr><td>".$employeeDevPlansData['development_plan_title']."</td><td>".$employeeDevPlansData['development_plan']."</td><td>".$employeeDevPlansData['development_plan_status']."</td><td>".$employeeDevPlansData['development_plan_measure']."</td><td>".$employeeDevPlansData['development_plan_status']."</td><td>".$employeeDevPlansData['development_plan_completion_status']."</td></tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
        <!-- <div class="box-footer">
          Footer
        </div> -->
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php
  require_once('layouts/main-footer.php');
  require_once('layouts/control-sidebar.php');
  require_once('layouts/bottom-footer.php');
  ?>
