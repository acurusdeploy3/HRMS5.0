<?php
//require_once("queries.php");
session_start();

include('config.php');
$name = $_SESSION['login_user'];

$strval = explode("|",$_POST["wflid"]);

$dval = $strval[1];
$did = $strval[2];
$idval = $strval[0];

$select =mysqli_query($db,"select * from dsr_comments where dsr_id='$did'");	
$get = mysqli_fetch_assoc($select);	
$id = $get['dsr_id'];	
$summary = $get['summary_id'];	
$emp = $get['employee_comments'];	
$tl = $get['tl_comments'];	
$manager = $get['manager_comments'];	
$insert = "Insert into `history_dsr_comments`(dsr_id,summary_id,employee_comments,tl_comments,manager_comments,deleted_by,	deleted_date_time)value ($id,(select dsr_summary_id from dsr_summary where employee_id='$idval' and date='$dval'),'$emp','$tl','$manager','$name',now())";	
$result = mysqli_query($db,$insert); 

$res="delete from dsr_comments WHERE dsr_id ='$did'";
$result = mysqli_query($db,$res); 
//echo $res;
?>