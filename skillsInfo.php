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

  mysqli_query($db,"update employee_skills set is_active = 'N' where employee_id = $empId and skill_id = $id");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($id, 'employee_skills','skill_id');
    $empSkillsQry = mysqli_query($db,"select * from employee_skills where employee_id = $empId and is_active = 'Y'");
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
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
									<li class=""><a href="familyInfo.php" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                        <li class=""><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
            						<li class=""><a href="documentsInfo.php" data-toggle="tab" aria-expanded="false">Documents</a></li>
            						<li class="active"><a href="skillsInfo.php" data-toggle="tab" aria-expanded="false">Skills</a></li>
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
          									<a href="addSkills.php"><button type="button" class="btn btn-primary">Add+</button></a>
          								</div>
          								<table class="table table-striped">
          									<tbody>
          										<tr>
                                <th>Actions</th>
          										  <th>Skill</th>
          										  <th>Months Used</th>
          										  <th>Competency Level</th>
          										  <th>Last Used</th>
          										</tr>
          										<?php
                              if(mysqli_num_rows($empSkillsQry) > 0){
                                while($empSkillsData = mysqli_fetch_assoc($empSkillsQry)){
                                  echo "<tr>
                                    <td>
                                      <a href='addSkills.php?id=".$empSkillsData['skill_id']."'><i class='fa fa-pencil'></i></a>
                                      <a href='skillsInfo.php?id=".$empSkillsData['skill_id']."'><i class='fa fa-trash'></i></a>
                                    </td>
              										  <td>".$empSkillsData['skill_desc']."</td>
              										  <td>".$empSkillsData['months_of_experience']."</td>
              										  <td>".$empSkillsData['competency_level']."</td>
              										  <td>".$empSkillsData['year_last_used']."</td>
              										</tr>";
                                }
                              } else {
                                echo "<tr><td colspan='4'>No data found </td></tr>";
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
