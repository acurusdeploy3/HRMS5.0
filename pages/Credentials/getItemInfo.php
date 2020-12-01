<?php 
if(isset($_REQUEST))
{
 include("config.php");
error_reporting(E_ALL && ~E_NOTICE);
$id=$_POST['ItemId'];
$qry1 = "select id, category, physical_location, logical_location, user_name, comments, title, expiry_cycle, last_renewed_date, created_date_time, modified_date_time, created_by, modified_by, next_renewal_date, is_active
from credentials_master where id='$id'";
$rec1 = mysqli_query($db,$qry1);
$res1 = mysqli_fetch_array($rec1);
$ProjId = $res1['project_id'];
$data['result'] = $res1;
echo json_encode($data);
}
?>