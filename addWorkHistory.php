<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $company = $from = $to = $designation = '';
$id = 0;

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $workHistory = mysqli_query($db,"select * from employee_work_history where employee_id = $empId and work_id = $id and is_active = 'Y'");
  $workHistoryData = mysqli_fetch_assoc($workHistory);

  $company = @$workHistoryData['company_name'];
  $designation = @$workHistoryData['designation'];
  $from = @$workHistoryData['worked_from'];
  $to = @$workHistoryData['worked_upto'];
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $company = (isset($_POST['company']) && $_POST['company'] != '')?mysqli_real_escape_string($db,$_POST['company']):$company;
  $designation = (isset($_POST['designation']) && $_POST['designation'] != '')?mysqli_real_escape_string($db,$_POST['designation']):$designation;
  $from = (isset($_POST['from']) && $_POST['from'] != '')?$_POST['from']:$from;
  $to = (isset($_POST['to']) && $_POST['to'] != '')?$_POST['to']:$to;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update employee_work_history set company_name = '$company', designation = '$designation', worked_from = '$from', worked_upto = '$to', modified_by=$empId, modified_date_and_time='$currentDate', modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and work_id = $id");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'employee_work_history','work_id');
      $msg = 'Updated Successfully';
    }
  }else{
    mysqli_query($db, "INSERT INTO employee_work_history(employee_id, company_name, designation, worked_from, worked_upto, created_by, created_date_and_time) VALUES('$empId', '$company','$designation','$from','$to','$empId','$currentDate')");
    if(mysqli_affected_rows($db)){
      $msg = 'Added Successfully';
    }
  }
  header("Location: workHistoryInfo.php");
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
            						<li class="active"><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
            						<li class=""><a href="educationInfo.php" data-toggle="tab" aria-expanded="false">Education</a></li>
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
                          <!-- form start -->
                          <form role="form" id="addWorkHistory" action="addWorkHistory.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Company Name<span class="astrick">*</span></label>
                                  <input type="text" tabindex="1" class="form-control" id="company" name="company" value="<?php echo $company; ?>"/>
                                </div>
                                <div class="form-group date-field">
                                  <label for="exampleInputEmail1">From<span class="astrick">*</span></label>
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" tabindex="3" class="form-control" id="workFrom" name="from" value="<?php echo $from; ?>" />
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group add-item-class">
                                  <label for="exampleInputEmail1">Designation<span class="astrick">*</span></label>
                                  <select tabindex="2" class="form-control" id="designation" name="designation">
                                    <option value="">Select Designation</option>
                                    <?php
                                    while($row = mysqli_fetch_assoc($designationsQuery)){
                                      echo "<option value='".$row['designation_desc']."'";
                                      if($row['designation_desc'] == $designation){
                                        echo 'selected';
                                      }
                                      echo ">".$row['designation_desc']."</option>";
                                    }
                                    ?>
                                  </select>
                                  <a data-toggle="modal" data-target="#addNewFieldModal"><i class="fa fa-fw fa-plus"></i></a>
                                </div>
                                <div class="form-group date-field">
                                  <label for="exampleInputEmail1">To<span class="astrick">*</span></label>
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" tabindex="4" class="form-control" id="workTo" name="to" value="<?php echo $to; ?>" />
                                  </div>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="workHistoryInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
              <h4 class="modal-title">Add Designation</h4>
            </div>
            <form>
            <div class="modal-body">
              <div class="form-group">
                <p class="text-red" id="uploadMsg"></p>
                <label for="exampleInputFile">Designation<span class="astrick">*</span></label>
                <input type="text" id="newField" name="newField" />
                <input type="hidden" id="tableColumn" name="tableColumn" value="designation_desc" />
                <input type="hidden" id="tableName" name="tableName" value="all_designations" />
                <input type="hidden" id="replaceField" name="replaceField" value="designation" />
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
<script>
$(function(){
  $("div.date-field > i").css("top","25px");
});
</script>
