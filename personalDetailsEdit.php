<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = '';

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $personalMail = (isset($_POST['personalMail']) && $_POST['personalMail'] != '')?$_POST['personalMail']:@$personDetailsData['Employee_Personal_Email'];
  $dob = (isset($_POST['dob']) && $_POST['dob'] != '')?$_POST['dob']:$dob;
  $mobile = (isset($_POST['mobile']) && $_POST['mobile'] != '')?$_POST['mobile']:@$personDetailsData['Primary_Mobile_Number'];
  $gender = (isset($_POST['gender']) && $_POST['gender'] != '')?$_POST['gender']:@$personDetailsData['Gender'];
  $maritalStatus = (isset($_POST['maritalStatus']) && $_POST['maritalStatus'] != '')?$_POST['maritalStatus']:@$personDetailsData['Marital_Status'];
  $bloodGroup = (isset($_POST['bloodGroup']) && $_POST['bloodGroup'] != '')?$_POST['bloodGroup']:@$personDetailsData['Employee_Blood_Group'];
  $countryCode = (isset($_POST['countryCode']) && $_POST['countryCode'] != '')?$_POST['countryCode']:@$personDetailsData['country_code'];
	$uanNumber = (isset($_POST['uanNumber']) && $_POST['uanNumber'] != '')?$_POST['uanNumber']:@$personDetailsData['UAN_Number'];
  $pfNumber = (isset($_POST['pfNumber']) && $_POST['pfNumber'] != '')?mysqli_real_escape_string($db,$_POST['pfNumber']):@$personDetailsData['PF_Number'];
  $esicNumber = (isset($_POST['esicNumber']) && $_POST['esicNumber'] != '')?$_POST['esicNumber']:@$personDetailsData['ESIC_Number'];
$InsuranceNum = (isset($_POST['InsuranceNum']) && $_POST['InsuranceNum'] != '')?$_POST['InsuranceNum']:@$personDetailsData['InsuranceNum'];
$InsurancExp = (isset($_POST['InsuranceExp']) && $_POST['InsuranceExp'] != '')?$_POST['InsuranceExp']:@$personDetailsData['InsuranceExp'];  

  $dob = date("Y-m-d",strtotime($dob));
