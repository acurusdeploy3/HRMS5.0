<?php
session_start();
include('config.php');
include('config2.php');
include('ModificationFunc.php');
$rId = $_SESSION['rID'];
$empId = $_SESSION['EmpId'];
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
$JRole =  $_POST['EmployeeRole'];
$tabname = 'resource_management_table';
$primKey = 'res_management_id';

$getJobRole = mysql_query("select concat(First_name,' ',last_name) as name ,job_role from employee_details where employee_id=$empId");
	 $getJobRoleRow = mysql_fetch_array($getJobRole);
  $JR = $getJobRoleRow['job_role']; 
  $EmpName = $getJobRoleRow['name']; 
	if($JRole != $JR)
	{
		$AD_server = "ldap://172.18.0.150"; 
		$ds = ldap_connect($AD_server);
		if ($ds) {
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); // IMPORTANT
					$result = ldap_bind($ds, "cn=Manager,dc=acurusldap,dc=com","Acurus@123"); //BIND
					if (!$result)
						{
							echo 'Not Binded';
						}
   
						$dn = "cn=".$JR.",ou=HRMS,ou=Test,ou=Acurus,dc=acurusldap,dc=com";
						$dnN = "cn=".$JRole.",ou=HRMS,ou=Test,ou=Acurus,dc=acurusldap,dc=com";
						$entry['memberuid'] = $empId;
						$s = ldap_mod_del ($ds, $dn, $entry);
						if ($s)
						{
								ldap_mod_add($ds, $dnN, $entry);
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
    mysql_query("update employee_Details set job_role='$JRole' where employee_id='$empId'");
	}		

			  
			  
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
	
	
	
//$MRFIDVal = $_POST['MRFID'];
$Depatment = $_POST['DeptSelect'];
if($_POST['BandSelect']=='NIL')
{
	$Band='';
}
else
{
$Band = $_POST['BandSelect'];
}
$effectfrom = $_POST['dateFrom'];
$Mgmr = $_POST['RepMgmr'];
$Role = $_POST['RoleSelect'];
if($_POST['LevelSel']=='NIL')
{
	$Lev='';
}
else
{
$Lev =   $_POST['LevelSel'];
}
$Proj =   $_POST['ProjSelect'];


			  $getMngrName = mysql_query("select concat(first_name,' ',last_name,' ',mi) as name,Official_Email from employee_details where employee_id='$Mgmr'");
			  $ManagerNameRow = mysql_fetch_array($getMngrName);
			  $repMngrName = $ManagerNameRow['name'];
			  $MgmrMail = $ManagerNameRow['Official_Email'];
			  
			  
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
	mysql_query("insert into fyi_transaction

(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by)

values

('$empId','$EmpName','Designation Change','Resource Management','$newdesn','$effectfrom','Y',now(),'Acurus')");
}
if($Mgmr!=$repOld)
{
	$mngrchanged = 'Y';
	$_SESSION['RepMgmr']='You are Supposed to be answerable to '.$repMngrName.' for the deeds you will have to perform in any case.'.' ';
	$_SESSION['MgmrPDF']='You are Supposed to be answerable to '.$repMngrName.' for the deeds you will have to perform in any case.'.' ';
	mysql_query("insert into fyi_transaction

(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by)

values

('$empId','$EmpName','Reporting Manager Change','Resource Management','$repMngrName','$effectfrom','Y',now(),'Acurus')");

mysql_query("update notification_contact_email set reporting_manager_contact_email='$MgmrMail' where employee_id='$empId'");
}
$_SESSION['Proj']='';
$_SESSION['ProjectPDF']='';
if($Depatment!=$DeptOld)
{
	$Deptchange = 'Y';
	$_SESSION['Dept'] = 'You are being transferred from  Department '.$DeptOld.' to '.$Depatment.'. ';
	$_SESSION['DeptPDF'] = 'You are being transferred from  Department '.$DeptOld.' to '.$Depatment.'. ';
	mysql_query("insert into fyi_transaction

(employee_id,employee_name,transaction,module_name,message,date_of_message,is_active,created_Date_and_time,created_by)

values

('$empId','$EmpName','Department Change','Resource Management','$Depatment','$effectfrom','Y',now(),'Acurus')");
}

include("config2.php");
$InsertNew = mysqli_query($db,"insert into resource_management_table
(employee_id,department,designation,reporting_manager,created_date_and_time,created_by,is_active,band,level,signed_loa_doc,effective_from)
values
('$empId','$Depatment','$Role','$Mgmr',now(),'$name','N','$Band','$Lev','','$effectfrom')");
$_SESSION['NewRID'] = mysqli_insert_id($db);
//$SESSION['NewRID'] = $insertedId;


$updateold = mysql_query("update resource_management_table set modified_Date_and_time=now(),modified_by='$name' where res_management_id='$rId'");

// $desg = $Band.' '.$Role.' '.$Lev;

 //$updateemployeedetails = mysql_query("update employee_details set modified_Date_and_time=now(),modified_by='$name',employee_Designation='$desg',reporting_manager_id='$Mgmr' where employee_id='$empId'");
						 
	$resQuery1 = "select res_management_id,employee_id,concat(band,' ',designation,' ',level) as Designation,department,
				date_format(effective_from,'%d-%b-%Y') as  Effective_From,project_id,reporting_manager


					from resource_management_table
						 WHERE employee_id=$empId and is_Active='Y'";			

$rec1 = mysql_query($resQuery1);
$res1 = mysql_fetch_array($rec1);	
				 
$data['result'] = $res1;
//echo json_encode($data);
 
 //echo $JRole.' '.$JR;
header("Location: NotifyResource.php");
 