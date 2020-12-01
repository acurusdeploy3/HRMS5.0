<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$projectsQuery = mysqli_query($db,"select Project_Name, a.Project_ID as project_id, DATE(allocated_from) as startDate, DATE(allocated_to) as endDate, allocated_percentage, appraisee_comments, manager_comments from employee_projects a, all_projects b, performance_project_scores c where a.Project_ID = b.Project_ID and a.Project_ID = c.Project_ID and a.is_Active = 'Y' and a.employee_id = $empId and c.employee_id = $empId");
if(mysqli_num_rows($projectsQuery) <= 0){
  $projectsQuery = mysqli_query($db,"select Project_Name, a.Project_ID as project_id, DATE(allocated_from) as startDate, DATE(allocated_to) as endDate, allocated_percentage from employee_projects a, all_projects b where a.Project_ID = b.Project_ID and a.is_Active = 'Y' and a.employee_id = $empId");
}
//$employeeGoalsQuery = mysqli_query($db,"select * from performance_goals where employee_id = $empId and employee_review_date_id = ".$currentReviewId);
$employeeGoalsQuery = mysqli_query($db,"select goal_plan_id, goal_title, goal_plan, appraisee_comments, goal_plan_measure, goal_plan_period from employee_goals a, employee_goal_plans b where a.employee_id = b.employee_id and a.goal_id = b.goal_id and b.employee_id = $empId and employee_review_date_id = $currentReviewId and a.is_Active = 'Y' and b.is_Active = 'Y'");
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
        <li><a href="#">PMS</a></li><li class="active">Peformance Assessment</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Peformance Assessment</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr><th colspan='7' class='pms_head'>Appraise Self Assessment</th></tr>
                <tr>
                  <th>#</th>
                  <th>Project</th>
                  <th>Allocated Percentage</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Apraisee Recommendation</th>
                  <th>Actions</th>
                </tr>
                <?php
                if(mysqli_num_rows($projectsQuery) <= 0){
                  echo "<tr><td colspan='7'>No Data found</td></tr>";
                }else{
                  $i=1;
                  while($value = mysqli_fetch_assoc($projectsQuery)){
                    $val = @$value['project_id'];
                    $id = str_replace(" ","_",$val);
                    echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo "<td>".@$value['Project_Name']."</td>";
                      echo "<td>".@$value['allocated_percentage']."</td>";
                      echo "<td>".@$value['startDate']."</td>";
                      echo "<td>".@$value['endDate']."</td>";
                      echo "<td><textarea row='1' class='form-control' id='comments_".$id."'>".@$value['appraisee_comments']."</textarea></td>";
                      echo "<td><a href='javascript:projectComments(\"".$val."\",\"".$id."\",\"employee\");'><i class='fa fa-save'></i></a></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th colspan='7' class='pms_head'>Appraisee Progress against Goals</th></tr>
                <tr>
                  <th>#</th>
                  <th>Goal</th>
                  <th>Goal Plan</th>
                  <th>Goal Measure</th>
                  <th>Goal Plan Period</th>
                  <th>Apraisee Recommendation</th>
                  <th>Actions</th>
                </tr>
                <?php
                if(mysqli_num_rows($employeeGoalsQuery) <= 0){
                  echo "<tr><td colspan='7'>No Data found</td></tr>";
                }else{
                  $i=1;
                  while($value = mysqli_fetch_assoc($employeeGoalsQuery)){
                    $val = @$value['goal_plan_id'];
                    echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo "<td>".@$value['goal_title']."</td>";
                      echo "<td>".@$value['goal_plan']."</td>";
                      echo "<td>".@$value['goal_plan_measure']."</td>";
                      echo "<td>".@$value['goal_plan_period']."</td>";
                      echo "<td><textarea row='1' class='form-control' id='comments_".$val."'>".@$value['appraisee_comments']."</textarea></td>";
                      echo "<td><a href='javascript:goalsComments(\"".$val."\",\"employee\");'><i class='fa fa-save'></i></a></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
          <a href="culturePerformance.php" class="btn btn-info" role="button" style="float:left;margin-left:20px;"><< Prev</a>
          <a href="employeeAppraisal.php" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Next >></a>
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
