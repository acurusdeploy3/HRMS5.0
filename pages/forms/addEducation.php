<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $education = $from = $to = $institute = $percentage = '';
$id = 0;

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from employee_qualifications where employee_id = $empId and qualifications_id = $id");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $education = @$eduHistoryData['course_name'];
  $institute = @$eduHistoryData['Institution'];
  $from = @$eduHistoryData['From_year'];
  $to = @$eduHistoryData['To_Year'];
  $percentage = @$eduHistoryData['percentage_obtained'];
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $education = (isset($_POST['education']) && $_POST['education'] != '')?$_POST['education']:$education;
  $institute = (isset($_POST['institute']) && $_POST['institute'] != '')?$_POST['institute']:$institute;
  $from = (isset($_POST['from']) && $_POST['from'] != '')?$_POST['from']:$from;
  $to = (isset($_POST['to']) && $_POST['to'] != '')?$_POST['to']:$to;
  $percentage = (isset($_POST['percentage']) && $_POST['percentage'] != '')?$_POST['percentage']:$percentage;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update employee_qualifications set course_name = '$education', Institution = '$institute', From_year = '$from', To_Year = '$to', percentage_obtained = '$percentage'  where employee_id = $empId and qualifications_id = $id");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'employee_qualifications','qualifications_id');
      $msg = 'Updated Successfully';
    }
  }else{
    mysqli_query($db, "INSERT INTO employee_qualifications(employee_id, course_name, Institution, From_year, To_Year, percentage_obtained) VALUES('$empId', '$education','$institute','$from','$to','$percentage')");
    if(mysqli_affected_rows($db)){
      $msg = 'Added Successfully';
    }
  }
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
            						<li class="active"><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
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
                          <!-- form start -->
                          <form role="form" id="addEducation" action="addEducation.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group add-item-class">
                                  <label for="exampleInputEmail1">Education Level<span class="astrick">*</span></label>
                                  <select class="form-control" id="education" name="education">
                                    <option value="">Select Education Level</option>
                                    <?php
                                    while($row = mysqli_fetch_assoc($qualificationsQuery)){
                                      echo "<option value='".$row['qualification_desc']."'";
                                      if($row['qualification_desc'] == $education){
                                        echo 'selected';
                                      }
                                      echo ">".$row['qualification_desc']."</option>";
                                    }
                                    ?>
                                  </select>
                                  <a data-toggle="modal" data-target="#addNewFieldModal"><i class="fa fa-plus-square"></i></a>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">From<span class="astrick">*</span></label>
                                  <select class="form-control" id="from" name="from">
                                    <option value="">Select From Year</option>
                                    <?php
                                    for($i=0;$i<=$limit;$i++){
                                      $start_year = $startYear+$i;
                                      echo "<option value='".$start_year."'";
                                      if($start_year == $from){
                                        echo 'selected';
                                      }
                                      echo ">".$start_year."</option>";
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Percentage<span class="astrick">*</span></label>
                                  <input type="text" class="form-control" id="percentage" name="percentage" value="<?php echo $percentage; ?>" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Institute<span class="astrick">*</span></label>
                                  <input type="text" class="form-control" id="institute" name="institute" value="<?php echo $institute; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">To<span class="astrick">*</span></label>
                                  <select class="form-control" id="to" name="to">
                                    <option value="">Select To Year</option>
                                    <?php
                                    for($i=0;$i<=$limit;$i++){
                                      $start_year = $startYear+$i;
                                      echo "<option value='".$start_year."'";
                                      if($start_year == $to){
                                        echo 'selected';
                                      }
                                      echo ">".$start_year."</option>";
                                    }
                                    ?>
                                  </select>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="educationInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
      <!-- Modal -->
      <div class="modal fade" id="addNewFieldModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add Education Level</h4>
            </div>
            <form>
            <div class="modal-body">
              <div class="form-group">
                <p class="text-red" id="uploadMsg"></p>
                <label for="exampleInputFile">Designation<span class="astrick">*</span></label>
                <input type="text" id="newField" name="newField" />
                <input type="hidden" id="tableColumn" name="tableColumn" value="qualification_desc" />
                <input type="hidden" id="tableName" name="tableName" value="all_qualifications" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="addNewField">Save</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
