<?php
require_once("config.php");

if(isset($_GET['doc_id']) && $_GET['doc_id'] != '' && is_numeric($_GET['doc_id'])){
  $uploadedDocsQry = mysqli_query($db,"select document_name from employee_documents where doc_id = ".$_GET['doc_id']." and is_Active = 'Y'");

  while($data = mysqli_fetch_assoc($uploadedDocsQry)){
    $file = $RELATIVE_PATH.$data['document_name'];
    if (file_exists($file)){
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      ob_clean();
      flush();
      readfile($file);
    }
  }
}
?>
