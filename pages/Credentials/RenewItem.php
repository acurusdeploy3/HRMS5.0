<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);
session_start();
$name = $_SESSION['login_user'];
$id=$_POST['ItemId'];
$RenewalDate=date('Y-m-d');
$RenewalCycle=$_POST['RenewalCycle'];
$nextDate = date('Y-m-d', strtotime($RenewalDate. ' + '.$RenewalCycle.' days'));
$archive = mysqli_query($db,"insert into archived_credentials_master select 0,now(),'$name',e.* from credentials_master e where id='$id'");
$qry1 = "update credentials_master set last_renewed_date='$RenewalDate',modified_date_time=now(),modified_by='$name',next_renewal_date='$nextDate' where id='$id'";
$rec1 = mysqli_query($db,$qry1);
}
?>