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
        Personal Info

		 <button OnClick="window.location.href='pages/tables/AuditLog.php?id=Employee Details'" type="button" class="btn btn-success pull-right">View Change Log</button>
      </h1>
   
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Employee Details</h3>
			  
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <?php require_once('employeeInfo.php'); ?>
            </div>

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2" id="tab-menu">
                    <ul class="nav nav-tabs tabs-left">
                        <li class="active"><a href="mydetails.php" data-toggle="tab" aria-expanded="true">Official</a></li>
                        <li class=""><a href="personalInfo.php" data-toggle="tab" aria-expanded="false">Personal</a></li>
                        <li class=""><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li ><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="documentsInfo.php" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class=""><a href="skillsInfo.php" data-toggle="tab" aria-expanded="false">Skills</a></li>
            						<!-- <li class=""><a href="#Visa_Immigration" data-toggle="tab" aria-expanded="false">Visa and Immigration</a></li> -->
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
          										  <td colspan="3">
                                  <?php
                                  if($personDetailsData['reporting_manager_id']){
                                    $managerQuery = mysqli_query($db,"select First_Name,MI,Last_Name from employee_details where employee_id = ".@$personDetailsData['reporting_manager_id']." and is_Active = 'Y'");
                                    $managerData = mysqli_fetch_assoc($managerQuery);
                                    echo @$managerData['First_Name']." ".@$managerData['MI']." ".@$managerData['Last_Name'];
                                  }else{
                                    echo "--";
                                  }
                                  ?>
                                </td>
          										</tr>
          										<tr>
          										  <th>Date Joined</th>
          										  <td><?php  $date=date_create(@$doj); if(@$doj!='0001-01-01') { echo date_format($date,'d M Y'); } else { echo '--'; } ?></td>
          										  <th>Months in Acurus</th>
          										  <td><?php echo @$monthsquerydata['experience']; ?></td>
          										</tr>
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
