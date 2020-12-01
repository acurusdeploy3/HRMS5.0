<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);
$id=$_POST['ItemId'];
$CategorySel=$_POST['CategorySel'];
session_start();
$name = $_SESSION['login_user'];
$PhysicalLocationText=mysqli_real_escape_string($db,$_POST['PhysicalLocationText']);
$userNameText=mysqli_real_escape_string($db,$_POST['userNameText']);
$LogicalLocationText=$_POST['LogicalLocationText'];
$LogicalLocationText = isset($LogicalLocationText)?$LogicalLocationText:'';
$Comments=mysqli_real_escape_string($db,$_POST['Comments']);
$archive = mysqli_query($db,"insert into archived_credentials_master select 0,now(),'$name',e.* from credentials_master e where id='$id'");
$qry1 = "update credentials_master set category='$CategorySel',
		physical_location='$PhysicalLocationText',
		logical_location='$LogicalLocationText',
		user_name='$userNameText',
		comments='$Comments',modified_date_time=now(),modified_by='$name' where id='$id'";
$rec1 = mysqli_query($db,$qry1);

}
?>