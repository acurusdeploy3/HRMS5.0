<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$id = 0;
$msg = '';

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  mysqli_query($db,"update employee_qualifications set is_active = 'N' where employee_id = $empId and qualifications_id = $id");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($id, 'employee_qualifications','qualifications_id');
    $empQualificationsQry = mysqli_query($db,"select * from employee_qualifications where employee_id = $empId and is_active = 'Y'");
    $msg = 'Deleted Successfully';
  }
}
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
                        <li class=""><a href="personalInfo.php" data-toggle="tab" aria-expanded="false">Personal</a></li>
                        <li class=""><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class="active"><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class=""><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
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
                          <?php if($msg != ''){ ?>
                          <div class="alert alert-success alert-dismissible custom-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $msg; ?>
                          </div>
                          <?php } ?>
            							<div class="border-class table-body-class">
                            <div class="add-btn-class">
            									<a href="addEducation.php"><button type="button" class="btn btn-primary">Add+</button></a>
            								</div>
            								<table class="table table-striped">
            									<tbody>
            										<tr>
                                  <th>Actions</th>
            										  <th>Education Level</th>
            										  <th>Institute</th>
            										  <th>From</th>
            										  <th>To</th>
            										  <th>Percentage</th>
            										</tr>
                                <?php
                                if(mysqli_num_rows($empQualificationsQry) > 0){
                                  while($empQualificationsData = mysqli_fetch_assoc($empQualificationsQry)){
                                    echo "<tr>
                                      <td>
                                        <a href='addEducation.php?id=".$empQualificationsData['qualifications_id']."'><i class='fa fa-pencil'></i></a>
                                        <a href='educationInfo.php?id=".$empQualificationsData['qualifications_id']."'><i class='fa fa-trash'></i></a>
                                      </td>
                										  <td>".$empQualificationsData['course_name']."</td>
                										  <td>".$empQualificationsData['Institution']."</td>
                										  <td>".$empQualificationsData['From_year']."</td>
                										  <td>".$empQualificationsData['To_Year']."</td>
                                      <td>".$empQualificationsData['percentage_obtained']."</td>
                										</tr>";
                                  }
                                } else {
                                  echo "<tr><td colspan='6'>No data found </td></tr>";
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
