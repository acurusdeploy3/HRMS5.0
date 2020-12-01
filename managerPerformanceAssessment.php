<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$projectsQuery = mysqli_query($db,"select Project_Name, a.Project_ID as project_id, DATE(allocated_from) as startDate, DATE(allocated_to) as endDate, allocated_percentage, appraisee_comments, manager_comments from employee_projects a, all_projects b, performance_project_scores c where a.Project_ID = b.Project_ID and a.Project_ID = c.Project_ID and a.is_Active = 'Y' and a.employee_id = $empId and c.employee_id = $empId");
$strengthsQuery = mysqli_query($db,"select * from performance_appraisee_strengths where employee_id = $empId");
$developmentAreasQuery = mysqli_query($db,"select * from performance_appraisal_development_areas where employee_id = $empId");
$goalsQuery = mysqli_query($db,"select * from performance_goals where employee_id = $empId");
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PMS
        <small>Manager Assessment</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PMS</a></li><li class="active">Manager Assessment</li>
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
                <tr><th colspan='7' class='pms_head'>Manager Assessment</th></tr>
                <tr>
                  <th>#</th>
                  <th>Project</th>
                  <th>Allocated Percentage</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Appraisee Comments</th>
                  <th>Comments</th>
                </tr>
                <?php
                if(mysqli_num_rows($projectsQuery) <= 0){
                  echo "<tr><td colspan='7'>No Data found</td></tr>";
                }else{
                  $i=1;
                  while($value = mysqli_fetch_assoc($projectsQuery)){
                    $val = @$value['project_id'];
                    echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo "<td>".@$value['Project_Name']."</td>";
                      echo "<td>".@$value['allocated_percentage']."</td>";
                      echo "<td>".@$value['startDate']."</td>";
                      echo "<td>".@$value['endDate']."</td>";
                      echo "<td>".@$value['appraisee_comments']."</td>";
                      echo "<td><textarea row='1' class='form-control' id='comments_".str_replace(" ","_",$val)."' onchange='projectManagerComments(this.id,\"".str_replace(" ","_",$val)."\");'>".@$value['manager_comments']."</textarea></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th class="pms_head" colspan="4">Appraise Strengths and Development Areas</th></tr>
                <tr>
                  <th>#</th>
                  <th>Appraisee Strength</th>
                  <th>Appraisee Recommendation</th>
                  <th>
                    Manager Recommendation
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#appraisee_strength_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($strengthsQuery) <= 0){
                  echo "<tr><td colspan='4'>No Data found</td></tr>";
                }else{
                  $i=1;
                  while($strengthsData = mysqli_fetch_assoc($strengthsQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$strengthsData['appraisee_strength']."</td>";
                    echo "<td>".$strengthsData['appraisee_comments']."</td>";
                    echo "<td><textarea class='form-control' id='appraisee_strength_comments_".$i."' onchange='appraiseeStrengthsComments(this.id,".$strengthsData['performance_appraisee_strength_id'].")'>".$strengthsData['manager_comments']."</textarea></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
                <tr>
                  <th>#</th>
                  <th>Development Area</th>
                  <th>Appraisee Recommendation</th>
                  <th>
                    Manager Recommendation
                    <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#development_areas_modal">Add+</button></span>
                  </th>
                </tr>
                <?php
                if(mysqli_num_rows($developmentAreasQuery) <= 0){
                  echo "<tr><td colspan='4'>No Data found</td></tr>";
                }else{
                  $i = 1;
                  while($developmentAreasData = mysqli_fetch_assoc($developmentAreasQuery)){
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".$developmentAreasData['development_area']."</td>";
                    echo "<td>".$developmentAreasData['appraisee_comments']."</td>";
                    echo "<td><textarea class='form-control' id='development_areas_comments_".$i."' onchange='developmentAreasComments(this.id,".$developmentAreasData['performance_development_area_id'].")'>".$developmentAreasData['manager_comments']."</textarea></td>";
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th class="pms_head" colspan="2">Goals for the next appraisal year</th></tr>
                <tr><th colspan="2">
                  <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#goals_modal">Add+</button></span>
                </th></tr>
                <?php
                if(mysqli_num_rows($goalsQuery) <= 0){
                  echo "<tr><td colspan='2'>No data found</td></tr>";
                }else{
                  $i=1;
                  while($data = mysqli_fetch_assoc($goalsQuery)){
                    echo "<table class='table'>";
                    echo "<tbody>";
                    echo "<tr><th class='pms_head' colspan='2'>".$i.".&nbsp;Goal&nbsp;:&nbsp;&nbsp;".$data['goal_title']."</th></tr>";
                    echo "<tr><th>Quater One Plan:</th><td>".$data['qtr_one_plan']."</td></tr>";
                    echo "<tr><th>Quater One Measure:</th><td>".$data['qtr_one_measure']."</td></tr>";
                    echo "<tr><th>Quater Two Plan:</th><td>".$data['qtr_two_plan']."</td></tr>";
                    echo "<tr><th>Quater Two Measure:</th><td>".$data['qtr_two_measure']."</td></tr>";
                    echo "<tr><th>Quater Three Plan:</th><td>".$data['qtr_three_plan']."</td></tr>";
                    echo "<tr><th>Quater Three Measure:</th><td>".$data['qtr_three_measure']."</td></tr>";
                    echo "<tr><th>Quater Four Plan:</th><td>".$data['qtr_four_plan']."</td></tr>";
                    echo "<tr><th>Quater Four Measure:</th><td>".$data['qtr_four_measure']."</td></tr>";
                    echo "</tbody>";
                    echo "</table>";
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
            <input type='hidden' class='form-control' id="appraisee_strength_page_id" value="manager"/>
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
            <input type='hidden' class='form-control' id="development_areas_page_id" value="manager"/>
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

  <!-- Modal -->
  <div class="modal fade" id="goals_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Goal</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Goal</label>
            <input type='text' class='form-control' id="goal_name" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater One Plan</label>
            <input type='text' class='form-control' id="goal_q1_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater One Measure</label>
            <textarea class='form-control' id="goal_q1_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Two Plan</label>
            <input type='text' class='form-control' id="goal_q2_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Two Measure</label>
            <textarea class='form-control' id="goal_q2_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Three Plan</label>
            <input type='text' class='form-control' id="goal_q3_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Three Measure</label>
            <textarea class='form-control' id="goal_q3_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Four Plan</label>
            <input type='text' class='form-control' id="goal_q4_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Four Measure</label>
            <textarea class='form-control' id="goal_q4_measuere"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="addGoals()">Save</button>
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
