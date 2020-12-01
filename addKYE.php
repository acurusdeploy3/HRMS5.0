<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$msg = $docType = $validFrom = $expiry = $docNum = $validTo = $docId = '';
$id = 0;
$docTypeArray = array();

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from kye_details where employee_id = $empId and kye_id = $id and is_Active = 'Y'");
  $eduHistoryData = mysqli_fetch_assoc($eduHistory);

  $docType = @$eduHistoryData['document_type'];
  $validFrom = @$eduHistoryData['valid_from'];
if($validFrom == '0001-01-01')
  {
	  $validFrom='';
	  
  }
  $expiry = @$eduHistoryData['has_expiry'];
  $docNum = @$eduHistoryData['document_number'];
  $docName = @$eduHistoryData['document_name'];
  $validTo = @$eduHistoryData['valid_to'];
 if($validTo == '0001-01-01')
  {
	  $validTo='';
	  
  }
  $docId = @$eduHistoryData['doc_id'];

  $docTypeArray = explode(" ",$docType);
}

if(isset($_POST['formSubmit']) && $_POST['formSubmit'] != ''){
  $docType = (isset($_POST['docType']) && $_POST['docType'] != '')?$_POST['docType']:$docType;
  $validFrom = (isset($_POST['validFrom']) && $_POST['validFrom'] != '')?$_POST['validFrom']:'0001-01-01';
  $expiry = (isset($_POST['expiry']) && $_POST['expiry'] != '')?$_POST['expiry']:$expiry;
  $docNum = (isset($_POST['docNum']) && $_POST['docNum'] != '')?$_POST['docNum']:$docNum;
  $docName = (isset($_POST['docName']) && $_POST['docName'] != '')?$_POST['docName']:$docName;
  $validTo = (isset($_POST['validTo']) && $_POST['validTo'] != '')?$_POST['validTo']:'0001-01-01';
  $docId = (isset($_POST['docId']) && $_POST['docId'] != '')?$_POST['docId']:$docId;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update kye_details set document_type = '$docType', valid_from = '$validFrom', has_expiry = '$expiry', document_number = '$docNum',document_name = '$docName' ,valid_to = '$validTo', doc_id= $docId, modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and kye_id = $id");
	$affectedRows = mysqli_affected_rows($db);
    if($affectedRows){
      storeDataInHistory($id, 'kye_details','kye_id');
      $msg = 'Updated Successfully';
    }
  }else{
    if(strtolower($expiry) == 'no'){
      $query = "INSERT INTO kye_details(employee_id, document_type, has_expiry, document_number, doc_id, created_by, created_date_and_time,is_Active,document_name) VALUES('$empId', '$docType','$expiry','$docNum','$docId',$empId,'$currentDate','Y','$docName')";
    }else{
      $query = "INSERT INTO kye_details(employee_id, document_type, valid_from, has_expiry, document_number, valid_to, doc_id, created_by, created_date_and_time,is_Active,document_name) VALUES('$empId', '$docType','$validFrom','$expiry','$docNum','$validTo','$docId',$empId,'$currentDate','Y','$docName')";
    }
    mysqli_query($db, $query);
	$affectedRows = mysqli_affected_rows($db);
	$msg = 'Added Successfully';
   
  }
	if($affectedRows){
		mysqli_query($db,"update employee_documents_tracker set is_active = 'Y', Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
		mysqli_query($db,"update employee_documents set is_Active = 'Y', Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
		storeDataInHistory($docId, 'employee_documents_tracker','doc_id');
		storeDataInHistory($docId, 'employee_documents','doc_id');
	}
  header("Location: KYEInfo.php");
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
            						<li class=""><a href="certificationsInfo.php" data-toggle="tab" aria-expanded="false">Certifications</a></li>
									
                        <li class="active"><a href="KYEInfo.php" data-toggle="tab" aria-expanded="false">KYE Info</a></li>
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
                            <form role="form" id="addKYE" action="addKYE.php" method="POST">
                            <div class="box-body">
                              <div class="col-md-6">
                                <div class="form-group add-item-class">
                                  <label for="exampleInputEmail1">KYE Document<span class="astrick">*</span></label>
                                  <?php if(!$id){ ?>
                                  <select tabindex="1" class="form-control" id="docType" name="docType">
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
                                <?php }else { ?>
                                  <input type="text" tabindex="1" class="form-control" name="docType" readonly value="<?php echo $docType; ?>" />
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Expiry</label>
                                  <select tabindex="4" class="form-control" name="expiry" id="expiry">
                                    <option value="No"  <?php if($expiry == 'No') {echo "selected";} ?>>No</option>
                                    <option value="Yes" <?php if($expiry == 'Yes') {echo "selected";} ?>>Yes</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Valid From</label>
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" tabindex="6" class="form-control" id="validFrom" readonly name="validFrom" value="<?php echo $validFrom; ?>" />
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                              	<div class="col-md-6">
                                	<div class="form-group">
                                  		<label for="exampleInputEmail1">Document Name</label>
                                 		 <input type="text" tabindex="2" class="form-control" id="docName" name="docName" value="<?php echo $docName; ?>" />
                                	</div>
                                 </div>
                              	<div class="col-md-6">
                              		<div class="form-group">
                                  		<label for="exampleInputEmail1">Document Number</label>
                                		  <input type="text" tabindex="3" class="form-control" id="docNum" name="docNum" value="<?php echo $docNum; ?>" />
                               		 </div>
                              	 </div>
                                <div class="col-md-11" style="padding-left:0px;">
                                  <div class="form-group" id="docIdDiv">
                                    <label for="exampleInputEmail1">Doc Id<span class="astrick">*</span></label>
                                    <?php if(!$id){ ?>
                                      <input type="text" tabindex="5" class="form-control" name="docId" readonly value="<?php echo $docId; ?>" />
                                    <?php }else{ ?>
                                      <input type="text" tabindex="5" class="form-control" id="<?php echo $docTypeArray[0].'_doc_id'; ?>" name="docId" readonly value="<?php echo $docId; ?>" />
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
                                    <input type="text" tabindex="7" class="form-control" id="validTo" name="validTo" readonly value="<?php echo $validTo; ?>" />
                                  </div>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="text-center">
                              <input type="submit" class="btn btn-primary" value="Save" name="formSubmit" />
                              <a href="KYEInfo.php"><input type="button" class="btn btn-default" value="Cancel" /></a>
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
 $("input[name='docId']").change(function(){
    $('#addKYE').bootstrapValidator('revalidateField', 'docId');
  });
});
</script>
