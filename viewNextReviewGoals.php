<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

if(isset($_REQUEST['appraisee_id']) && is_numeric($_REQUEST['appraisee_id'])) {
  $appraiseeId = $_REQUEST['appraisee_id'];
}else{
  header("Location:myTeamAppraisal.php");
  exit();
}

// Review Details Query
$reviewAppraiseeDetailsQry = mysqli_query($db,"select * from employee_performance_review_dates where employee_id = $appraiseeId and is_Active = 'Y'");
$reviewAppraiseeDetailsData = mysqli_fetch_assoc($reviewAppraiseeDetailsQry);
$appraiseeCurrentReviewId = isset($reviewAppraiseeDetailsData['employee_review_date_id'])?$reviewAppraiseeDetailsData['employee_review_date_id']:0;
$appraiseeNextReviewId = isset($reviewAppraiseeDetailsData['next_review_id'])?$reviewAppraiseeDetailsData['next_review_id']:0;

$goalsQuery = mysqli_query($db, "select goal_title,goal_id from employee_goals where employee_review_date_id = $appraiseeNextReviewId and is_Active = 'Y'");
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PMS
        <small>Goals for Next Appraisal</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PMS</a></li>
        <li class="active">Goals for Next Appraisal</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Goals for Next Appraisal</h3>
              <a href="addNextReviewGoals.php?appraisee_id=<?php echo $appraiseeId; ?>" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Add+</a>
            </div>
            <!-- /.box-header -->

            <div class="border-class">
              <!-- form start -->
              <div class="row">
                <div class="tab-content">
                  <div class="box-body">
                    <div class="box-group" id="accordion">
                      <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                      <?php
                      if(mysqli_num_rows($goalsQuery) <= 0){
                        echo "No Goals Found";
                      }else{
                        while($data = mysqli_fetch_assoc($goalsQuery)){
                          $goalId = $data['goal_id'];
                          $goalTitle = $data['goal_title'];
                      ?>
                      <div class="panel box box-primary">
                        <div class="box-header with-border">
                          <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $goalId; ?>">
                              <?php echo $goalTitle; ?>
                            </a>
                          </h4>
                        </div>
                        <div id="collapse_<?php echo $goalId; ?>" class="panel-collapse collapse">
                          <div class="box-body">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th>#</th>
                                  <th>Goal Plan Period</th>
                                  <th>Goal Plan</th>
                                  <th>Goal Plan Measure</th>
                                </tr>
                                <?php
                                $i = 1;
                                $goalPlansQuery = mysqli_query($db, "select goal_plan, goal_plan_measure, goal_plan_period from employee_goal_plans where goal_id = $goalId and is_Active = 'Y' and employee_id = $appraiseeId");
                                while($data = mysqli_fetch_assoc($goalPlansQuery)){
                                  echo "<tr><td>".$i."</td><td>".@$data['goal_plan_period']."</td><td>".@$data['goal_plan']."</td><td>".@$data['goal_plan_measure']."</td></tr>";
                                  $i++;
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <?php
                        }
                      }
                      ?>
                    </div>
                  </div>
                  <a href="employeeGoalsReview.php?appraisee_id=<?php echo $appraiseeId; ?>" class="btn btn-info" role="button" style="float:left;margin-left:20px;"><< Prev</a>
                  <a href="viewNextReviewDevelopmentPlans.php?appraisee_id=<?php echo $appraiseeId; ?>" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Next >></a>
                  <!-- /.box-body -->
                </div>
              </div>
            </div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
