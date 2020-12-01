<?php 
if(isset($_REQUEST))
{
	session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include("config.php");
error_reporting(E_ALL && ~E_NOTICE);

$CategorySel=mysqli_real_escape_string($db,$_POST['CategorySel']);
$PhysicalLocationText=mysqli_real_escape_string($db,$_POST['PhysicalLocationText']);
$LogicalLocationText=$_POST['LogicalLocationText'];
$LogicalLocationText = isset($LogicalLocationText)?$LogicalLocationText:'';
$userNameText=mysqli_real_escape_string($db,$_POST['userNameText']);
$ExpiryCycle=mysqli_real_escape_string($db,$_POST['ExpiryCycle']);
$NextRenewalDate=mysqli_real_escape_string($db,$_POST['NextRenewalDate']);
$Comments=mysqli_real_escape_string($db,$_POST['Comments']);

mysqli_query($db,"insert into credentials_master

(category,physical_location,logical_location,user_name,comments,expiry_cycle,last_renewed_date,created_by,next_renewal_date)

values

('$CategorySel','$PhysicalLocationText','$LogicalLocationText','$userNameText','$Comments','$ExpiryCycle','0001-01-01','$name','$NextRenewalDate')");

$itemid =mysqli_insert_id($db);

$owners = count($_POST['ItemOwners']);
if($owners>0)
{
	for($n=0; $n<$owners; $n++)  
		{  	
			$ownerid = $_POST["ItemOwners"][$n];
			mysqli_query($db,"insert into `credentials_ownership` (item_id,owner_id,created_by) values ($itemid,$ownerid,$name);");	
		}
}

$_SESSION['NewItem'] = 'New Item added Successfully';
header("Location: AllCredentials.php");
}
?>