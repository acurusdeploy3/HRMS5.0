<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
include('config.php');

//$MRFIDVal = $_POST['MRFID'];
$AppID = $_POST['AppID'];
$EmployeeFullName = $_POST['EmployeeFullName'];
$FirsName = $_POST['FirsName'];
if($_POST['LastName']!='--')
{
$LastName =   $_POST['LastName'];
}
else
{
	$LastName =   '';
}
if($_POST['MI']!='--')
{
$MI =   $_POST['MI'];
}
else
{
	$MI =   '';
}
$FullName = $FirsName.' '.$LastName.' '.$MI;



$getApplicantDetails = mysqli_query($db1,"select first_name,mi,last_name,date_of_birth,gender,
primary_mobile_number,alternate_mobile_number,
marital_Status,contact_email,position_applied_for from resume_Tracker.applicant_Details where applicant_id='$AppID'");
$getApplicantRow = mysqli_fetch_array($getApplicantDetails);
$Dob = $getApplicantRow['date_of_birth'];
$PersonalEmail = $getApplicantRow['contact_email'];
$MarStatus = $getApplicantRow['marital_Status'];
$PosApplied = $getApplicantRow['position_applied_for'];
$MobNum = $getApplicantRow['primary_mobile_number'];

$EmployeeID =   $_POST['EmployeeID'];
$EmployeeRole =   $_POST['EmployeeRole'];
$EmployeeType =   $_POST['JobType'];
$Employeeprobation =   $_POST['ProbationPeriod'];
$EmployeeContract =   $_POST['ContractPeriod'];
$MRFID =   $_POST['MRF'];
$EmployeeAadhaar = $_POST['EmployeeAadhaar'];
$sql = "select hiring_location,type_of_employment from mrf_table where concat('MR',mrf_id)='$MRFID'";
$results = mysqli_query($db1, $sql);
$locationRow = mysqli_fetch_array($results);
$Location = $locationRow['hiring_location'];
$type_of_employment = $locationRow['type_of_employment'];
$Dept =   $_POST['Department'];
date_default_timezone_set('Asia/Kolkata');

if($EmployeeType=='Permanent')
{
$probenddate = date('Y-m-d', strtotime('+'.$Employeeprobation.' months'));
$createEmployee = mysqli_query($db,"insert into employee_details (Applicant_id,employee_id,first_name,last_name,MI,Job_Role,primary_mobile_number,marital_status,
employee_personal_email,Date_Joined,created_daTe_and_time,created_by,
is_personal_data_filled,is_active,position_applied_for,date_of_birth,job_type,is_permanent,probation_period,probation_end_date,mrf_id,department,business_unit)
values('$AppID','$EmployeeID','$EmployeeFullName','','','$EmployeeRole','$MobNum','$MaritalStatus','$PersonalEmail',curdate(),now(),'$name','N','Y','$PosApplied','$Dob'
,'$EmployeeType','Y','$Employeeprobation','$probenddate','$MRFID','$Dept','$Location');
");
}
else
{
	$contractenddate = date('Y-m-d', strtotime('+'.$EmployeeContract.' months'));
$createEmployee = mysqli_query($db,"insert into employee_details (Applicant_id,employee_id,first_name,last_name,MI,Job_Role,primary_mobile_number,marital_status,
employee_personal_email,Date_Joined,created_daTe_and_time,created_by,
is_personal_data_filled,is_active,position_applied_for,date_of_birth,job_type,is_permanent,contract_duration,contract_end_date,mrf_id,department,business_unit)
values('$AppID','$EmployeeID','$EmployeeFullName','','','$EmployeeRole','$MobNum','$MaritalStatus','$PersonalEmail',curdate(),now(),'$name','N','Y','$PosApplied','$Dob'
,'$EmployeeType','N','$EmployeeContract','$contractenddate','$MRFID','$Dept','$Location');
");
}
$createEmployeeinBoarding = mysqli_query($db,"insert into employee_boarding (employee_id,date_of_joining,is_reported,is_doj_modified,is_aeds_filled,is_hipaa_signed,user_login_created,
created_date_and_time,created_by,is_active,is_formalities_completed)

values

('$EmployeeID',curdate(),'Y','N','N','N','Y',now(),'$name','Y','N')");
$insertAadhaar = mysqli_query($db,"insert into kye_Details (employee_id,document_type,document_number,has_expiry,valid_from,valid_to,doc_id,created_date_and_time,created_by,is_active,document_name)

values

('$EmployeeID','AADHAAR ','$EmployeeAadhaar','N','0001-01-01','0001-01-01',0,now(),'$name','Y','')");

$datejoined = date('d');
if($datejoined > 15)
{
	$datefrom = date('Y-m-d', strtotime('+1 months'));
	$dateto = date('Y-m-d', strtotime('+11 months'));
	$datefrom = date('Y-m-01', strtotime($datefrom));
	$dateto = date("Y-m-t", strtotime($dateto));
$insertintoPmstable = mysqli_query($db,"insert into employee_performance_review_dates

(employee_id,review_from_Date,review_to_Date,created_by)

values

('$EmployeeID','$datefrom','$dateto','$name')");
}
else
{
	$datefrom=date('Y-m-d', strtotime(date('Y-m-1')));
	$dateto = date('Y-m-d', strtotime('+10 months'));
	$dateto = date("Y-m-t", strtotime($dateto));		
$insertintoPmstable = mysqli_query($db,"insert into employee_performance_review_dates
(employee_id,review_from_Date,review_to_Date,created_by)
values
('$EmployeeID','$datefrom','$dateto','$name')");
}
$AD_server = "ldap://172.18.0.150"; 
$ds = ldap_connect($AD_server);
if ($ds) {
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
    $result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
	if (!$result)
	{
	echo 'Not Binded';
	}
    $info["cn"] = $EmployeeID;
    $info["sn"] = $EmployeeID;
	$info["uid"] = $EmployeeID;
    $info["objectclass"] = "inetOrgPerson";
	$info["userPassword"] = "acurus";
    $info["givenName"] = $EmployeeFullName;
    $r = ldap_add($ds, "cn=".$EmployeeID.",ou=Users,dc=acurusldap,dc=com", $info);
	if ($r)
		{
				echo 'Success';
				$dn = "cn=".$EmployeeRole.",ou=HRMS,ou=Test,ou=Acurus,dc=acurusldap,dc=com";
				$entry['memberuid'] = $EmployeeID;
				$s = ldap_mod_add($ds, $dn, $entry);
				if ($s)
				{
					echo 'Yes';
				}
		}
   else
		{
			echo ldap_errno($ds) ;
		}
    ldap_close($ds);
}
 else
	 {
		echo "Unable to connect to LDAP server";
	}
//header("Location: BoardingHome.php");
	
	