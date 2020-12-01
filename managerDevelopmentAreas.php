<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$managerDevelopmentAreasQuery = mysqli_query($db,"select * from performance_development_areas where employee_id = $empId");
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
                <tr><th class="pms_head" colspan="2">Developement Areas for the next appraisal year</th></tr>
                <tr><th colspan="2">
                  <span style="float:right;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#manager_development_areas_modal">Add+</button></span>
                </th></tr>
                <?php
                if(mysqli_num_rows($managerDevelopmentAreasQuery) <= 0){
                  echo "<tr><td colspan='2'>No data found</td></tr>";
                }else{
                  $i=1;
                  while($data = mysqli_fetch_assoc($managerDevelopmentAreasQuery)){
                    echo "<table class='table'>";
                    echo "<tbody>";
                    echo "<tr><th class='pms_head' colspan='2'>".$i.".&nbsp;Developement Area&nbsp;:&nbsp;&nbsp;".$data['development_area_title']."</th></tr>";
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
  <div class="modal fade" id="manager_development_areas_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Development Area</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Developement Area</label>
            <input type='text' class='form-control' id="development_area_name" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater One Plan</label>
            <input type='text' class='form-control' id="development_area_q1_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater One Measure</label>
            <textarea class='form-control' id="development_area_q1_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Two Plan</label>
            <input type='text' class='form-control' id="development_area_q2_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Two Measure</label>
            <textarea class='form-control' id="development_area_q2_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Three Plan</label>
            <input type='text' class='form-control' id="development_area_q3_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Three Measure</label>
            <textarea class='form-control' id="development_area_q3_measuere"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Four Plan</label>
            <input type='text' class='form-control' id="development_area_q4_paln" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Quater Four Measure</label>
            <textarea class='form-control' id="development_area_q4_measuere"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onClick="addDevelopmentAreas()">Save</button>
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
