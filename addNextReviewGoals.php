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

if(!$appraiseeNextReviewId){
  $nextReviewStartDate = date('Y-m-d');
  $nextReviewEndDate = date('Y-m-d', strtotime('+1 years'));
  mysqli_query($db, "INSERT INTO employee_performance_review_dates(employee_id, review_from_date, review_to_date, last_review_date, next_review_date, is_Active, created_by, created_date) VALUES($appraiseeId, '$nextReviewStartDate','$nextReviewEndDate','$nextReviewStartDate','$nextReviewEndDate','N',$empId,'$currentDate')");
  $appraiseeNextReviewId = mysqli_insert_id($db);
  mysqli_query($db,"update employee_performance_review_dates set next_review_id = $appraiseeNextReviewId where employee_review_date_id = $appraiseeCurrentReviewId and is_Active = 'Y'");
}

$goal_title = $q1_goal_plan_measure = $q2_goal_plan_measure = $q3_goal_plan_measure = $q4_goal_plan_measure = '';
$q1_goal_plan = $q2_goal_plan = $q3_goal_plan = $q4_goal_plan = '';
$id = 0;

// if(isset($_GET['id']) && $_GET['id'] != ''){
//   $id = $_GET['id'];
//
//   $eduHistory = mysqli_query($db,"select * from employee_qualifications where employee_id = $empId and qualifications_id = $id and is_active = 'Y'");
//   $eduHistoryData = mysqli_fetch_assoc($eduHistory);
//
//   $education = @$eduHistoryData['course_name'];
//   $institute = @$eduHistoryData['Institution'];
//   $from = @$eduHistoryData['From_year'];
//   $to = @$eduHistoryData['To_Year'];
//   $percentage = @$eduHistoryData['percentage_obtained'];
// }

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $goal_title = (isset($_POST['goal_title']) && $_POST['goal_title'] != '')?$_POST['goal_title']:$goal_title;
  $q1_goal_plan_measure = (isset($_POST['q1_goal_plan_measure']) && $_POST['q1_goal_plan_measure'] != '')?$_POST['q1_goal_plan_measure']:$q1_goal_plan_measure;
  $q2_goal_plan_measure = (isset($_POST['q2_goal_plan_measure']) && $_POST['q2_goal_plan_measure'] != '')?$_POST['q2_goal_plan_measure']:$q2_goal_plan_measure;
  $q3_goal_plan_measure = (isset($_POST['q3_goal_plan_measure']) && $_POST['q3_goal_plan_measure'] != '')?$_POST['q3_goal_plan_measure']:$q3_goal_plan_measure;
  $q4_goal_plan_measure = (isset($_POST['q4_goal_plan_measure']) && $_POST['q4_goal_plan_measure'] != '')?$_POST['q4_goal_plan_measure']:$q4_goal_plan_measure;
  $q1_goal_plan = (isset($_POST['q1_goal_plan']) && $_POST['q1_goal_plan'] != '')?$_POST['q1_goal_plan']:$q1_goal_plan;
  $q2_goal_plan = (isset($_POST['q2_goal_plan']) && $_POST['q2_goal_plan'] != '')?$_POST['q2_goal_plan']:$q2_goal_plan;
  $q3_goal_plan = (isset($_POST['q3_goal_plan']) && $_POST['q3_goal_plan'] != '')?$_POST['q3_goal_plan']:$q3_goal_plan;
  $q4_goal_plan = (isset($_POST['q4_goal_plan']) && $_POST['q4_goal_plan'] != '')?$_POST['q4_goal_plan']:$q4_goal_plan;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;
 
  if($id){
    // mysqli_query($db,"update employee_qualifications set course_name = '$education', Institution = '$institute', From_year = '$from', To_Year = '$to', percentage_obtained = '$percentage', modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and qualifications_id = $id");
    // if(mysqli_affected_rows($db)){
    //   storeDataInHistory($id, 'employee_qualifications','qualifications_id');
    //   $msg = 'Updated Successfully';
    // }
  }else{
    mysqli_query($db,"insert into employee_goals(employee_id, goal_title, employee_review_date_id, is_Active, created_date, created_by) values($appraiseeId, '$goal_title', $appraiseeNextReviewId, 'Y', '$currentDate', $empId)");
	
    $goalId = mysqli_insert_id($db);
    $goalPlanPeriods = array(
      'Q1' => array($q1_goal_plan, $q1_goal_plan_measure),
      'Q2' => array($q2_goal_plan, $q2_goal_plan_measure),
      'Q3' => array($q3_goal_plan, $q3_goal_plan_measure),
      'Q4' => array($q4_goal_plan, $q4_goal_plan_measure)
    );

    foreach ($goalPlanPeriods as $key => $value) {
      mysqli_query($db, "insert into employee_goal_plans(goal_id, employee_id, goal_plan, goal_plan_measure, goal_plan_period, created_by, created_date) VALUES($goalId, $appraiseeId,'$value[0]','$key','$value[1]',$empId,'$currentDate')");
    }

    if($goalId){
      $msg = 'Added Successfully';
    }
  }
}
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
            </div>
            <!-- /.box-header -->

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="tab-content">
                    <?php if(isset($msg) && $msg != ''){ ?>
                    <div class="alert alert-success alert-dismissible custom-alert">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $msg; ?>
                    </div>
                    <?php } ?>
                    <!-- form start -->
                    <form role="form" id="addGoal" action="addNextReviewGoals.php?appraisee_id=<?php echo $appraiseeId; ?>" method="POST">
                      <div class="box-body">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Goal Title<span class="astrick">*</span></label>
                            <input type="text" class="form-control" id="goal_title" name="goal_title" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Q1 Goal Plan<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q1_goal_plan" name="q1_goal_plan"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q2 Goal Plan<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q2_goal_plan" name="q2_goal_plan"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q3 Goal Plan<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q3_goal_plan" name="q3_goal_plan"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q4 Goal Plan<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q4_goal_plan" name="q4_goal_plan"></textarea>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Q1 Goal Plan Measure<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q1_goal_plan_measure" name="q1_goal_plan_measure"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q2 Goal Plan Measure<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q2_goal_plan_measure" name="q2_goal_plan_measure"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q3 Goal Plan Measure<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q3_goal_plan_measure" name="q3_goal_plan_measure"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="exampleInputEmail1">Q4 Goal Plan Measure<span class="astrick">*</span></label>
                            <textarea class="form-control" id="q4_goal_plan_measure" name="q4_goal_plan_measure"></textarea>
                          </div>
                        </div>
                        </div>
                      </div>
                      <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                        <a href="viewNextReviewGoals.php?appraisee_id=<?php echo $appraiseeId; ?>"><input type="button" class="btn btn-default" value="Cancel" /></a>
                      </div>
                      <!-- /.box-body -->
                    </form>
                    <!-- </form> -->
                  </div>
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
