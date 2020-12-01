<?php
require_once("config.php");
session_start();
$backpage = $_SESSION['searchBack'];

if($backpage=='PMS')
{
	$page = 'pages/PerformanceReview/';
}
if($backpage=='ConfirmServices')
{
	$page = 'pages/OnBoarding/ConfirmServices.php';
}
if($backpage=='SearchEmployee.php')
{
	$page = 'pages/tables/SearchEmployee.php';
}
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');
require_once('searchEmployeeCommon.php');

$msg = $docType = $validFrom = $expiry = $docNum = $validTo = $docId = '';
$id = 0;
$docTypeArray = array();

if(isset($_GET['id']) && $_GET['id'] != '' && isset($viewEmpId)){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from kye_details where employee_id = $viewEmpId and kye_id = $id and is_Active = 'Y'");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $docType = @$eduHistoryData['document_type'];
  $validFrom = @$eduHistoryData['valid_from'];
  $expiry = @$eduHistoryData['has_expiry'];
  $docNum = @$eduHistoryData['document_number'];
  $validTo = @$eduHistoryData['valid_to'];
  $docId = @$eduHistoryData['doc_id'];

  $docTypeArray = explode(" ",$docType);
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $docType = (isset($_POST['docType']) && $_POST['docType'] != '')?$_POST['docType']:$docType;
  $validFrom = (isset($_POST['validFrom']) && $_POST['validFrom'] != '')?$_POST['validFrom']:$validFrom;
  $expiry = (isset($_POST['expiry']) && $_POST['expiry'] != '')?$_POST['expiry']:$expiry;
  $docNum = (isset($_POST['docNum']) && $_POST['docNum'] != '')?$_POST['docNum']:$docNum;
  $validTo = (isset($_POST['validTo']) && $_POST['validTo'] != '')?$_POST['validTo']:$validTo;
  $docId = (isset($_POST['docId']) && $_POST['docId'] != '')?$_POST['docId']:$docId;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update kye_details set document_type = '$docType', valid_from = '$validFrom', has_expiry = '$expiry', document_number = '$docNum', valid_to = '$validTo', doc_id= $docId, modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $viewEmpId and kye_id = $id");
	$affectedRows = mysqli_affected_rows($db);
    if($affectedRows){
      storeDataInHistory($id, 'kye_details','kye_id');
      $msg = 'Updated Successfully';
    }
  }else{
    if(strtolower($expiry) == 'no'){
      $query = "INSERT INTO kye_details(employee_id, document_type, has_expiry, document_number, doc_id, created_by, created_date_and_time,is_Active) VALUES('$viewEmpId', '$docType','$expiry','$docNum','$docId',$empId,'$currentDate','Y')";
    }else{
      $query = "INSERT INTO kye_details(employee_id, document_type, valid_from, has_expiry, document_number, valid_to, doc_id, created_by, created_date_and_time,is_Active) VALUES('$viewEmpId', '$docType','$validFrom','$expiry','$docNum','$validTo','$docId',$empId,'$currentDate','Y')";
    }
	mysqli_query($db, $query);
	$affectedRows = mysqli_affected_rows($db);
	$msg = 'Added Successfully';

  }
  if($affectedRows){
      mysqli_query($db,"update employee_documents_tracker set employee_id = $viewEmpId, is_active = 'Y', Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
      mysqli_query($db,"update employee_documents set is_Active = 'Y',document_name = replace(document_name,'$empId','$viewEmpId'), Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
      storeDataInHistory($docId, 'employee_documents_tracker','doc_id');
      storeDataInHistory($docId, 'employee_documents','doc_id');
      $msg = 'Added Successfully';
    }
	header("Location: searchKYEInfo.php?empId=$viewEmpId");
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <h1>
        Personal Info
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
 		 <li><a href="#">Search Employee</a></li>
        <li><a href="#">Employee Info</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
			
			<button onclick="window.location='<?php echo $page ?>'" type="button" class="btn btn-block btn-primary btn-flat">Back</button>
           <br>
			Employee Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="border-class">
              <!-- form start -->
              <?php require_once('searchEmployeeInfo.php'); ?>
            </div>

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  <div class="col-md-2" id="tab-menu">
                    <ul class="nav nav-tabs tabs-left">
                      <li class=""><a href="searchMydetails.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="true">Official</a></li>
                      <li class=""><a href="searchPersonalInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Personal</a></li>
                      <li class=""><a href="searchContactInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Contact</a></li>
                      <li class=""><a href="searchWorkHistoryInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Work History</a></li>
                      <li class=""><a href="searchEducationInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Education</a></li>
					   <li class=""><a href="searchFamilyInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Family Particulars</a></li>
                      <li class=""><a href="searchCertificationsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Certifications</a></li>
                      <li class="active"><a href="searchKYEInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
                      <li class=""><a href="searchDocumentsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Documents</a></li>
                      <li class=""><a href="searchSkillsInfo.php?empId=<?php echo $viewEmpId; ?>" data-toggle="tab" aria-expanded="false">Skills</a></li>
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
                          <form role="form" id="addKYE" action="searchAddKYE.php?empId=<?php echo $viewEmpId; ?>" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group add-item-class">
                                  <label for="exampleInputEmail1">KYE Document<span class="astrick">*</span></label>
                                  <?php if(!$id){ ?>
                                  <select class="form-control" id="docType" name="docType">
                                    <option value="">Select Document Type</option>
                                    <?php
                                    foreach($docTypesData as $val){
                                      echo "<option value='".$val."'";
                                      if($docType == $val){
                                        echo 'selected';
                                      }
                                      echo ">".$val."</option>";
                                    }
                                    ?>
                                  </select>
                                  <a data-toggle="modal" data-target="#addNewFieldModal"><i class="fa fa-fw fa-plus"></i></a>
                                <?php }else { ?>
                                  <input type="text" class="form-control" name="docType" readonly value="<?php echo $docType; ?>" />
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Expiry</label>
                                  <select class="form-control" name="expiry" id="expiry">
                                    <option value="No"  <?php if($expiry == 'No') {echo "selected";} ?>>No</option>
                                    <option value="Yes" <?php if($expiry == 'Yes') {echo "selected";} ?>>Yes</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Valid From</label>
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control" id="validFrom" readonly name="validFrom" value="<?php echo $validFrom; ?>" />
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Document Number</label>
                                  <input type="text" class="form-control" id="docNum" name="docNum" value="<?php echo $docNum; ?>" />
                                </div>
                                <div class="col-md-11" style="padding-left:0px;">
                                  <div class="form-group" id="docIdDiv">
                                    <label for="exampleInputEmail1">Doc Id<span class="astrick">*</span></label>
                                    <?php if(!$id){ ?>
                                      <input type="text" class="form-control" name="docId" readonly value="<?php echo $docId; ?>" />
                                    <?php }else{ ?>
                                      <input type="text" class="form-control" id="<?php echo $docTypeArray[0].'_doc_id'; ?>" name="docId" readonly value="<?php echo $docId; ?>" />
                                    <?php } ?>
                                  </div>
                                </div>
                                <div class="col-md-1 btn_div">
                                <?php if(!$id){ ?>
                                  <a href="#myModal" class="btn_anchor" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                <?php }else{ ?>
                                  <a href="#myModal" class="btn_anchor" id="<?php echo $docTypeArray[0]; ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Valid To</label>
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control" id="validTo" name="validTo" readonly value="<?php echo $validTo; ?>" />
                                  </div>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="searchKYEInfo.php?empId=<?php echo $viewEmpId; ?>"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
              <h4 class="modal-title">Add Document Type</h4>
            </div>
            <form>
            <div class="modal-body">
              <div class="form-group">
                <p class="text-red" id="uploadMsg"></p>
                <label for="exampleInputFile">Document Type<span class="astrick">*</span></label>
                <input type="text" id="newField" name="newField" />
                <input type="hidden" id="tableColumn" name="tableColumn" value="document_type" />
                <input type="hidden" id="tableName" name="tableName" value="all_document_types" />
                <input type="hidden" id="replaceField" name="replaceField" value="docType" />
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
require_once('layouts/documentModals.php');
require_once('layouts/main-footer.php');
require_once('layouts/control-sidebar.php');
require_once('layouts/bottom-footer.php');
?>
<!-- Modal -->

<script>
$(function(){
  <?php
  if(!$id){
  ?>
  var addKYEName = $("form#addKYE select[name='docType'] option:eq(1)").val();
	var addKYENameArray = addKYEName.split(" ");
	$("form#addKYE div.btn_div a[href='#myModal']").attr("id",addKYENameArray[0]);
	$("form#addKYE input[name='docId']").attr("id",addKYENameArray[0]+"_doc_id");
  $("form#addKYE select[name='docType'] option:eq(1)").attr("selected","selected");
  <?php } ?>

  $("#expiry").change(function(){
    var val = $("#expiry").val();
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    if(val == "No"){
      $("#validFrom").prop("readonly",true);
      $("#validTo").prop("readonly",true);
    } else {
      $("#validFrom").prop("readonly",false);
      $("#validTo").prop("readonly",false);
      $('#validFrom').datepicker({
        format: 'yyyy-mm-dd',
        setDate: today,
        todayHighlight: true,
        endDate: today,
        autoclose: true
      });

      $('#validTo').datepicker({
        format: 'yyyy-mm-dd',
        setDate: today,
        todayHighlight: true,
        startDate: today,
        autoclose: true
      });
    }
  });
});
</script>
