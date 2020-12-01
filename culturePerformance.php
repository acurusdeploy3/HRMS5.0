<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$category_id = 2;

$subCategoryQuery = mysqli_query($db,"select perfromance_criteria_sub_category_id,perfromance_criteria_sub_category_name,perfromance_criteria_sub_category_weightage from performance_criteria_sub_categories_master where perfromance_criteria_category_id = $category_id");
$performanceData = [];
foreach(mysqli_fetch_all($subCategoryQuery) as $key => $value){
  $data = [];
  $data["head"][] = @$value[1]." - ".@$value[2]."%";
  $criteriaQuery = mysqli_query($db,"select criteria_id,criteria_name,criteria_weightage from performance_criteria_master where perfromance_criteria_sub_category_id = ".@$value[0]);
  foreach (mysqli_fetch_all($criteriaQuery) as $k => $val) {
    $data["criteria"][] = array(@$val[0],@$val[1],@$val[2]);
    $measuresQuery = mysqli_query($db,"select measure_id,measure_name from performance_measures where criteria_id = ".@$val[0]);
    $data['data'][] = mysqli_fetch_all($measuresQuery);
  }
  $performanceData[$key] = $data;
}

$overallQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name,SUM(score) as totalScore from performance_criteria_sub_categories_master a,performance_criteria_master b,performance_measures c,performance_scores d where a.perfromance_criteria_sub_category_id = b.perfromance_criteria_sub_category_id and b.criteria_id = c.criteria_id and c.measure_id = d.measure_id and perfromance_criteria_category_id = $category_id and employee_id = $empId and d.is_Active = 'Y' group by perfromance_criteria_sub_category_name");
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
        <li><a href="#">PMS</a></li><li class="active">Culture</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Culture</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th>Criteria</th>
                  <th>Measurement</th>
                  <th>Score</th>
                  <th>Weighted Score</th>
                  <th>Appraisal Score</th>
                  <th>Appraisee Comments</th>
                  <th>Actions</th>
                </tr>
                <?php
                foreach($performanceData as $value) {
                  echo "<tr><th colspan='7' class='pms_head'>".@$value['head'][0]."</th></tr>";
                  foreach($value['criteria'] as $key => $val){
                    $criteriaId = $val[0];
                    $criteriaVal = $val[1];
                    $criteriaScore = $val[2];
                    $options = "";
                    $appraiseeComments = "";
                    $measuredScore = 0;
                    foreach ($value['data'][$key] as $k => $v) {
                      $scoresQuery = mysqli_query($db,"select appraisee_comments, measure_score from performance_scores a,performance_measures b where a.measure_id = b.measure_id and employee_id = $empId and a.is_Active = 'Y' and a.measure_id = ".$v[0]);
                      if(mysqli_num_rows($scoresQuery) > 0){
                        $scoresData = mysqli_fetch_assoc($scoresQuery);
                        $options .= "<option value='".@$v[0]."' selected>".@$v[1]."</option>";
                        $measuredScore = @$scoresData['measure_score'];
                        $appraiseeComments = @$scoresData['appraisee_comments'];
                      }else{
                        $options .= "<option value='".@$v[0]."'>".@$v[1]."</option>";
                      }
                    }
                    echo "<tr class='measure_name'><td>".$criteriaVal."</td><td><select class='form-control' id='measure_".$criteriaId."' onchange='getMeasureWeightage(this.value,\"".$criteriaId."\");'>".$options."</select></td>";
                    echo "<td id='measured_score_".$criteriaId."'>".$measuredScore."</td>";
                    echo "<td id='weighted_score_".$criteriaId."'>".$criteriaScore."</td>";
                    echo "<td id='apparisee_score_".$criteriaId."'>".($measuredScore * $criteriaScore / 5)."</td>";
                    echo "<td><textarea rows='1' class='form-control' id='comments_".$criteriaId."'>".$appraiseeComments."</textarea></td>";
                    echo "<td><a href='javascript:updateDeliverablesPerformance(\"".$criteriaId."\",".$category_id.");'><i class='fa fa-save'></i></a></td></tr>";
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th colspan='7' class='pms_head'>OverAll Performance</th></tr>
                <tr id="overAllData">
                <?php
                $totalWeightage = 0;
                if(mysqli_num_rows($overallQuery) <= 0){
                  $overallQuery = mysqli_query($db,"select perfromance_criteria_sub_category_name, 0 as totalScore from performance_criteria_sub_categories_master where perfromance_criteria_category_id = $category_id");
                }
                while($overallData = mysqli_fetch_assoc($overallQuery)){
                  echo "<th>".$overallData['perfromance_criteria_sub_category_name']."</th><td>".$overallData['totalScore']."</td>";
                  $totalWeightage += $overallData['totalScore'];
                }
                $percentage = number_format(($totalWeightage * 0.2), 2, '.', '');
                $satisfactory = getPercentage($totalWeightage);
                ?>
                </tr>
                <tr id="overAllPerformance">
                <?php
                echo "<th>Total Weightage</th><td>".$totalWeightage."</td><th>Weightage of Culture(20%)</th><td>".$percentage."</td><th>Assessment</th><td>".$satisfactory."</td>";
                ?>
                </tr>
              </tbody>
            </table>
          </div>
          <a href="deliverablesPerformance.php" class="btn btn-info" role="button" style="float:left;margin-left:20px;"><< Prev</a>
          <a href="employeeGoals.php" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Next >></a>
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
<script>
$(function(){
  $(".measure_name").each(function(){
    var criteria_id = $(this).find('td:eq(1)').find("select").attr("id").replace("measure_", "");
    //var val = $(this).find('td:eq(1)').find("select option:eq(0)").val();
    var val = $(this).find('td:eq(1)').find("select option:selected").val();
    getMeasureWeightage(val,criteria_id);
  });
});
</script>
