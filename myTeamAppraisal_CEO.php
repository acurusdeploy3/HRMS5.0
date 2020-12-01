<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

if(isset($_REQUEST['approveId']) && $_REQUEST['approveId'] != '' && is_numeric($_REQUEST['approveId'])){
  mysqli_query($db,"update employee_performance_review_dates set review_status = 6 where employee_id = ".$_REQUEST['approveId']." and is_Active = 'Y' and next_review_id != 0 and review_status = 5");
}

if(isset($_REQUEST['declineId']) && $_REQUEST['declineId'] != '' && is_numeric($_REQUEST['declineId'])){
  mysqli_query($db,"update employee_performance_review_dates set review_status = 11 where employee_id = ".$_REQUEST['declineId']." and is_Active = 'Y' and next_review_id != 0 and review_status = 5");
}

$myTeamQuery = mysqli_query($db,"select a.employee_id as employee_id, First_Name, MI, Last_Name, Department, review_status from employee_details a, employee_performance_review_dates b where a.employee_id = b.employee_id and a.is_Active = 'Y' and b.is_Active = 'Y' and review_status = 5");
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        PMS
        <small>Team Appraisal</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">PMS</a></li><li class="active">Team Appraisal</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Team Appraisal</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr><th colspan='7' class='pms_head'>MY TEAM APPRAISAL</th></tr>
                <tr>
                  <th>#</th>
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Department</th>
                  <th>Actions</th>
                </tr>
                <?php
                if(mysqli_num_rows($myTeamQuery) < 1){
                  echo "<tr><td colspan='7'> No Employees Found </td></tr>";
                }else{
                  $i = 1;
                  while($data = mysqli_fetch_assoc($myTeamQuery)){
                    echo "<tr>
                    <td>".$i."</td>
                    <td>".$data['employee_id']."</td>
                    <td>".$data['First_Name']." ".$data['MI']." ".$data['Last_Name']."</td>
                    <td>".$data['Department']."</td>";
                    if($data['review_status'] == 5){
                        echo "<td>
                          <input type='button' class='btn btn-info' value='View' />
                          <td><a href='myTeamAppraisal_CEO.php?approveId=".$data['employee_id']."' role='button' class='btn btn-success'>Approve</a></td>
                          <td><a href='myTeamAppraisal_CEO.php?declineId=".$data['employee_id']."' role='button' class='btn btn-danger'>Decline</a></td>
                        </td>";
                    }else{
                        echo ($data['review_status'] == 6)?"Approved":"Declined";
                    }

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
  <?php
  require_once('layouts/main-footer.php');
  require_once('layouts/control-sidebar.php');
  require_once('layouts/bottom-footer.php');
  ?>
