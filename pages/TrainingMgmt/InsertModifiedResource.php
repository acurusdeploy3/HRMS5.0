<?php
session_start();
include('config.php');
include('ModificationFunc.php');
$rId = $_SESSION['rID'];
$empId = $_SESSION['EmpId'];
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$tabname = 'resource_management_table';
$primKey = 'res_management_id';


	



			  
			  
			  $resQuery = mysql_query("select res_management_id,employee_id,band,designation,level ,department,
				TIMESTAMPDIFF(MONTH,Created_Date_and_time,now()) as Months,project_id,reporting_manager

					from resource_management_table
						 WHERE employee_id=$empId and is_Active='Y'");
			  $tRow = mysql_fetch_array($resQuery);
			  //$rId = $tRow['res_management_id']; 
			  $bandOld = $tRow['band']; 
			  $desnOld = $tRow['designation']; 
			  $levelOld = $tRow['level']; 
			  $monthsservedOld = $tRow['Months']; 
			  $projnameOld = $tRow['project_id']; 
			  $DeptOld = $tRow['department']; 
			  $repOld = $tRow['reporting_manager']; 
	
	
	
	
	
	
	StoreDatainHistory($rId, $tabname,$primKey);
	
	
		$getDocIdEx = mysql_query("select doc_id from employee_documents_tracker where document_type='RES_MGMT_LOA' and employee_id='$empId' and is_active='Y'");
		$DocIDRowEx  = mysql_fetch_assoc($getDocIdEx);
		$DocIdEx = $DocIDRowEx['doc_id'];
		$tabName='employee_documents_tracker';
		$tabNamedocs='employee_documents';
		$tabNamePrimKey = 'doc_id';
		StoreDatainHistory($DocIdEx, $tabName,$tabNamePrimKey);
		StoreDatainHistory($DocIdEx, $tabNamedocs,$tabNamePrimKey);
	
	$disbledoc = mysql_query("update employee_documents_tracker set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where employee_id='$empId' and doc_id='$DocIdEx' and Document_type='RES_MGMT_LOA'");
		$disbledoc1 = mysql_query("update employee_documents set is_active='N',modified_Date_and_time=now(),modified_by='$createdby' where doc_id='$DocIdEx'");
	
	
//$MRFIDVal = $_POST['MRFID'];
$Depatment = $_POST['DeptSelect'];
$Band = $_POST['BandSelect'];
$effectfrom = $_POST['dateFrom'];
$Mgmr = $_POST['RepMgmr'];
$Role = $_POST['RoleSelect'];
$Lev =   $_POST['LevelSel'];
$Proj =   $_POST['ProjSelect'];


			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name from employee_details where employee_id='$Mgmr'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];
			  
			  
			   $query4 = mysql_query("select project_no,project_id,project_name from all_projects where project_id='$Proj'");
				$ProRow = mysql_fetch_array($query4);
			  $ProjVal = $ProRow['project_name'];
			  
			  
			  $query5 = mysql_query("select project_no,project_id,project_name from all_projects where project_id='$projnameOld'");
				$ProRow = mysql_fetch_array($query5);
			  $ProjValold = $ProRow['project_name'];
			  
			  
if($bandOld!=$Band || $desnOld!=$Role || $levelOld!=$Lev )
{
	$degn = 'Y';
	$oldDesn = $bandOld.' '.$desnOld.'  '.$levelOld;
	$newdesn = $Band.' '.$Role.' '.$Lev;
	$_SESSION['DesChange']  = 'It is being announced with immense please that your are being designated as '.$newdesn.' from '.$oldDesn.'. ';
	$_SESSION['DesPDF']  = 'It is being announced with immense please that your are being designated as '.$newdesn.' from '.$oldDesn.'. ';
}
if($Mgmr!=$repOld)
{
	$mngrchanged = 'Y';
	$_SESSION['RepMgmr']='You are Supposed to be answerable to '.$repMngrName.' for the deeds you will have to perform in any case.'.' ';
	$_SESSION['MgmrPDF']='You are Supposed to be answerable to '.$repMngrName.' for the deeds you will have to perform in any case.'.' ';
}
if($Depatment!=$DeptOld)
{
	$Deptchange = 'Y';
	$_SESSION['Dept'] = 'You are being transferred from  Department '.$DeptOld.' to '.$Depatment.'. ';
	$_SESSION['DeptPDF'] = 'You are being transferred from  Department '.$DeptOld.' to '.$Depatment.'. ';
}
$_SESSION['Proj']='';
$_SESSION['ProjectPDF']='';
$updateNew =mysql_query("update resource_management_table set modified_Date_and_time=now(),modified_by='$name',project_id=if('$Proj'='None','','$Proj'),
department='$Depatment',designation='$Role',reporting_manager='$Mgmr',effective_from='$effectfrom',modified_Date_and_time=now(),modified_by='$name',band='$Band',level='$Lev',signed_loa_doc='0'
 where res_management_id='$rId'");
						 
	$resQuery1 = "select res_management_id,employee_id,concat(band,' ',designation,' ',level) as Designation,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager


					from resource_management_table
						 WHERE employee_id=$empId and is_Active='Y'";			

$rec1 = mysql_query($resQuery1);
$res1 = mysql_fetch_array($rec1);						 
$data['result'] = $res1;
echo json_encode($data);
 
 
 