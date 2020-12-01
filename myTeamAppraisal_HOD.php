<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$empReportingToHODQuery = mysqli_query($db,"select employee_id from employee_details where reporting_manager_id = $empId");
$empReportingToHODData = mysqli_fetch_array($empReportingToHODQuery,MYSQLI_NUM);

if(isset($_REQUEST['empId']) && $_REQUEST['empId'] != '' && is_numeric($_REQUEST['empId'])){
  $status = in_array($_REQUEST['empId'],$empReportingToHODData)?3:2;
  mysqli_query($db,"update employee_performance_review_dates set review_status = $status where employee_id = ".$_REQUEST['empId']." and is_Active = 'Y' and next_review_id = 0 and review_status = 1");
  //mysqli_query($db,"update employee_performance_review_dates set review_status = 2 where employee_id IN (select employee_id from employee_details where reporting_manager_id =".$_REQUEST['empId'].") and is_Active = 'Y' and next_review_id = 0 and review_status = 1");
  mysqli_query($db,"update employee_performance_review_dates set review_status = 4 where employee_id = ".$_REQUEST['empId']." and is_Active = 'Y' and next_review_id != 0 and review_status = 8");
  mysqli_query($db,"update employee_performance_review_dates set review_status = 4 where employee_id IN (select employee_id from employee_details where reporting_manager_id =".$_REQUEST['empId'].") and is_Active = 'Y' and next_review_id != 0 and review_status = 8");
}

//$myTeamQuery = mysqli_query($db,"select a.employee_id as employee_id, First_Name, MI, Last_Name, Department, next_review_id, review_status from employee_details a, employee_performance_review_dates b where a.employee_id = b.employee_id and a.is_Active = 'Y' and b.is_Active = 'Y' and review_status IN (1,7,8) and reporting_manager_id = $empId");
$myTeamQuery = mysqli_query($db,"select a.employee_id as employee_id, First_Name, MI, Last_Name, Department, next_review_id, review_status from employee_details a, employee_performance_review_dates b where a.employee_id = b.employee_id and a.is_Active = 'Y' and b.is_Active = 'Y' and review_status IN (1,7,8)");
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
                  <th>Send To Lead</th>
                  <th>Send To HR</th>
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
                    if($data['review_status'] == 7){
                      echo "<td>--</td><td>--</td>";
                    }else{
                      echo "<td><a href='MyTeamAppraisal_HOD.php?empId=".$data['employee_id']."' role='button' class='btn btn-success'";
                    //  if($data['review_status'] == 8 && $data['next_review_id'] != 0){
                       // echo "disabled";
                    //  }
                      $labelTxt = in_array($data['employee_id'],$empReportingToHODData)?'Move to Emp':'Move to Lead';
                      echo ">$labelTxt</a></td>
                      <td><a href='MyTeamAppraisal_HOD.php?empId=".$data['employee_id']."' role='button' class='btn btn-success'";
                      if($data['review_status'] == 1 && $data['next_review_id'] == 0){
                        echo "disabled";
                      }
                      echo ">Move to HR</a></td>";
                    }

                    echo "<td><a href='reviewerHomePage.php?appraisee_id=".$data['employee_id']."' role='button' class='btn btn-info'>View / Edit</a></td>
                    </tr>";
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
