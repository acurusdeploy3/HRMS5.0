<?php
if(isset($_REQUEST['empId']) && $_REQUEST['empId'] != '' && is_numeric($_REQUEST['empId'])){
  $viewEmpId =  $_REQUEST['empId'];

  // Employment Details Query
  $personDetailsQry = mysqli_query($db,"select * from employee_details where employee_id = $viewEmpId");
  $personDetailsData = mysqli_fetch_assoc($personDetailsQry);
  $dob = date('Y-m-d',strtotime($personDetailsData['Date_of_Birth']));
  $doj = date('Y-m-d',strtotime($personDetailsData['Date_Joined']));

if($personDetailsData['is_Active']=='Y')
{
	$monthsquery = mysqli_query($db,"SELECT if(date_joined<>'0001-01-01',TIMESTAMPDIFF(MONTH,date_joined, date(now())),'--') as experience FROM `employee_details` where employee_id = $viewEmpId");
	$monthsquerydata = mysqli_fetch_assoc($monthsquery);
}
else
{
	$monthsquery = mysqli_query($db,"SELECT TIMESTAMPDIFF(MONTH,doj, ldw) as experience FROM `employee_master` where employee_id = $viewEmpId");
	$monthsquerydata = mysqli_fetch_assoc($monthsquery);
	$deactivated = mysqli_query($db,"SELECT a.status,reason,ldw FROM `deactivated_employees` a inner join employee_master b on a.employee_id=b.employee_id where a.employee_id=$viewEmpId");
	$deactivatedRow = mysqli_fetch_assoc($deactivated);
}

  $empWorkHistoryQry = mysqli_query($db,"select * from employee_work_history where employee_id = $viewEmpId and is_active = 'Y'");
  $empQualificationsQry = mysqli_query($db,"select * from employee_qualifications where employee_id = $viewEmpId and is_active = 'Y'");
  $empFamilyQry = mysqli_query($db,"select family_id,family_member_name,relationship_with_employee,if(date_of_birth='0001-01-01','--',timestampdiff(year,date_of_birth,curdate())) as age,occupation from employee_family_particulars where employee_id=$viewEmpId and is_active='Y'");
  $empCertificationsQry = mysqli_query($db,"select * from employee_certifications where employee_id = $viewEmpId and is_Active = 'Y'");
  $empSkillsQry = mysqli_query($db,"select * from employee_skills where employee_id = $viewEmpId and is_active = 'Y'");
  $empKyeQry = mysqli_query($db,"select * from kye_details where employee_id = $viewEmpId and is_Active = 'Y'");
  $empDocumentsQry = mysqli_query($db,"select * from employee_documents_tracker where employee_id = $viewEmpId and is_active = 'Y'");
  $empKyeDataQry = mysqli_query($db,"select document_type from kye_details where employee_id = $viewEmpId and is_Active = 'Y'");
  
  $docTypesQuery = mysqli_query($db,"select distinct document_type from all_document_types where authorized_for ='HR'");
  $docTypesData = [];
  foreach(mysqli_fetch_all($docTypesQuery) as $key => $val) 
    {
		$docTypesData[$key] = $val[0];
	}

   foreach (mysqli_fetch_all($empKyeDataQry) as $key => $value)
    {
		if (($k = array_search($value[0], $docTypesData)) !== false)
			{
		unset($docTypesData[$k]);
			}
	}
}else{

}
?>
