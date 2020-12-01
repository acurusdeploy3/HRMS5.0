<?php
//require_once("queries.php");
session_start();
include('config.php');
$name = $_SESSION['login_user'];

$saveid = $_POST["saveid"];
$dateval = $_POST['dateval'];

$date = date("Y-m-d h:i:s");


$res="Update dsr_summary set is_sent='Y',modified_date_and_time='".$date."',modified_by='$name' where employee_id='$saveid' and date='$dateval' and is_active='Y'";
$result = mysqli_query($db,$res);

?>