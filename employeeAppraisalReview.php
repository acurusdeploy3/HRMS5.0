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

$appraiseeStrengthsQuery = mysqli_query($db,"select * from performance_appraisee_strengths where employee_id = $appraiseeId and review_id = $currentReviewId");
$developmentAreasQuery = mysqli_query($db,"select * from performance_appraisal_development_areas where employee_id = $appraiseeId and review_id = $currentReviewId");
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
                <tr><th colspan="5" class="pms_head">Appraise Strengths and Development Areas</th></tr>
                <tr>
                  <th>#</th>
                  <th>
                    Appraisee Strength
                  </th>
                  <th>
                    Apraisee Recommendations
                  </th>
                  <th>
                    Reviewer Recommendation
                  </th>
                  <th>
                    Actions
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#appraisee_strength_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($appraiseeStrengthsQuery) <= 0){
                  echo "<tr><td colspan='5'> No Data found</td></tr>";
                }else{
                  $i = 1;
                  while($value = mysqli_fetch_assoc($appraiseeStrengthsQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$value['appraisee_strength']."</td>";
                    echo "<td>".$value['appraisee_comments']."</td>";
                    echo "<td><textarea class='form-control' id='appraisee_strength_recommendation_".$value['performance_appraisee_strength_id']."'>".$value['manager_comments']."</textarea></td>";
                    echo "<td><a href='javascript:appraiseeStrengthRecommendation(".$value['performance_appraisee_strength_id'].",".$appraiseeId.");'><i class='fa fa-save'></i></a></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
                <tr>
                  <th>#</th>
                  <th>
                    Development Area
                  </th>
                  <th>
                    Apraisee Recommendations
                  </th>
                  <th>
                    Reviewer Recommendation
                  </th>
                  <th>
                    Actions
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#development_areas_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($developmentAreasQuery) <= 0){
                  echo "<tr><td colspan='5'> No Data found</td></tr>";
                }else{
                  $i = 1;
                  while($value = mysqli_fetch_assoc($developmentAreasQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$value['development_area']."</td>";
                    echo "<td>".$value['appraisee_comments']."</td>";
                    echo "<td><textarea class='form-control' id='appraisee_da_recommendation_".$value['performance_development_area_id']."'>".$value['manager_comments']."</textarea></td>";
                    echo "<td><a href='javascript:appraiseeDevelopmentRecommendation(".$value['performance_development_area_id'].",".$appraiseeId.");'><i class='fa fa-save'></i></a></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- <a href="" class="btn btn-info" role="button" style="float:right;margin-right:20px;">Submit</a> -->
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
  <!-- Modal -->
  <div class="modal fade" id="appraisee_strength_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Strength</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Stength</label>
            <input type='text' class='form-control' id="appraisee_strength" />
            <input type='hidden' class='form-control' id="appraisee_strength_page_id" value="<?php echo $appraiseeId; ?>"/>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Recommendations</label>
            <textarea class='form-control' id="appraisee_strength_comments"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="appraiseeStrengths(<?php echo $currentReviewId; ?>)">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="development_areas_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Developement Area</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Development Area</label>
            <input type='text' class='form-control' id="development_areas" />
            <input type='hidden' class='form-control' id="development_areas_page_id" value="<?php echo $appraiseeId; ?>"/>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Recommendations</label>
            <textarea class='form-control' id="development_areas_comments"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="developmentAreas(<?php echo $currentReviewId; ?>)">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  <?php
  require_once('layouts/main-footer.php');
  require_once('layouts/control-sidebar.php');
  require_once('layouts/bottom-footer.php');
  ?>
