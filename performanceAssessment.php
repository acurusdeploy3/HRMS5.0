<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$projectsQuery = mysqli_query($db,"select Project_Name, a.Project_ID as project_id, DATE(allocated_from) as startDate, DATE(allocated_to) as endDate, allocated_percentage, appraisee_comments, manager_comments from employee_projects a, all_projects b, performance_project_scores c where a.Project_ID = b.Project_ID and a.Project_ID = c.Project_ID and a.is_Active = 'Y' and a.employee_id = $empId and c.employee_id = $empId");
$appraiseeStrengthsQuery = mysqli_query($db,"select * from performance_appraisee_strengths where employee_id = $empId");
$developmentAreasQuery = mysqli_query($db,"select * from performance_appraisal_development_areas where employee_id = $empId");
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
                <tr><th colspan='6' class='pms_head'>Appraise Self Assessment</th></tr>
                <tr>
                  <th>#</th>
                  <th>Project</th>
                  <th>Allocated Percentage</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Apraisee Recommendation</th>
                </tr>
                <?php
                $i=1;
                while($value = mysqli_fetch_assoc($projectsQuery)){
                  $val = @$value['project_id'];
                  echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".@$value['Project_Name']."</td>";
                    echo "<td>".@$value['allocated_percentage']."</td>";
                    echo "<td>".@$value['startDate']."</td>";
                    echo "<td>".@$value['endDate']."</td>";
                    echo "<td><textarea row='1' class='form-control' id='comments_".str_replace(" ","_",$val)."' onchange='projectComments(this.id,\"".str_replace(" ","_",$val)."\");'>".@$value['appraisee_comments']."</textarea></td>";
                  echo "</tr>";
                  $i++;
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th colspan='3' class='pms_head'>Appraisee Progress against Goals</th></tr>
                <tr>
                  <th>#</th>
                  <th>Goal</th>
                  <th>Apraisee Recommendation</th>
                </tr>
                <?php
                if(mysqli_num_rows($projectsQuery) <= 0){
                  echo "<tr><td colspan='3'>No Data found</td></tr>";
                }else{
                  $i=1;
                  while($value = mysqli_fetch_assoc($projectsQuery)){
                    $val = @$value['project_id'];
                    echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo "<td>".@$value['Project_Name']."</td>";
                      echo "<td><textarea row='1' class='form-control' id='comments_".str_replace(" ","_",$val)."' onchange='projectComments(this.id,\"".str_replace(" ","_",$val)."\");'>".@$value['appraisee_comments']."</textarea></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th colspan="3" class="pms_head">Appraise Strengths and Development Areas</th></tr>
                <tr>
                  <th>#</th>
                  <th>
                    Appraisee Strength
                  </th>
                  <th>
                    Apraisee Recommendation
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#appraisee_strength_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($appraiseeStrengthsQuery) <= 0){
                  echo "<tr><td colspan='3'> No Data found</td></tr>";
                }else{
                  $i = 1;
                  while($value = mysqli_fetch_assoc($appraiseeStrengthsQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$value['appraisee_strength']."</td>";
                    echo "<td><textarea class='form-control' id='appraisee_strength_recommendation_".$value['performance_appraisee_strength_id']."' onchange='appraiseeStrengthRecommendation(this.id,".$value['performance_appraisee_strength_id'].")'>".$value['appraisee_comments']."</textarea></td>";
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
                    Apraisee Recommendation
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#development_areas_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($developmentAreasQuery) <= 0){
                  echo "<tr><td colspan='3'> No Data found</td></tr>";
                }else{
                  $i = 1;
                  while($value = mysqli_fetch_assoc($developmentAreasQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$value['development_area']."</td>";
                    echo "<td><textarea class='form-control' id='appraisee_da_recommendation_".$value['performance_development_area_id']."' onchange='appraiseeDevelopmentRecommendation(this.id,".$value['performance_development_area_id'].")'>".$value['appraisee_comments']."</textarea></td>";
                    echo "</tr>";
                    $i++;
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
            <input type='hidden' class='form-control' id="appraisee_strength_page_id" value="appraisee"/>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Recommendations</label>
            <textarea class='form-control' id="appraisee_strength_comments"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="appraiseeStrengths()">Save</button>
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
            <input type='hidden' class='form-control' id="development_areas_page_id" value="appraisee"/>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Recommendations</label>
            <textarea class='form-control' id="development_areas_comments"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="developmentAreas()">Save</button>
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
