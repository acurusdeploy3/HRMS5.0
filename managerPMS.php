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
          <h3 class="box-title">Manager Assessment</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th colspan='8' class='pms_head'>Appraisee Details and Recommendation</th>
                </tr>
                <tr>
                  <th>Name</th><td></td>
                  <th>Employee ID</th><td></td>
                  <th>Title</th><td></td>
                  <th>Date</th><td></td>
                </tr>
                <tr>
                  <th>Appraisee Recommendation</th><td colspan="3"></td>
                  <th>Appraisee Comments</th><td colspan="3"></td>
                </tr>
                <tr>
                  <th colspan='8' class='pms_head'>Reviewer Details and Recommendation</th>
                </tr>
                <tr>
                  <th>Name</th><td></td>
                  <th>Employee ID</th><td></td>
                  <th>Title</th><td></td>
                  <th>Date</th><td></td>
                </tr>
                <tr>
                  <th>Recommended Increase</th><td colspan="3"></td>
                  <th>Proposed revised Annual CTC</th><td colspan="3"></td>
                </tr>
                <tr>
                  <th>Reviewer Comments</th><td colspan="7"></td>
                </tr>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr>
                  <th colspan='6' class='pms_head'>Final Recommendation</th>
                </tr>
                <tr>
                  <th>Final Review with</th><td></td>
                  <th>Final Review Date</th><td></td>
                  <th>HR Representative Name</th><td></td>
                </tr>
                <tr>
                  <th>HR Comments</th><td colspan="5"></td>
                </tr>
                <tr>
                  <td colspan="6">Revisions in:</td>
                </tr>
                <tr>
                  <th>Title</th><td></td>
                  <th>Band</th><td></td>
                  <th>Level</th><td></td>
                </tr>
                <tr>
                  <th>Approved Salary Increase</th><td colspan="2"></td>
                  <th>Final Approved CTC Annual</th><td colspan="2"></td>
                </tr>
                <tr>
                  <th>CEO Comments</th><td colspan="5"></td>
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
