<?php
ob_start();
require_once("config.php");
require_once('queries.php');
require_once('Layouts/top-header.php');
require_once('Layouts/main-header.php');
require_once('Layouts/main-sidebar.php');
session_start();
$empId=$_SESSION['login_user'];

$msg = $docType = $validFrom = $expiry = $docNum = $validTo = $docId = '';
$id = 0;
$docTypeArray = array();
$currentDate = date("Y-m-d h:i:s");

if(isset($_GET['id']) && $_GET['id'] != ''){
  $id = $_GET['id'];

  $eduHistory = mysqli_query($db,"select * from kye_details where employee_id = $empId and kye_id = $id and is_Active = 'Y'");
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
  $validFrom = (isset($_POST['validFrom']) && $_POST['validFrom'] != '')?$_POST['validFrom']:'0001-01-01';
  $expiry = (isset($_POST['expiry']) && $_POST['expiry'] != '')?$_POST['expiry']:$expiry;
  $docNum = (isset($_POST['docNum']) && $_POST['docNum'] != '')?$_POST['docNum']:$docNum;
  $validTo = (isset($_POST['validTo']) && $_POST['validTo'] != '')?$_POST['validTo']:'0001-01-01';
  $docId = (isset($_POST['docId']) && $_POST['docId'] != '')?$_POST['docId']:$docId;
  $id = (isset($_POST['id']) && $_POST['id'] != '')?$_POST['id']:$id;

  if($id){
    mysqli_query($db,"update kye_details set document_type = '$docType', valid_from = '$validFrom', has_expiry = '$expiry', document_number = '$docNum', valid_to = '$validTo', doc_id= $docId, modified_by = $empId, modified_date_and_time = '$currentDate' where employee_id = $empId and kye_id = $id");
    if(mysqli_affected_rows($db)){
      storeDataInHistory($id, 'kye_details','kye_id');
      $msg = 'Updated Successfully';
    }
  }else{
    if(strtolower($expiry) == 'N'){
      $query = "INSERT INTO kye_details(employee_id, document_type, has_expiry, document_number, doc_id, created_by, created_date_and_time,is_Active) VALUES('$empId', '$docType','$expiry','$docNum','$docId',$empId,'$currentDate','Y')";
	   
    }else{
      $query = "INSERT INTO kye_details(employee_id, document_type, valid_from, has_expiry, document_number, valid_to, doc_id, created_by, created_date_and_time,is_Active) VALUES('$empId', '$docType','$validFrom','$expiry','$docNum','$validTo','$docId',$empId,'$currentDate','Y')";
    }
	
    mysqli_query($db, $query);
    if(mysqli_affected_rows($db)){
      mysqli_query($db,"update employee_documents_tracker set is_active = 'Y', Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
      mysqli_query($db,"update employee_documents set is_Active = 'Y', Modified_date_and_time = '$currentDate',modified_by = $empId where doc_id = $docId");
      storeDataInHistory($docId, 'employee_documents_tracker','doc_id');
      storeDataInHistory($docId, 'employee_documents','doc_id');
      $msg = 'Added Successfully';
    }
  }
  header("Location:kyeform.php");


}
$kyeQuery=mysqli_query($db,"select * from kye_details where employee_id=$empId and is_Active = 'Y'");
$docTypesData= mysqli_query($db,"select distinct document_type from all_document_types where authorized_for <>'HR'");

?>

<head>

<style>
.btn-default {
    background-color: #3c8dbc;
border-color : #3c8dbc; }
	.skin-blue .sidebar a {
    color: #ffffff;}
	.error {color: #FF0000;}
img {
    vertical-align: middle;
    height: 30px;
    width: 30px;
    border-radius: 20px;
}
.fa-fw {
    padding-top: 13px;
}
#goprevious,#finishme{
	background-color: #286090;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
#finish{
	background-color: #4CAF50;
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
	border-radius: 3px;
	border-color:#4CAF50;
	color:white;
	border: 1px solid transparent;
}
th {
  background-color: #31607c;
  color:white;
}


/* The Close Button 1  */

.close1 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close1:hover,
.close1:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
/* The Close Button   */

.close12 {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close12:hover,
.close12:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
</head>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Acurus Employee Form
        <small>Step 8</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">UPLOAD DOCUMENTS</h3>

            </div>
            <!-- /.box-header -->
            

            <div class="border-class">
              <!-- form start -->
              <div class="box-body no-padding">
                <div class="row">
                  
                  <div class="col-md-12" id="tab-content">
                    <div class="tab-content">
                       
                          <?php if($msg != ''){ ?>
                          <div class="alert alert-success alert-dismissible custom-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $msg; ?>
                          </div>
                          <?php } ?>
                          <!-- form start -->
                          <form role="form" id="addKYE" action="" method="POST" class="form-horizontal" onLoad="load();" autocomplete="off"); >
                          
							
							
							 <div class="box-body">
                              <div class="col-sm-12">
                                <div class="form-group ">
                                  <label for="exampleInputEmail1" class="col-sm-2 control-label" >Document Type<span class="astrick">*</span></label>
                                  <div class="col-sm-5">
                                  <?php if(!$id){ ?>
                                  <select class="form-control" id="docType" name="docType">
                                    <option value="" selected>Select Document Type</option>
				<?php
					while($row2 = mysqli_fetch_assoc($docTypesData))
						{
  				 ?>
					<option value= "<?php echo $row2['document_type']." ";?>" ><?php  echo $row2['document_type']." "; ?></option> 
				<?php 
				    }
			   	 ?>
                                  </select>
                                <?php }else { ?>
                                  <input type="text" class="form-control" name="docType" readonly value="<?php echo $docType; ?>" />
                                <?php } ?>
                               </div>
							   <div class="col-sm-1">
	</div>	</div> <div class="form-group ">
	
                                  <label for="exampleInputEmail1" class="col-sm-2 control-label">Document Number</label>
								  <div class="col-sm-5">
                                  <input type="text" class="form-control" id="docNum" name="docNum" value="<?php echo $docNum; ?>" autocomplete="off"/>
                                </div>
                                </div>
								<div class="form-group">
                                  <label for="exampleInputEmail1" class="col-sm-2 control-label">Expiry</label>
								  <div class="col-sm-4">
                                  <select class="form-control" name="expiry" onchange="yesnoCheck(this);" id="expirycondition">
                                    <option value="N"  <?php if($expiry == 'N') {echo "selected";} ?>>N</option>
									<option value="Y" <?php if($expiry == 'Y') {echo "selected";} ?>>Y</option>
                                  </select>
								  </div>
					<div class="col-sm-1"></div>									  
                                    <label for="exampleInputEmail1" class="col-sm-2 control-label">Doc Id<span class="astrick">*</span></label>
									<div class="col-md-2">
                                    <?php if(!$id){ ?>
                                      <input type="text" class="form-control" id="docId" name="docId" readonly value="<?php echo $docId; ?>"  ></input>
                                    <?php }else{ ?>
                                      <input type="text" class="form-control" id="<?php echo $docTypeArray[0].'_doc_id'; ?>" id="docId" name="docId" readonly value="<?php echo $docId; ?>" ></input>
                                    <?php } ?>
                                  </div>
								<div class="col-md-1 btn_div">
                                <?php if(!$id){ ?>
                                  <a href="#myModal" class="btn_anchor" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                <?php }else{ ?>
                                  <a href="#myModal" class="btn_anchor" id="<?php echo $docTypeArray[0]; ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                <?php } ?>
                                </div>
                                </div>
                                <div class="form-group">
								<div id="ifYes" style="display: none;">
                                  <label for="exampleInputEmail1" class="col-sm-2 control-label">Valid From</label>
								  <div class="col-sm-4">
                                  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control" id="validFrom"  name="validFrom" value="<?php echo $validFrom; ?>" autocomplete="anyrandomstring"/>
                                  </div>
								  </div>
                                  <label for="exampleInputEmail1" class="col-sm-2 control-label">Valid To</label>
								  <div class="col-sm-4">
								  <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control" id="validTo" name="validTo"  value="<?php echo $validTo; ?>" autocomplete="anyrandomstring"/>
                                  </div>
                                  <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" />
                               </div>                                  
							   </div>
                                </div>
                                
                                
                                </div>
                              </div>
                             
                               
							
                            <div class="box-footer">
							<input action="action" class="btn btn-info pull-left" type="button" value="Previous" onclick="window.location='miscform.php';"id="goprevious" />  
                              
							  <input type="button" class="btn btn-default pull-right" value="Complete Formalities" style = "margin-left: 7px;" name="finish" id="finish"/>
							  <input type="submit" class="btn btn-primary pull-right" value="Save" name="formSubmit" style = "margin-right: 7px;display:none;" id="finishme"  ></input>
                            </div>
							
                            <!-- /.box-body -->
                          </form>
                          <!-- </form> -->
            						
                    </div>
								 <div class="border-class">	
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>	  
                  
                  <th>Document Name</th>
                  <th>Document Number</th>
				  <th>Document ID</th>
                  <th>Has Expiry</th>
                  <th>Valid From</th>
                  <th>Valid To</th>
                </tr>
                
				<?php
                                if(mysqli_num_rows($kyeQuery) < 1){
									echo "<tr><td cols-span='4'> No Results Found </td></tr>";
                }else{
                  $i = 1;
									
                                  while($row = mysqli_fetch_assoc($kyeQuery)){
                                     echo "<tr><td>".$i.".</td>";
									echo "<td>".$row['document_type']."</td>";
									echo "<td>".$row['document_number']."</td>";
									echo "<td>".$row['doc_id']."</td>";
									echo "<td>".$row['has_expiry']."</td>";
									if($row['has_expiry']=='Y'){
									echo "<td>".$row['valid_from']."</td>";
									echo "<td>".$row['valid_to']."</td>";}
									else
									{
									echo "<td></td>";	
									echo "<td></td>";	
									}
									   echo "<td><a href='deletekye.php?kye_id=".$row['kye_id']."'><i class='fa fa-trash'></i></a></td>
                										</tr>";
											$i++;			
                                  }
								 
                                } 
                                ?>
              </tbody>
            </table>
			<label>Note<span class="astrick">** </span>  Kindly note the Document ID on the hardcopy of the documents that you upload</label>
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
              <button type="button" id="closemodal" class="close" data-dismiss="modal">&times;</button>
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
              <button type="button" class="btn btn-primary" id="addNewField" onclick="AddingDocument();">Save</button>
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
require_once('Layouts/documentModals.php');

require_once('Layouts/bottom-footer.php');
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

  
});
</script>
<script>
function yesnoCheck(that) {
        if (that.value == "Y") {
            
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
	function AddingDocument() {
            var ddl = document.getElementById("docType");
            var option = document.createElement("OPTION");
            option.innerHTML = document.getElementById("newField").value;
            option.value = document.getElementById("newField").value;
            ddl.options.add(option);
			$("#closemodal").click();
        }
		// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close1")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
 <script>
  $(function() {
  $("#validFrom,#validTo").datepicker({ 
	dateFormat: 'yyyy-mm-dd',
    autoclose: true
  });
});

	</script>
	 <script type="text/javascript">
$('#finish').click(function(e) {
  if (!confirm('Are you sure that you have completed the formalities?')){
  e.preventDefault();}
  else{
window.location.href = 'updatedetails.php';	  
}
});
    </script>