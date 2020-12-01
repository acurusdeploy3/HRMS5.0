<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
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
                  <th>Title</th><td></td>
                  <th>Band</th><td><?php echo @$resourceManagementData['band']; ?></td>
                  <th>Level</th><td><?php echo @$resourceManagementData['level']; ?></td>
                  <th>Education</th><td></td>
                </tr>
                <tr>
                  <th colspan='8' class='pms_head'>Review Period Details</th>
                </tr>
                <tr>
                  <th>From</th><td><?php echo @$empReviewData['review_from_date']; ?></td>
                  <th>To</th><td><?php echo @$empReviewData['review_to_date']; ?></td>
                  <th>Apprisal Date</th><td><?php echo @$empReviewData['next_review_date']; ?></td>
                  <th>Last Review Date</th><td><?php echo @$empReviewData['last_review_date']; ?></td>
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
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='text' class='form-control' /></td>
                  <td rowspan="3"><textarea class="form-control" rows="6"></textarea></td>
                  <td rowspan="3"><textarea class="form-control" rows="6"></textarea></td>
                </tr>
                <tr>
                  <th>Culture Fit</th>
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='text' class='form-control' /></td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='number' min='0' max='10' class='form-control' /></td>
                  <td><input type='text' class='form-control' /></td>
                </tr>
              </tody>
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