if($InsurancExp=='')
{
$InsurancExp='0001-01-01';
}
else
{
  $InsurancExp = date("Y-m-d",strtotime($InsurancExp));
}
  
  
 mysqli_query($db,"update employee_details set Employee_Personal_Email = '$personalMail', Date_of_Birth = '$dob', Primary_Mobile_Number = '$mobile',country_code='$countryCode', Gender = '$gender', Marital_Status = '$maritalStatus', Employee_Blood_Group = '$bloodGroup', PF_Number = '$pfNumber', UAN_Number = '$uanNumber', ESIC_Number = '$esicNumber',insurance_num='$InsuranceNum',insurance_Expiry='$InsurancExp' where employee_id = $empId");

  if(mysqli_affected_rows($db)){
    storeDataInHistory($empId, 'employee_details','employee_id');
    $msg = "Updated Successfully";
	$name=@$personDetailsData['First_Name'];
	mysqli_query($db,"insert into fyi_transaction (employee_id,employee_name,transaction,module_name,
message,date_of_message) values ($empId,'$name','Employee Details Modification','Employee Info','Employee Information has been Modified.',curdate())");

  }
  header("Location: personalInfo.php");
}else{
  $personalMail = @$personDetailsData['Employee_Personal_Email'];
  $dob = @$dob;
  $mobile = @$personDetailsData['Primary_Mobile_Number'];
  $gender = @$personDetailsData['Gender'];
  $maritalStatus = @$personDetailsData['Marital_Status'];
  $bloodGroup = @$personDetailsData['Employee_Blood_Group'];
  $countryCode = @$personDetailsData['country_code'];
  $uanNumber = @$personDetailsData['UAN_Number'];
  $pfNumber = @$personDetailsData['PF_Number'];
  $esicNumber = @$personDetailsData['ESIC_Number'];
  $InsuranceNum = @$personDetailsData['Insurance_num'];
  $InsurancExp = @$personDetailsData['insurance_expiry'];
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
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2">
                    <img src="<?php echo $RELATIVE_PATH.@$personDetailsData['Employee_image']; ?>" class="img-responsive" alt="User Image" id="profileImage" />
                    <a style="margin-left: 30px;" data-toggle="modal" data-target="#uploadModal">Change Photo</a>
                  </div>
                  <div class="col-md-10">
                    <div id="personalDetailsDiv">
                      <p><b>Employee Name </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['First_Name']." ".@$personDetailsData['MI']." ".@$personDetailsData['Last_Name']; ?></p>
                      <p><b>Employee Id </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['employee_id']; ?></p>
                      <p><b>Email Id </b>&nbsp;:&nbsp;<?php echo @$personDetailsData['Official_Email']; ?></p>
                      <p><b>Contact Number </b>&nbsp;:&nbsp;<?php echo $countryCode.$mobile; ?></p>
                    </div>
                  </div>
                </div>
              </div>
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
                           <form role="form" id="personalDetailsEdit" action="personalDetailsEdit.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Date of Birth<span class="astrick">*</span></label>
                                  <input type="text" tabindex="1" class="form-control" id="dob" name="dob" style="background-color: white;" value="<?php echo $dob; ?>" readonly />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Personal E-mail<span class="astrick">*</span></label>
                                  <input type="email" tabindex="3" class="form-control" id="personalMail" name="personalMail" value="<?php echo $personalMail; ?>" />
                                </div>
                                <div class="form-group">
                                  <label>Marital Status<span class="astrick">*</span></label>
                                  <select tabindex="6" class="form-control" id="maritalStatus" name="maritalStatus">
                                    <option value="">Choose your Marital Status</option>
                                    <option value="Unmarried" <?php if($maritalStatus == "Unmarried") {echo 'selected';} ?>>Unmarried</option>
                                    <option value="Married" <?php if($maritalStatus == "Married") {echo 'selected';} ?>>Married</option>
                                    <option value="Prefer not to disclose" <?php if($maritalStatus == "Prefer not to disclose") {echo 'selected';} ?>>Prefer not to disclose</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">PF Number</label>
                                  <input type="text" tabindex="8" class="form-control" id="pfNumber" name="pfNumber" value="<?php echo $pfNumber; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Insurance Number</label>
                                  <input type="text" tabindex="10" class="form-control" id="InsuranceNum" name="InsuranceNum" value="<?php echo $InsuranceNum; ?>" />
                                </div>
								
								 <div class="form-group">
                                  <label for="exampleInputEmail1">ESIC Number</label>
                                  <input type="text" tabindex="10" class="form-control" id="esicNumber" name="esicNumber" value="<?php echo $esicNumber; ?>" />
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Gender<span class="astrick">*</span></label>
                                  <input type="text" tabindex="2" class="form-control" id="gender" name="gender" readonly value="<?php echo $gender; ?>" />
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Primary Mobile<span class="astrick">*</span></label>
                                  <div class="row">
                                    <div class="col-md-4">
                                      <select tabindex="4" class="form-control" id="countryCode" name="countryCode">
                                        <option value="+91" <?php if($countryCode == "+91"){ echo "selected"; } ?>>+91</option>
                                        <option value="+1" <?php if($countryCode == "+1"){ echo "selected"; } ?>>+1</option>
                                      </select>
                                    </div>
                                    <div class="col-md-8">
                                      <input type="text" tabindex="5" class="form-control" id="mobile" name="mobile" maxlength="10" pattern="^\d{10}$" value="<?php echo $mobile; ?>"/>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group add-item-class">
                                  <label>Blood Group<span class="astrick">*</span></label>
                                  <select tabindex="7" class="form-control" id="bloodGroup" name="bloodGroup">
                                    <option value="">Select Blood Group</option>
                                    <?php
                                    while($row = mysqli_fetch_assoc($blood_groups_qry)){
                                      echo '<option value="'.$row['blood_group'].'"';
                                      if($bloodGroup == $row['blood_group']){
                                        echo 'selected';
                                      }
                                      echo '>'.$row['blood_group'].'</option>';
                                    }
                                    ?>
                                  </select>
                                  <a data-toggle="modal" data-target="#addNewFieldModal"><i class="fa fa-fw fa-plus"></i></a>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">UAN Number</label>
                                  <input type="text" tabindex="9" class="form-control" id="uanNumber" name="uanNumber" value="<?php echo $uanNumber; ?>" />
								  
                                </div>
								
								<div class="form-group">
                                  <label for="exampleInputEmail1">Insurance Expiry</label>
                                  <input type="text" tabindex="10" class="form-control" id="InsuranceExp" name="InsuranceExp" value="<?php if($InsurancExp=='0001-01-01') { echo ''; }  else { echo $InsurancExp; }  ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="personalInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
      <div class="modal fade" id="uploadModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <form>
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Photo</h4>
              </div>
              <div class="modal-body">
                <p class="text-red" id="uploadMsg"></p>
                <div class="form-group">
                  <label for="exampleInputFile">New Photo</label>
                  <input type="file" id="newPhoto" name="newPhoto" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="uploadNewPhoto">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="addNewFieldModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add Blood Group</h4>
            </div>
            <form>
            <div class="modal-body">
              <div class="form-group">
                <p class="text-red" id="uploadMsg"></p>
                <label for="exampleInputFile">Blood Group<span class="astrick">*</span></label>
                <input type="text" id="newField" name="newField" />
                <input type="hidden" id="tableColumn" name="tableColumn" value="blood_group" />
                <input type="hidden" id="tableName" name="tableName" value="all_blood_groups" />
                <input type="hidden" id="replaceField" name="replaceField" value="bloodGroup" />
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
         $('#dob').datepicker({
        autoclose: true
      });
	   $('#InsuranceExp').datepicker({
        autoclose: true
      });
});
</script>