<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
if(isset($_REQUEST['empId']) && $_REQUEST['empId'] != '' && is_numeric($_REQUEST['empId'])){
  mysqli_query($db,"update employee_performance_review_dates set review_status = 1 where employee_id = ".$_REQUEST['empId']." and is_Active = 'Y' and next_review_id = 0 and review_status = 9");
  //mysqli_query($db,"update employee_performance_review_dates set review_status = 1 where employee_id IN (select employee_id from employee_details where reporting_manager_id =".$_REQUEST['empId'].") and is_Active = 'Y' and next_review_id = 0 and review_status = 9");
}

if(isset($_REQUEST['approveId']) && $_REQUEST['approveId'] != '' && is_numeric($_REQUEST['approveId'])){
  mysqli_query($db,"update employee_performance_review_dates set review_status = 5 where employee_id = ".$_REQUEST['approveId']." and is_Active = 'Y' and next_review_id != 0 and review_status = 4");
}

if(isset($_REQUEST['declineId']) && $_REQUEST['declineId'] != '' && is_numeric($_REQUEST['declineId'])){
  mysqli_query($db,"update employee_performance_review_dates set review_status = 10 where employee_id = ".$_REQUEST['declineId']." and is_Active = 'Y' and next_review_id != 0 and review_status = 4");
}
$eligibleEmployeesQuery = mysqli_query($db,"select a.employee_id as employee_id, First_Name, MI, Last_Name, Department, review_status from employee_details a, employee_performance_review_dates b where a.employee_id = b.employee_id and a.is_Active = 'Y' and b.is_Active = 'Y' and review_status != 0 and next_review_id = 0");
$approvalEmployeesQuery = mysqli_query($db,"select a.employee_id as employee_id, First_Name, MI, Last_Name, Department, review_status from employee_details a, employee_performance_review_dates b where a.employee_id = b.employee_id and a.is_Active = 'Y' and b.is_Active = 'Y' and review_status = 4 and next_review_id != 0");
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
        <li><a href="#">PMS</a></li><li class="active">Appraisal List</li>
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
                <tr><th colspan='7' class='pms_head'>PMS Eligible Candidates List</th></tr>
                <tr>
                  <th>#</th>
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Department</th>
                  <th>Status</th>
                </tr>
                <?php
                if(mysqli_num_rows($eligibleEmployeesQuery) < 1){
                  echo "<tr><td colspan='5'> No Data Found </td></tr>";
                }else{
                  $i = 1;
                  while($data = mysqli_fetch_assoc($eligibleEmployeesQuery)){
                    switch ($data['review_status']) {
                      case 1:
                        $status = 'Moved to HOD';
                        break;
                      case 2:
                        $status = 'Moved to LEAD';
                        break;
                      case 3:
                        $status = 'Moved to Employee';
                        break;
                      case 4:
                        $status = 'Moved to HR';
                        break;
                      case 5:
                        $status = 'Waiting For Approval';
                        break;
                      case 6:
                        $status = 'Approved';
                        break;

                      default:
                        $status = 'Eligible For Review';
                        break;
                    }
                    echo "<tr>
                    <td>".$i."</td>
                    <td>".$data['employee_id']."</td>
                    <td>".$data['First_Name']." ".$data['MI']." ".$data['Last_Name']."</td>
                    <td>".$data['Department']."</td>";
                    if($status == 'Eligible For Review'){
                      echo "<td><a href='appraisalList.php?empId=".$data['employee_id']."' role='button' class='btn btn-success'>Move to HOD</a></td>";
                    }else{
                      echo "<td>".$status."</td>";
                    }
                    echo "</tr>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
            <table class="table">
              <tbody>
                <tr><th colspan='7' class='pms_head'>Waiting For Approval</th></tr>
                <tr>
                  <th>#</th>
                  <th>Employee ID</th>
                  <th>Employee Name</th>
                  <th>Department</th>
                  <th>Actions</th>
                </tr>
                <?php
                if(mysqli_num_rows($approvalEmployeesQuery) < 1){
                  echo "<tr><td colspan='5'> No Data Found </td></tr>";
                }else{
                  $i = 1;
                  while($data = mysqli_fetch_assoc($approvalEmployeesQuery)){
                    echo "<tr>
                      <td>".$i."</td>
                      <td>".$data['employee_id']."</td>
                      <td>".$data['First_Name']." ".$data['MI']." ".$data['Last_Name']."</td>
                      <td>".$data['Department']."</td>";
                      if($data['review_status'] == 4){
                          echo "<td>
                            <input type='button' class='btn btn-info' value='View' />
                            <td><a href='appraisalList.php?approveId=".$data['employee_id']."' role='button' class='btn btn-success'>Move to CEO</a></td>
                            <td><a href='appraisalList.php?declineId=".$data['employee_id']."' role='button' class='btn btn-danger'>Decline</a></td>
                          </td>";
                      }else{
                          echo ($data['review_status'] == 5)?"Moved to CEO":"Declined";
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
