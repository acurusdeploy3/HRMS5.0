<?php
include("config.php");
$id = $_GET['id'];
mysqli_query($db,"update fyi_transaction set emp_ack='N' where transaction_id='$id'");
header("Location: DashboardFinal.php");
?>