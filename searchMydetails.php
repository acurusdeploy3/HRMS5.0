<?php
require_once("config.php");
session_start();
$employeeid=$_SESSION['login_user'];
$employeegrp=$_SESSION['login_user_group'];
$Searchid = $_GET['empId'];
$CheckRep = mysqli_query($db,"select * from employee_details where reporting_manager_id='$employeeid' and employee_id='$Searchid'");
if($employeegrp=='HR Manager' || $employeegrp=='HR' || mysqli_num_rows($CheckRep)>0 )
{
?>
<?php
require_once("config.php");
session_start();
$backpage = $_SESSION['searchBack'];
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
require_once("searchEmployeeCommon.php");
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Personal Info
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
 		 <li><a href="#">Search Employee</a></li>
        <li><a href="#">Employee Info</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
			<button onclick="window.location='pages/tables/<?php echo $backpage ?>'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
           <br>
			Employee Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <?php require_once('searchEmployeeInfo.php'); ?>
            </div>

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2" id="tab-menu">
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active"><a href="searchMydetails.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="true">Official</a></li>
                        <li class=""><a href="searchPersonalInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Personal</a></li>
                        <li class=""><a href="searchContactInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Contact</a></li>
            						<li class=""><a href="searchWorkHistoryInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="searchEducationInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Education</a></li>
									 <li class=""><a href="searchFamilyInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="searchCertificationsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="searchKYEInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="searchDocumentsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class=""><a href="searchSkillsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Skills</a></li>
                    </ul>
                  </div>
                  <div class="col-md-10" id="tab-content">
                    <div class="tab-content">
                      <div class="content-tab-pane tab-pane active" id="Official">
          							<div class="border-class">
          								<table class="table table-striped">
          									<tbody>
          										<tr>
          										  <th>Employee Id</th>
          										  <td colspan="3"><?php echo @$personDetailsData['employee_id']; ?></td>
          										</tr>
          										<tr>
          										  <th>First Name</th>
          										  <td><?php echo @$personDetailsData['First_Name']; ?></td>
          										  <th>Last Name</th>
          										  <td><?php echo @$personDetailsData['Last_Name']; ?></td>
          										</tr>
          										<tr>
          										  <th>Middle Name</th>
          										  <td><?php echo @$personDetailsData['MI']; ?></td>
          										  <th>Employment Type</th>
          										  <td><?php echo @$personDetailsData['Job_Type']; ?></td>
          										</tr>
          										<tr>
          										  <th>Designation</th>
          										  <td><?php echo @$personDetailsData['Employee_Designation']; ?></td>
          										  <th>Email</th>
          										  <td><?php echo @$personDetailsData['Official_Email']; ?></td>
          										</tr>
          										<tr>
          										  <th>Business Unit</th>
          										  <td><?php echo @$personDetailsData['business_unit']; ?></td>
          										  <th>Department</th>
          										  <td><?php echo @$personDetailsData['Department']; ?></td>
          										</tr>
          										<tr>
          										  <th>Reporting Manager</th>
          										  <td>
                                  <?php
                                  if($personDetailsData['reporting_manager_id']){
                                    $managerQuery = mysqli_query($db,"select First_Name,MI,Last_Name from employee_details where employee_id = ".@$personDetailsData['reporting_manager_id']." ");
                                    $managerData = mysqli_fetch_assoc($managerQuery);
                                    echo @$managerData['First_Name']." ".@$managerData['MI']." ".@$managerData['Last_Name'];
                                  }else{
                                    echo "--";
                                  }
                                  ?>
                                </td>
                                                 <th>Mentor</th>
                                                <td>
                                                  <?php
									if($personDetailsData['mentor_id']!=''){
                                    $mentorQuery = mysqli_query($db,"select First_Name,MI,Last_Name from employee_details where employee_id = ".@$personDetailsData['mentor_id']." ");
                                    $mentorData = mysqli_fetch_assoc($mentorQuery);
                                    echo @$mentorData['First_Name']." ".@$mentorData['MI']." ".@$mentorData['Last_Name'];
                                  }else{
                                    echo "--";
                                  }
                                  ?>
                                                </td>
          										</tr>
          										<tr>
          										  <th>Date Joined</th>
          										  <td><?php $date=date_create(@$doj); if(@$doj!='0001-01-01') { echo date_format($date,'d M Y');  } else { echo '--';}?></td>
          										  <th>Months in Acurus</th>
          										  <td><?php echo @$monthsquerydata['experience']; ?></td>
          										</tr>
                                            <?php 
												if(isset($deactivatedRow))
                                                {
											?>
                                            	<tr>
          										  <th>Status</th>
          										  <td><?php echo @$deactivatedRow['status']; ?></td>
          										  <th>Reason Left</th>
          										  <td><?php echo @$deactivatedRow['reason']; ?></td>
                                                </tr>
                                            <tr>
                                                 <th>Last Date of Working</th>
          										  <td><?php $date=date_create(@$deactivatedRow['ldw']); echo date_format($date,'d M Y'); ?></td>
                                            	<th></th>
          										  <td></td>
          										</tr>
                                            <?php
                                                }
                                                ?>
          									</tbody>
          								</table>
          							</div>
          						</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer">
            </div>
          </div>
          <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/bottom-footer.php');
//require_once('layouts/control-sidebar.php');
?>
<?php
}
else
{
  header("Location: pages/forms/Logout.php");
}

?>
