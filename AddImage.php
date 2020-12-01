<?php
include("config.php");
include("ModificationFunc.php");
session_start();
$createdby =$_SESSION['login_user'];
$getName = mysqli_query($db,"select concat(First_name,' ',Last_name) as Name from employee_details where employee_id='$createdby'");
$getNameRow = mysqli_fetch_array($getName);
$EmpName = $getNameRow['Name'];
$filesCount = count($_FILES['ResumeFileDoc']['name']);
for($i = 0; $i < $filesCount; $i++){
$uploaddir = 'uploads/';
$uploadfile = $uploaddir . basename($_FILES['ResumeFileDoc']['name'][$i]);
$FileData = pathinfo($uploadfile);
$FileExt=$FileData['extension'];
$EmpId=$_POST['hidEmpVal'];
$FileNamewithoutextension = pathinfo($uploadfile, PATHINFO_FILENAME);

if (move_uploaded_file($_FILES['ResumeFileDoc']['tmp_name'][$i], $uploadfile)) 
 {
	 date_default_timezone_set('Asia/Kolkata');
	$dat =date("Ymd_Hisu");
      echo "File is valid, and was successfully uploaded.\n";
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_DAILY_IMAGE_'.$i.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$newFilePath =$uploaddir.$new_name;
		$namewithdir = $dat.'_DAILY_IMAGE_'.'.'.$FileExt ;
		$imgs.=$new_name.'-';
		//$getDocIdEx = mysql_query("select doc_id from employee_documents_tracker where document_type='RES_MGMT_LOA' and employee_id='$EmpId' and is_active='Y'");
		//$DocIDRowEx  = mysql_fetch_assoc($getDocIdEx);
		//$DocIdEx = $DocIDRowEx['doc_id'];
		//$tabName='employee_documents_tracker';
		//$tabNamedocs='employee_documents';
		//$tabNamePrimKey = 'doc_id';
		//StoreDatainHistory($DocIdEx, $tabName,$tabNamePrimKey);
		//StoreDatainHistory($DocIdEx, $tabNamedocs,$tabNamePrimKey);
		
		//$disbledoc = mysql_query("update employee_documents_tracker set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where employee_id='$EmpId' and doc_id='$DocIdEx' and Document_type='RES_MGMT_LOA'");
		//$disbledoc1 = mysql_query("update employee_documents set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where doc_id='$DocIdEx'");
 }	
}
	$InsertResume = mysqli_query($db,"update application_configuration set value=concat(value,'$imgs'),created_date_and_time=now(),created_by='$EmpName' where config_type='DAILY_IMAGE';");
	
	header("Location: DashBoardFinal.php");
?>