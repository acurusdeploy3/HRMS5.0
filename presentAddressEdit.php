<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = '';

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $addressLine1 = (isset($_POST['addressLine1']) && $_POST['addressLine1'] != '')?mysqli_real_escape_string($db,$_POST['addressLine1']):'';
  $city = (isset($_POST['city']) && $_POST['city'] != '')?mysqli_real_escape_string($db,$_POST['city']):@$personDetailsData['Present_City'];
  $country = (isset($_POST['country']) && $_POST['country'] != '')?mysqli_real_escape_string($db,$_POST['country']):@$personDetailsData['Present_Country'];
  $addressLine2 = (isset($_POST['addressLine2']) && $_POST['addressLine2'] != '')?mysqli_real_escape_string($db,$_POST['addressLine2']):'';
$addressLine3 = (isset($_POST['addressLine3']) && $_POST['addressLine3'] != '')?mysqli_real_escape_string($db,$_POST['addressLine3']):'';
  $state = (isset($_POST['state']) && $_POST['state'] != '')?mysqli_real_escape_string($db,$_POST['state']):@$personDetailsData['Present_State'];
 $street = (isset($_POST['street']) && $_POST['street'] != '')?mysqli_real_escape_string($db,$_POST['street']):@$personDetailsData['street'];
  $zip = (isset($_POST['zip']) && $_POST['zip'] != '')?$_POST['zip']:@$personDetailsData['Present_Address_ZipCode'];

  mysqli_query($db,"update employee_details set Present_Address_Line_1 = '$addressLine1',present_address_line_3='$addressLine3',present_street='$street', Present_City = '$city', Present_Country = '$country', Present_Address_Line_2 = '$addressLine2', Present_State = '$state', Present_Address_ZipCode = '$zip' where employee_id = $empId");

  if(mysqli_affected_rows($db)){
    storeDataInHistory($empId, 'employee_details','employee_id');
    $msg = "Updated Successfully";
	$name=@$personDetailsData['First_Name'];
	mysqli_query($db,"insert into fyi_transaction (employee_id,employee_name,transaction,module_name,
message,date_of_message) values ($empId,'$name','Employee Present Address','Employee Info','Employee Information has been Modified.',curdate())");
  }
  header("Location: contactInfo.php");
} else {
  $addressLine1 = $personDetailsData['Present_Address_Line_1'];
  $city = $personDetailsData['Present_City'];
  $country = $personDetailsData['Present_Country'];
  $addressLine2 = $personDetailsData['Present_Address_Line_2'];
$addressLine3 = $personDetailsData['Present_Address_Line_3'];
  $state = $personDetailsData['Present_State'];
 $street = $personDetailsData['Present_Street'];
  $zip = $personDetailsData['Present_Address_ZipCode'];
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
                         <form role="form" id="presentAddressEdit" action="presentAddressEdit.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Building /  Apartment Name</label>
                                  <input type="text" tabindex="1" class="form-control" id="addressLine1" name="addressLine1" value="<?php echo $addressLine1; ?>" />
                                </div>
                                   <div class="form-group">
                                  <label for="exampleInputEmail1">Old No.</label>
                                  <input type="text" tabindex="3" class="form-control" id="addressLine3" name="addressLine3" value="<?php echo $addressLine3; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">City<span class="astrick">*</span></label>
                                  <input type="text" tabindex="5" class="form-control" id="city" name="city" value="<?php echo $city; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country<span class="astrick">*</span></label>
                                  <input type="text" tabindex="7" class="form-control" id="country" name="country" value="<?php echo $country; ?>" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">New No.</label>
                                  <input type="text" tabindex="2" class="form-control" id="addressLine2" name="addressLine2" value="<?php echo $addressLine2; ?>" />
                                </div>
                                    <div class="form-group">
                                  <label for="exampleInputEmail1">Street</label>
                                  <input type="text" tabindex="4" class="form-control" id="street" name="street" value="<?php echo $street; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">State<span class="astrick">*</span></label>
                                  <input type="text" tabindex="6" class="form-control" id="state" name="state" value="<?php echo $state; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Zip Code<span class="astrick">*</span></label>
                                  <input type="text" tabindex="8" class="form-control" id="zip" name="zip" maxlength="6" pattern="^\d{6}$" value="<?php echo $zip; ?>" />
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
