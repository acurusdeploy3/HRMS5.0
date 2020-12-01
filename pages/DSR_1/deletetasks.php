<?php
//require_once("queries.php");
session_start();

include('config.php');
$name = $_SESSION['login_user'];

$strval = explode("|",$_POST["wflid"]);

$dval = $strval[1];
$did = $strval[2];
$idval = $strval[0];

$strval1 = explode("|",$_GET["wflid"]);
$dval1 = $strval1[1];
$idval1 = $strval1[0];


$date = date("Y-m-d h:i:s");
if(!empty($did))
{
$res="UPDATE dsr_summary set is_active = 'N',modified_date_and_time='".$date."',modified_by='$name' WHERE dsr_summary_id ='$did' and date='$dval' and employee_id='$idval'";
$result = mysqli_query($db,$res);
//echo $res;
}
else{

$res="UPDATE dsr_summary set is_active = 'N',modified_date_and_time='".$date."',modified_by='$name' WHERE date='$dval1' and employee_id='$idval1'";
$result = mysqli_query($db,$res);
//echo $res;
header("Location: MyDSRReports.php");	
	
}

?>