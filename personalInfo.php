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
                        <li class=""><a href="mydetails.php" data-toggle="tab" aria-expanded="true">Official</a></li>
                        <li class="active"><a href="personalInfo.php" data-toggle="tab" aria-expanded="false">Personal</a></li>
                        <li class=""><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class="" ><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="documentsInfo.php" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class=""><a href="skillsInfo.php" data-toggle="tab" aria-expanded="false">Skills</a></li>
            						<!-- <li class=""><a href="#Visa_Immigration" data-toggle="tab" aria-expanded="false">Visa and Immigration</a></li> -->
                    </ul>
                  </div>
                  <div class="col-md-10" id="tab-content">
                    <div class="tab-content">
                        <div class="content-tab-pane tab-pane active">
            							<div class="border-class">
                            <div class="add-btn-class">
                              <a href="personalDetailsEdit.php"><i class="fa fa-pencil"></i> Edit</a>
            								</div>
                            <table class="table table-striped">
            									<tbody>
            										<tr>
            										  <th>DOB</th>
            										  <td><?php $date=date_create(@$personDetailsData['Date_of_Birth']); echo date_format($date,'d M Y'); ?></td>
            										  <th>Gender</th>
            										  <td><?php echo @$personDetailsData['Gender']; ?></td>
            										</tr>
            										<tr>
            										  <th>Personal Email</th>
            										  <td><?php echo @$personDetailsData['Employee_Personal_Email']; ?></td>
            										  <th>Primary Mobile</th>
            										  <td><?php echo @$personDetailsData['country_code'].@$personDetailsData['Primary_Mobile_Number']; ?></td>
            										</tr>
                                <tr>
            										  <th>Marital Status</th>
            										  <td><?php echo @$personDetailsData['Marital_Status']; ?></td>
														<th>Alternate Contact</th>
            										  <td><?php echo @$personDetailsData['Alternate_Mobile_Number']; ?></td>
            										</tr>
													<tr>
            										  <th>PF Number</th>
            										  <td><?php echo @$personDetailsData['PF_Number']; ?></td>
            										  <th>UAN Number</th>
            										  <td><?php echo @$personDetailsData['UAN_Number']; ?></td>
            										</tr>
												   <tr>
            										  <th>Insurance Number</th>
            										  <td><?php echo @$personDetailsData['Insurance_num']; ?></td>
													  <th>Insurance Expiry Date</th>
            										  <td><?php if(@$personDetailsData['insurance_expiry']!='0001-01-01' && @$personDetailsData['insurance_expiry']!='') { $date=date_create(@$personDetailsData['insurance_expiry']); echo date_format($date,'d M Y'); } else { echo '--'; } ?></td>
            										</tr>
													 <tr>
													   <th>ESIC Number</th>
            										  <td><?php echo @$personDetailsData['ESIC_Number']; ?></td>
                                                     <th>Blood Group</th>
            										  <td><?php echo @$personDetailsData['Employee_Blood_Group']; ?></td>
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
