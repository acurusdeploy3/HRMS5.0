<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $course = $offeredBy = $courseLevel = $certificationName = '';
$id = 0;

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from employee_certifications where employee_id = $empId and certifications_id = $id and is_Active = 'Y'");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $course = @$eduHistoryData['course_name'];
  $offeredBy = @$eduHistoryData['course_offered_by'];
  $courseLevel = '--';
  $certificationName = @$eduHistoryData['certification_name'];
  $description = @$eduHistoryData['description'];
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $course = (isset($_POST['course']) && $_POST['course'] != '')?mysqli_real_escape_string($db,$_POST['course']):$course;
  $offeredBy = (isset($_POST['offeredBy']) && $_POST['offeredBy'] != '')?mysqli_real_escape_string($db,$_POST['offeredBy']):'';
  $courseLevel = (isset($_POST['courseLevel']) && $_POST['courseLevel'] != '')?mysqli_real_escape_string($db,$_POST['courseLevel']):'';
   $description = (isset($_POST['description']) && $_POST['description'] != '')?mysqli_real_escape_string($db,$_POST['description']):'';
  $certificationName = (isset($_POST['certificationName']) && $_POST['certificationName'] != '')?mysqli_real_escape_string($db,$_POST['certificationName']):'';
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update employee_certifications set course_name = '$course', course_offered_by = '$offeredBy', certification_name = '$certificationName',description='$description', modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and certifications_id = $id");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'employee_certifications','certifications_id');
      $msg = 'Updated Successfully';
    }
  }else{
    mysqli_query($db, "INSERT INTO employee_certifications(employee_id, course_name, course_offered_by, certification_name, created_by,description, created_date_and_time) VALUES('$empId', '$course','$offeredBy','$certificationName',$empId,'$description','$currentDate')");
    if(mysqli_affected_rows($db)){
      $msg = 'Added Successfully';
    }
  }
  header("Location: certificationsInfo.php");
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Self Service
        <small>My Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Self Service</a></li>
        <li class="active">My Details</li>
      </ol>
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
            						<li class="active"><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
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
                          <!-- form start -->
                          <form role="form" id="addCertifications" action="addCertifications.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Course Name<span class="astrick">*</span></label>
                                  <input type="text" tabindex="1" class="form-control" id="course" name="course" value="<?php echo $course; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Offered By</label>
                                  <input type="text" tabindex="3" class="form-control" id="offeredBy" name="offeredBy" value="<?php echo $offeredBy; ?>" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Course Description</label>
                                  <input type="text" tabindex="2" class="form-control" id="description" name="description" value="<?php echo $description; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Certification Name</label>
                                  <input type="text" tabindex="4" class="form-control" id="certificationName" name="certificationName" value="<?php echo $certificationName; ?>" />
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="certificationsInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
                            </div>
                            <!-- /.box-body -->
                          </form>
                          <!-- </form> -->
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
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
