<?php
include("config2.php");
include("ModificationFunc.php");
session_start();
$createdby =$_SESSION['login_user'];
$DocType =$_POST['DocSelect'];
$uploaddir = '../../uploads/BOARDING/';
$uploadfile = $uploaddir . basename($_FILES['ResumeFileDoc']['name']);
$FileData = pathinfo($uploadfile);
$FileExt=$FileData['extension'];
$EmpId=$_SESSION['Employee_id'];
$FileNamewithoutextension = pathinfo($uploadfile, PATHINFO_FILENAME);

if (move_uploaded_file($_FILES['ResumeFileDoc']['tmp_name'], $uploadfile)) 
 {
	 date_default_timezone_set('Asia/Kolkata');
	$dat =date("Ymd_Hi");
    //  echo "File is valid, and was successfully uploaded.\n";
	  $old_name = $uploadfile;
        $new_name = $uploaddir.$dat.'_'.$DocType.'_'.$EmpId.'.'.$FileExt ;
		rename( $old_name, $new_name);	
		$newFilePath =$uploaddir.$new_name;
		$namewithdir = $dat.'_'.$DocType.'_'.$EmpId.'.'.$FileExt ;

		$InsertResume = mysqli_query($db,"insert into employee_documents_tracker (employee_id,document_type,created_Date_and_time,created_by,is_active)
		values
		('$EmpId','$DocType',now(),'$createdby','Y')");
		
		$getDocId = mysqli_query($db,"select doc_id from employee_documents_tracker where document_type='$DocType' and employee_id='$EmpId' and is_active='Y'");
		$DocIDRow  = mysqli_fetch_assoc($getDocId);
		$DocId = $DocIDRow['doc_id'];
		$InsertdOC = mysqli_query($db,"insert into employee_documents (doc_id,document_name,created_date_and_Time,created_by,is_active)
		values
		('$DocId','$namewithdir',now(),'$createdby','Y')");
    } 
	else 
	{
       echo "Upload failed";
    }
	
	header("Location: CompleteFormalities.php?id=$EmpId");
?>