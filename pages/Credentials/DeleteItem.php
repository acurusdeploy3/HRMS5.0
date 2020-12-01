<?php 
if(isset($_REQUEST))
{
 include("config.php");
 session_start();
 $name = $_SESSION['login_user'];
error_reporting(E_ALL && ~E_NOTICE);
$id=$_POST['ItemIdHidden'];
$archive = mysqli_query($db,"insert into archived_credentials_master select 0,now(),'$name',e.* from credentials_master e where id='$id'");
$qry1 = "update credentials_master set is_active='N',modified_date_time=now(),modified_by='$name' where id='$id'";
$rec1 = mysqli_query($db,$qry1);

header("Location: AllCredentials.php");
}
?>