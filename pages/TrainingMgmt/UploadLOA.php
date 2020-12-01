<?php
include("config.php");
include("ModificationFunc.php");
session_start();
$id = $_SESSION['EmpId'];
$createdby =$_SESSION['login_user'];
$uploaddir = '../../uploads/LOA_DOCS/';
$uploadfile = $uploaddir . basename($_FILES['ResumeFileDoc']['name']);
$FileData = pathinfo($uploadfile);
$FileExt=$FileData['extension'];
$EmpId=$_POST['hidEmpVal'];
$FileNamewithoutextension = pathinfo($uploadfile, PATHINFO_FILENAME);

if (move_uploaded_file($_FILES['ResumeFileDoc']['tmp_name'], $uploadfile)) 
 {
	 date_default_timezone_set('Asia/Kolkata');
	$dat =date("Ymd_Hi");
      echo "File is valid, and was successfully uploaded.\n";
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_RESOURCE_MGMT_LOA_'.$EmpId.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$newFilePath =$uploaddir.$new_name;
		$namewithdir = $dat.'_RESOURCE_MGMT_LOA_'.$EmpId.'.'.$FileExt ;
		
		$getDocIdEx = mysql_query("select doc_id from employee_documents_tracker where document_type='RES_MGMT_LOA' and employee_id='$EmpId' and is_active='Y'");
	$DocIDRowEx  = mysql_fetch_assoc($getDocIdEx);
	$DocIdEx = $DocIDRowEx['doc_id'];
		$tabName='employee_documents_tracker';
		$tabNamedocs='employee_documents';
		$tabNamePrimKey = 'doc_id';
		StoreDatainHistory($DocIdEx, $tabName,$tabNamePrimKey);
		StoreDatainHistory($DocIdEx, $tabNamedocs,$tabNamePrimKey);
		
		$disbledoc = mysql_query("update employee_documents_tracker set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where employee_id='$EmpId' and doc_id='$DocIdEx' and Document_type='RES_MGMT_LOA'");
		$disbledoc1 = mysql_query("update employee_documents set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where doc_id='$DocIdEx'");
		
		
		$InsertResume = mysql_query("insert into employee_documents_tracker (employee_id,document_type,created_Date_and_time,created_by,is_active)
		values
		('$EmpId','RES_MGMT_LOA',now(),'$createdby','Y')");
		
		$getDocId = mysql_query("select doc_id from employee_documents_tracker where document_type='RES_MGMT_LOA' and employee_id='$EmpId' and is_active='Y'");
		$DocIDRow  = mysql_fetch_assoc($getDocId);
		$DocId = $DocIDRow['doc_id'];
		$InsertdOC = mysql_query("insert into employee_documents (doc_id,document_name,created_date_and_Time,created_by,is_active)
		values
		('$DocId','$namewithdir',now(),'$createdby','Y')");
    } 
	else 
	{
       echo "Upload failed";
    }
	$InsertResume = mysql_query("update resource_management_table set signed_loa_doc = '$DocId' where employee_id='$EmpId'");
	
	//header("Location: ViewResource.php?id=$id");
?>