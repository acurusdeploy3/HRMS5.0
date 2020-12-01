<?php
require_once("config.php");
require_once('queries.php');
require_once('layouts/top-header.php');
require_once('layouts/main-header.php');
require_once('layouts/main-sidebar.php');

$docTypesQuery = mysqli_query($db,"SELECT document_type FROM all_document_types");
$docsQuery = mysqli_query($db,"SELECT * FROM employee_documents_tracker where is_active = 'Y'");
$empId = 1234;
if(isset($_POST['uploadDoc'])){

  $docType = (isset($_POST['doc_type']) && $_POST['doc_type'])?$_POST['doc_type']:'';
  $docNum = (isset($_POST['doc_num']) && $_POST['doc_num'])?$_POST['doc_num']:'';

  $target_dir = "../uploads/";
  $target_file = $target_dir.$empId."_".basename($_FILES["doc"]["name"]);

  move_uploaded_file($_FILES["doc"]["tmp_name"],$target_file);
  $date = date("Y-m-d h:i:s");
  mysqli_query($db,"INSERT INTO employee_documents_tracker(employee_id, document_type, document_name, created_date_and_Time, created_by, is_active) VALUES ($empId,'$docType','$target_file','$date',$empId,'Y')");
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Documents
        <small>upload Documents here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>        <li class="active">Documents</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Documents</h3>
        </div>
        <div class="box-body">
          <div class="border-class">
            <form action="" enctype="multipart/form-data" method="post">
              <div class="row">
                <div class="form-group col-xs-3">
                  <label for="email">Document Type:</label>
                  <select class="form-control" name="doc_type">
                    <option value="">Select Document Type</option>
                    <?php
                    while($docTypes = mysqli_fetch_assoc($docTypesQuery)){
                      echo "<option value='".$docTypes['document_type']."'>".$docTypes['document_type']."</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-xs-3">
                  <label for="email">Document Number:</label>
                  <input type="text" class="form-control" id="doc_num" name="doc_num" />
                </div>
                <div class="form-group col-xs-3">
                  <label for="pwd">Document Attachment:</label>
                  <div class="upload-attachment" style="position: relative; overflow: hidden; cursor: default;">
                    <b class="sprite upload-icon" style="cursor:pointer">Upload Attachment</b>
                    <input type="file" class="form-control" id="doc" name="doc" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 100%; left: 0px; z-index: 100; opacity: 0;">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-1" style="margin-top: 25px;">
                    <button type="submit" name="uploadDoc" class="btn btn-default">Upload</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="border-class">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Document ID</th>
                  <th>Document Type</th>
                  <th>Document Number</th>
                  <th>View Document</th>
                </tr>
                <?php
                if(mysqli_num_rows($docsQuery) < 1){
                  echo "<tr><td cols-span='4'> No Documents Found </td></tr>";
                }else{
                  $i = 1;
                  while($docs = mysqli_fetch_assoc($docsQuery)){
                    echo "<tr><td>".$i.".</td>";
                    echo "<td>".$docs['doc_id']."</td>";
                    echo "<td>".$docs['document_type']."</td>";
                    echo "<td>1234</td>";
                    echo "<td><a href='".$docs['document_name']."'>view</a></td>";
                    $i++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
        <!-- <div class="box-footer">
          Footer
        </div> -->
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php
  require_once('layouts/main-footer.php');
  require_once('layouts/control-sidebar.php');
  require_once('layouts/bottom-footer.php');
  ?>
