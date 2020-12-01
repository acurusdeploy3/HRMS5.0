<?php
require_once("config.php");
require_once("queries.php");

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

if(isset($_POST['action']) && $_POST['action'] == 'addNewFieldValue'){
  $table = (isset($_POST['table']) && $_POST['table'])?$_POST['table']:'';
  $column = (isset($_POST['column']) && $_POST['column'])?$_POST['column']:'';
  $field = (isset($_POST['field']) && $_POST['field'])?$_POST['field']:'';

  mysqli_query($db,"insert into $table ($column) values ('$field')");
  if(mysqli_insert_id($db)){
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'uploadNewPhoto'){
  $fileExtension = strtolower(pathinfo(basename($_FILES['file']["name"]),PATHINFO_EXTENSION));
  $targetFile = $empId."_".time()."_profileImage.".$fileExtension;
  move_uploaded_file($_FILES['file'],$TARGET_DIR.$targetFile);
  mysqli_query($db,"update employee_details set Employee_image = '$targetFile' where employee_id = $empId");
  if(mysqli_affected_rows($db)){
    storeDataInHistory($empId, 'employee_details','employee_id');
    echo "success";
  }else{
    echo "failed";
  }
}

if(isset($_POST['action']) && $_POST['action'] == 'upload'){

  $docType = (isset($_POST['doc_type']) && $_POST['doc_type'])?$_POST['doc_type']:'';
  $currentDocId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:'';

  if($currentDocId){
    mysqli_query($db,"update employee_documents_tracker set is_active = 'N' where doc_id = $currentDocId");
    mysqli_query($db,"update employee_documents set is_Active = 'N' where doc_id = $currentDocId");
    storeDataInHistory($currentDocId, 'employee_documents_tracker','doc_id');
    storeDataInHistory($currentDocId, 'employee_documents','doc_id');
  }

  $fileArray = reArrayFiles($_FILES['files']);

  $date = date("Y-m-d h:i:s");
  mysqli_query($db,"insert into employee_documents_tracker(employee_id, document_type, created_date_and_Time, created_by, is_active) values ($empId,'$docType','$date',$empId,'Y')");
  $docId = mysqli_insert_id($db);

  foreach ($fileArray as $key => $file) {
    $fileExtension = strtolower(pathinfo(basename($file["name"]),PATHINFO_EXTENSION));
    $fileName = str_replace(" ","_",$docType);
    $targetFile = $empId."_".time()."_".$fileName."_".$key."_.".$fileExtension;
    move_uploaded_file($file["tmp_name"],$TARGET_DIR.$targetFile);
    mysqli_query($db, "insert into employee_documents (document_name,doc_id,created_by,is_Active) values('$targetFile',$docId,$empId,'Y')");
  }

  echo $docId;
}

if(isset($_POST['action']) && $_POST['action'] == 'getFile'){

  $docId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:'';

  $docIdQuery = mysqli_query($db,"SELECT document_name FROM employee_documents_tracker WHERE is_active = 'Y' and doc_id = ".$docId);
  $data = mysqli_fetch_assoc($docIdQuery);

  echo '<img src="'.$DOCUMENT_PATH.@$data['document_name'].'" class="img-responsive" />';
}

if(isset($_POST['action']) && $_POST['action'] == 'displayForm'){

  $docType = (isset($_POST['doc_type']) && $_POST['doc_type'])?$_POST['doc_type']:'';
  $docId = (isset($_POST['doc_id']) && $_POST['doc_id'])?$_POST['doc_id']:0;

  $docTypesQuery = mysqli_query($db,"SELECT document_type FROM all_document_types WHERE document_type LIKE '%".$docType."%'");
  $data = mysqli_fetch_assoc($docTypesQuery);

  echo '<div class="row">
    <div class="col-md-6">
      <form role="form" id="uploadForm" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="exampleInputEmail1">Document Type:</label>
          <input type="text" class="form-control" id="doc_type" name="doc_type" value="'.@$data['document_type'].'" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Document Attachment:</label>
          <input type="file" class="form-control" name="filesToUpload[]" id="filesToUpload" multiple="multiple" accept="application/pdf,image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
          <input type="hidden" class="form-control" name="doc_id" id="doc_id" value="'.@$docId.'"/>
        </div>
        <input type="button" class="btn btn-primary" name="uploadDoc" id="uploadDoc" value="Upload" />
      </form>
    </div>
  </div>';
?>
<script type="text/javascript">
$("#uploadDoc").click(function() {

  var fd = new FormData();
  var doc_type = $("#doc_type").val();
  var doc_id = $("#doc_id").val();
  var files = $('#filesToUpload')[0].files;

  //fd.append('file',files);
  fd.append('doc_type',doc_type);
  fd.append('doc_id',doc_id);
  fd.append('action','upload');
  for (var x = 0; x < files.length; x++) {
    fd.append("files[]", files[x]);
  }
  $.ajax({
         url: "ajaxUpload.php",
         type: "POST",
         data:  fd,
         contentType: false,
         processData:false,
         cache: false,
         success: function(data){
           $("#uploadForm")[0].reset();
           $("#"+type+"_doc_id").val(data);
           $("#edit-modal").modal('hide');
         }
  });
});
</script>
<?php } ?>
