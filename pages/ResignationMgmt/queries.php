<?php
$desgnQuery = mysqli_query($db,"select designation,count(designation) as count from resource_management_table where is_active='Y' group by designation");
$deptQuery = mysqli_query($db,"select department,count(department) as count from resource_management_table where is_active='Y' group by department;");
$projQuery = mysqli_query($db,"select a.project_id,b.project_name,count(a.project_id) as count from resource_management_table a
join all_projects b on a.project_id = b.project_id where is_active='Y' group by a.project_id;");
$blood_groups_qry = mysqli_query($db,"select blood_group from all_blood_groups");
$docTypesQuery = mysqli_query($db,"select document_type from all_document_types");
$designationsQuery = mysqli_query($db,"select designation_desc from all_designations");
$qualificationsQuery = mysqli_query($db,"select qualification_desc from all_qualifications");
$limit = 20;
$startYear = date("Y") - $limit;

//employment details module query
$personDetailsQry = mysqli_query($db,"select * from employee_details where employee_id = $empId");
$personDetailsData = mysqli_fetch_assoc($personDetailsQry);
$dob = date('Y-m-d',strtotime($personDetailsData['Date_of_Birth']));
$doj = date('Y-m-d',strtotime($personDetailsData['Date_Joined']));

$empRealtionsQry = mysqli_query($db,"select * from employee_family_particulars where employee_id = $empId");
$empRealtionsData = mysqli_fetch_assoc($empRealtionsQry);

$empWorkHistoryQry = mysqli_query($db,"select * from employee_work_history where employee_id = $empId");
$empQualificationsQry = mysqli_query($db,"select * from employee_qualifications where employee_id = $empId");
$empCertificationsQry = mysqli_query($db,"select * from employee_certifications where employee_id = $empId");
$empSkillsQry = mysqli_query($db,"select * from employee_skills where employee_id = $empId");
$empKyeQry = mysqli_query($db,"select * from kye_details where employee_id = $empId");
$empDocumentsQry = mysqli_query($db,"select * from employee_documents_tracker where employee_id = $empId and is_active = 'Y'");

$empKyeDataQry = mysqli_query($db,"select document_type from kye_details where employee_id = $empId");

$docTypesData = [];
foreach(mysqli_fetch_all($docTypesQuery) as $key => $val) {
  $docTypesData[$key] = $val[0];
}

foreach (mysqli_fetch_all($empKyeDataQry) as $key => $value) {
  if (($k = array_search($value[0], $docTypesData)) !== false) {
    unset($docTypesData[$k]);
  }
}

function storeDataInHistory($id, $tabName,$primKey) {
	$HisTable = 'history_'.$tabName;
	$resQuery = mysqli_query($GLOBALS['db'], "insert into ".$HisTable." select 0,now(),e.*  from ".$tabName." e where ".$primKey." = $id");
}
?>
