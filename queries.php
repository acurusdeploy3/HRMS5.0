<?php
ob_start();
$currentDate = date("Y-m-d h:i:s");
$limit = 20;
$startYear = date("Y") - $limit;
session_start();
$usergrp= $_SESSION['login_user_group'];
// All Master Details Query
$desgnQuery = mysqli_query($db,"select designation,count(designation) as count from resource_management_table where is_active='Y' group by designation");
$deptQuery = mysqli_query($db,"select department,count(department) as count from resource_management_table where is_active='Y' group by department");
$projQuery = mysqli_query($db,"select a.project_id,b.project_name,count(a.project_id) as count from resource_management_table a
join all_projects b on a.project_id = b.project_id where is_active='Y' group by a.project_id");
$blood_groups_qry = mysqli_query($db,"select blood_group from all_blood_groups");
if($usergrp=='HR')
{
$docTypesQuery = mysqli_query($db,"select distinct document_type from all_document_types where authorized_for ='HR'");
}
else
{
$docTypesQuery = mysqli_query($db,"select distinct document_type from all_document_types where authorized_for <>'HR'");
}
$designationsQuery = mysqli_query($db,"select designation_desc from all_designations");
$qualificationsQuery = mysqli_query($db,"select qualification_desc from all_qualifications");

// Review Details Query
$reviewDetailsQry = mysqli_query($db,"select * from employee_performance_review_dates where employee_id = $empId and is_Active = 'Y'");
$reviewDetailsData = mysqli_fetch_assoc($reviewDetailsQry);
$currentReviewId = isset($reviewDetailsData['employee_review_date_id'])?$reviewDetailsData['employee_review_date_id']:0;
$nextReviewId = isset($reviewDetailsData['next_review_id'])?$reviewDetailsData['next_review_id']:0;
$eligibleForReview = isset($reviewDetailsData['review_status'])?$reviewDetailsData['review_status']:0;
$_SESSION['isPMSEligible']=$eligibleForReview;
// Employment Details Query
$personDetailsQry = mysqli_query($db,"select * from employee_details where employee_id = $empId and is_Active = 'Y'");
$personDetailsData = mysqli_fetch_assoc($personDetailsQry);
$dob = date('Y-m-d',strtotime($personDetailsData['Date_of_Birth']));
$doj = date('Y-m-d',strtotime($personDetailsData['Date_Joined']));

$monthsquery = mysqli_query($db,"SELECT if(date_joined<>'0001-01-01',TIMESTAMPDIFF(MONTH,date_joined, date(now())),'--') as experience FROM `employee_details` where employee_id = $empId and is_Active = 'Y'");
$monthsquerydata = mysqli_fetch_assoc($monthsquery);

// Employee Relations Query
$empRealtionsQry = mysqli_query($db,"select * from employee_family_particulars where employee_id = $empId and is_active = 'Y'");
$empRealtionsData = mysqli_fetch_assoc($empRealtionsQry);

// Employment Details Queries
$empWorkHistoryQry = mysqli_query($db,"select * from employee_work_history where employee_id = $empId and is_active = 'Y'");
$empQualificationsQry = mysqli_query($db,"select * from employee_qualifications where employee_id = $empId and is_active = 'Y'");
$empFamilyQry = mysqli_query($db,"select family_id,family_member_name,relationship_with_employee,if(date_of_birth='0001-01-01','--',timestampdiff(year,date_of_birth,curdate())) as age,occupation from employee_family_particulars where employee_id=$empId and is_active='Y'");
$empCertificationsQry = mysqli_query($db,"select * from employee_certifications where employee_id = $empId and is_Active = 'Y'");
$empSkillsQry = mysqli_query($db,"select * from employee_skills where employee_id = $empId and is_active = 'Y'");
$empKyeQry = mysqli_query($db,"select * from kye_details where employee_id = $empId and is_Active = 'Y'");
if($usergrp =='HR' || $usergrp  == 'HR Manager')
{
$empDocumentsQry = mysqli_query($db,"select * from employee_documents_tracker where employee_id = $empId and is_active = 'Y'");
}
else
{
$empDocumentsQry = mysqli_query($db,"select * from employee_documents_tracker where employee_id = $empId and is_active = 'Y' and document_module!='Employee_Boarding' and document_type not in ('RES_MGMT_LOA','Resignation Cancellation Letter')");	
}
$empKyeDataQry = mysqli_query($db,"select document_type from kye_details where employee_id = $empId and is_Active = 'Y'");

// Resource Management Query
$resourceManagementQry = mysqli_query($db,"select * from resource_management_table where employee_id = $empId and is_Active = 'Y' limit 1");
$resourceManagementData = mysqli_fetch_assoc($resourceManagementQry);

// leads Query
$leadsQry = mysqli_query($db,"select * from employee_details where reporting_manager_id = $empId and is_Active = 'Y'");
$leadDetails = mysqli_fetch_all($leadsQry);
if(!empty($leadDetails))
{
	$_SESSION['hasTeam']='Y';
}
else
{
	$_SESSION['hasTeam']='N';
}
if(!empty($leadDetails)) {
  if(isset($_GET['appraisee_id']) && is_numeric($_GET['appraisee_id'])) {
    $appraiseeId = $_GET['appraisee_id'];

    $reviewAppraiseeDetailsQry = mysqli_query($db,"select * from employee_performance_review_dates where employee_id = $appraiseeId and is_Active = 'Y'");
    $reviewAppraiseeDetailsData = mysqli_fetch_assoc($reviewAppraiseeDetailsQry);
    $appraiseeCurrentReviewId = isset($reviewAppraiseeDetailsData['employee_review_date_id'])?$reviewAppraiseeDetailsData['employee_review_date_id']:0;
    $appraiseeNextReviewId = isset($reviewAppraiseeDetailsData['next_review_id'])?$reviewAppraiseeDetailsData['next_review_id']:0;
  }
}

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

function getPercentage($percentage) {
  if($percentage > 90){
    $satisfactory = 'Outstanding';
  }elseif($percentage > 80){
    $satisfactory = 'Very Good';
  }elseif($percentage > 70){
    $satisfactory = 'Good';
  }elseif($percentage > 60){
    $satisfactory = 'Satisfactory';
  }else{
    $satisfactory = 'Below Satisfactory';
  }

  return $satisfactory;
}
?>
