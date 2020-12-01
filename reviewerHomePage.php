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
$reviewDetailsQry = mysqli_query($db,"select * from employee_performance_review_dates where employee_id = $appraiseeId and is_Active = 'Y'");
$reviewDetailsData = mysqli_fetch_assoc($reviewDetailsQry);
$currentReviewId = isset($reviewDetailsData['employee_review_date_id'])?$reviewDetailsData['employee_review_date_id']:0;
$nextReviewId = isset($reviewDetailsData['next_review_id'])?$reviewDetailsData['next_review_id']:0;


// Employment Details Query
$personDetailsQry = mysqli_query($db,"select * from employee_details where employee_id = $appraiseeId and is_Active = 'Y'");
$personDetailsData = mysqli_fetch_assoc($personDetailsQry);
$dob = date('Y-m-d',strtotime($personDetailsData['Date_of_Birth']));
$doj = date('Y-m-d',strtotime($personDetailsData['Date_Joined']));

// Resource Management Query
$resourceManagementQry = mysqli_query($db,"select * from resource_management_table where employee_id = $appraiseeId and is_Active = 'Y' limit 1");
$resourceManagementData = mysqli_fetch_assoc($resourceManagementQry);

$overallDeliverablesQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name,SUM(score) as totalScore from performance_criteria_sub_categories_master a,performance_criteria_master b,performance_measures c,performance_scores d where a.perfromance_criteria_sub_category_id = b.perfromance_criteria_sub_category_id and b.criteria_id = c.criteria_id and c.measure_id = d.measure_id and perfromance_criteria_category_id = 1 and employee_id = $appraiseeId and d.is_Active = 'Y' group by perfromance_criteria_sub_category_name");

$totalDelivarablesWeightage = 0;
while($overallDeliverablesData = mysqli_fetch_assoc($overallDeliverablesQuery)){
  $totalDelivarablesWeightage += $overallDeliverablesData['totalScore'];
}
$delivarablesPercentage = number_format(($totalDelivarablesWeightage * 0.8), 2, '.', '');
$delivarableSatisfactory = getPercentage($totalDelivarablesWeightage);

$overallCultureQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name,SUM(score) as totalScore from performance_criteria_sub_categories_master a,performance_criteria_master b,performance_measures c,performance_scores d where a.perfromance_criteria_sub_category_id = b.perfromance_criteria_sub_category_id and b.criteria_id = c.criteria_id and c.measure_id = d.measure_id and perfromance_criteria_category_id = 2 and employee_id = $appraiseeId and d.is_Active = 'Y' group by perfromance_criteria_sub_category_name");

$totalCultureWeightage = 0;
while($overallCultureData = mysqli_fetch_assoc($overallCultureQuery)){
  $totalCultureWeightage += $overallCultureData['totalScore'];
}
$culturePercentage = number_format(($totalCultureWeightage * 0.2), 2, '.', '');
$cultureSatisfactory = getPercentage($totalCultureWeightage);

$percentage = $delivarablesPercentage + $culturePercentage;
$satisfactory = getPercentage($percentage);
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
        <li><a href="#">PMS</a></li><li class="active">Employee Performance</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Employee Performance management system</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th colspan='8' class='pms_head'>Employee Details</th>
                </tr>
                <tr>
                  <th>EMP ID</th><td><?php echo @$personDetailsData['employee_id']; ?></td>
                  <th>EMP Name</th><td><?php echo @$personDetailsData['First_Name']." ".@$personDetailsData['MI']." ".@$personDetailsData['Last_Name']; ?></td>
                  <th>Date of Joining</th><td><?php echo @$doj; ?></td>
                  <th>Experience in Acurus</th><td><?php echo @$personDetailsData['Experience_At_Acurus']; ?></td>
                </tr>
                <tr>
                  <th>Title</th><td><?php echo @$resourceManagementData['designation']; ?></td>
                  <th>Band</th><td><?php echo @$resourceManagementData['band']; ?></td>
                  <th>Level</th><td><?php echo @$resourceManagementData['level']; ?></td>
                  <th>Education</th><td></td>
                </tr>
                <tr>
                  <th colspan='8' class='pms_head'>Review Period Details</th>
                </tr>
                <tr>
                  <th>From</th><td><?php echo @$reviewDetailsData['review_from_date']; ?></td>
                  <th>To</th><td><?php echo @$reviewDetailsData['review_to_date']; ?></td>
                  <th>Appraisal Date</th><td><?php echo @$reviewDetailsData['next_review_date']; ?></td>
                  <th>Last Review Date</th><td><?php echo @$reviewDetailsData['last_review_date']; ?></td>
                </tr>
                <tr>
                  <th>Last Annual CTC in Lakhs</th><td></td>
                  <th>Previous Experience(Relevant)</th><td><?php echo @$personDetailsData['Total_Experience']-@$personDetailsData['Experience_At_Acurus']; ?></td>
                  <th>Acurus Experience</th><td><?php echo @$personDetailsData['Experience_At_Acurus']; ?></td>
                  <th>Total Experience</th><td><?php echo @$personDetailsData['Total_Experience']; ?></td>
                </tr>
                <tr>
                  <th colspan='8' class='pms_head'>Attendance Record</th>
                </tr>
                <tr>
                  <th>Working Hours</th><td></td>
                  <th>Absent</th><td></td>
                  <th>Present</th><td></td>
                  <th>Late</th><td></td>
                </tr>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr>
                  <th colspan='6' class='pms_head'>Performance Appraisal Results</th>
                </tr>
                <tr>
                  <th>Description</th>
                  <th>Total Score</th>
                  <th>Weighted Score</th>
                  <th>Result</th>
                  <th>Strengths</th>
                  <th>Areas of Improvement</th>
                </tr>
                <tr>
                  <th>Deliverables</th>
                  <td><?php echo $delivarablesPercentage; ?></td>
                  <td>80</td>
                  <td><?php echo $delivarableSatisfactory; ?></td>
                  <td rowspan="3"><textarea class="form-control" rows="6"></textarea></td>
                  <td rowspan="3"><textarea class="form-control" rows="6"></textarea></td>
                </tr>
                <tr>
                  <th>Culture Fit</th>
                  <td><?php echo $culturePercentage; ?></td>
                  <td>20</td>
                  <td><?php echo $cultureSatisfactory; ?></td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td><?php echo $percentage; ?></td>
                  <td>100</td>
                  <td><?php echo $satisfactory; ?></td>
                </tr>
              </tody>
            </table>
          </div>
          <a href="myTeamAppraisal.php" class="btn btn-info" role="button" style="float:left;margin-left:20px;"><< Prev</a>
          <a href="managerDeliverablesPerformance.php?appraisee_id=<?php echo $appraiseeId; ?>" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Next >></a>
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
