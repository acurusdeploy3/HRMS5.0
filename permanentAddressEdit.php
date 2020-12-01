<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = '';

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $paddressLine1 = (isset($_POST['paddressLine1']) && $_POST['paddressLine1'] != '')?mysqli_real_escape_string($db,$_POST['paddressLine1']):'';
  $pcity = (isset($_POST['pcity']) && $_POST['pcity'] != '')?mysqli_real_escape_string($db,$_POST['pcity']):@$personDetailsData['Permanent_City'];
  $pcountry = (isset($_POST['pcountry']) && $_POST['pcountry'] != '')?mysqli_real_escape_string($db,$_POST['pcountry']):@$personDetailsData['Permanent_Country'];
  $paddressLine2 = (isset($_POST['paddressLine2']) && $_POST['paddressLine2'] != '')?mysqli_real_escape_string($db,$_POST['paddressLine2']):'';
 $paddressLine3 = (isset($_POST['paddressLine3']) && $_POST['paddressLine3'] != '')?mysqli_real_escape_string($db,$_POST['paddressLine3']):'';
  $pstate = (isset($_POST['pstate']) && $_POST['pstate'] != '')?mysqli_real_escape_string($db,$_POST['pstate']):@$personDetailsData['Permanent_State'];
  $pzip = (isset($_POST['pzip']) && $_POST['pzip'] != '')?$_POST['pzip']:@$personDetailsData['Permanent_Address_Zip'];
$pstreet = (isset($_POST['pstreet']) && $_POST['pstreet'] != '')?mysqli_real_escape_string($db,$_POST['pstreet']):@$personDetailsData['pstreet'];

  mysqli_query($db,"update employee_details set Permanent_Address_Line_1 = '$paddressLine1',Permanent_Address_Line_3='$paddressLine3',permanent_street='$pstreet', Permanent_City = '$pcity', Permanent_Country = '$pcountry', Permanent_Address_Line_2 = '$paddressLine2', Permanent_State = '$pstate', Permanent_Address_Zip = '$pzip' where employee_id = $empId");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($empId, 'employee_details','employee_id');
    $msg = "Updated Successfully";
	$name=@$personDetailsData['First_Name'];
	mysqli_query($db,"insert into fyi_transaction (employee_id,employee_name,transaction,module_name,
message,date_of_message) values ($empId,'$name','Employee Permanent Address','Employee Info','Employee Information has been Modified.',curdate())");
  }
  header("Location: contactInfo.php");
} else {
  $paddressLine1 = @$personDetailsData['Permanent_Address_Line_1'];
  $pcity = @$personDetailsData['Permanent_City'];
  $pcountry = @$personDetailsData['Permanent_Country'];
  $paddressLine2 = @$personDetailsData['Permanent_Address_Line_2'];
$paddressLine3 = @$personDetailsData['Permanent_Address_Line_3'];
  $pstreet = @$personDetailsData['Permanent_Street'];
  $pstate = @$personDetailsData['Permanent_State'];
  $pzip = @$personDetailsData['Permanent_Address_Zip'];
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
                        <li class="active"><a href="contactInfo.php" data-toggle="tab" aria-expanded="false">Contact</a></li>
                        <!-- <li class=""><a href="#Acurus_History" data-toggle="tab" aria-expanded="false">History in Acurus</a></li> -->
            						<li class=""><a href="workHistoryInfo.php" data-toggle="tab" aria-expanded="false">Work History</a></li>
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
                          <form role="form" id="permanentAddressEdit" action="permanentAddressEdit.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Building / Apartment Name</label>
                                  <input type="text" tabindex="1" class="form-control" id="paddressLine1" name="paddressLine1" value="<?php echo $paddressLine1; ?>" />
                                </div>
                                  <div class="form-group">
                                  <label for="exampleInputEmail1">Old No.</label>
                                  <input type="text" tabindex="3" class="form-control" id="paddressLine3" name="paddressLine3" value="<?php echo $paddressLine3; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">City<span class="astrick">*</span></label>
                                  <input type="text" tabindex="5" class="form-control" id="pcity" name="pcity" value="<?php echo $pcity; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country<span class="astrick">*</span></label>
                                  <input type="text" tabindex="7" class="form-control" id="pcountry" name="pcountry" value="<?php echo $pcountry; ?>" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">New No.</label>
                                  <input type="text" tabindex="2" class="form-control" id="paddressLine2" name="paddressLine2"value="<?php echo $paddressLine2; ?>"  />
                                </div>
                                  <div class="form-group">
                                  <label for="exampleInputEmail1">Street</label>
                                  <input type="text" tabindex="4" class="form-control" id="pstreet" name="pstreet" value="<?php echo $pstreet; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">State<span class="astrick">*</span></label>
                                  <input type="text" tabindex="6" class="form-control" id="pstate" name="pstate" value="<?php echo $pstate; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Zip Code<span class="astrick">*</span></label>
                                  <input type="text" tabindex="8" class="form-control" id="pzip" name="pzip" maxlength="6" pattern="^\d{6}$" value="<?php echo $pzip; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="contactInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
