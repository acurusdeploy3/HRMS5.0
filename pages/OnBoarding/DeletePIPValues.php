<?php
//require_once("queries.php");
session_start();
require_once("config2.php");
require_once("config5.php");
$name = $_SESSION['login_user'];
$pipval = mysqli_real_escape_string($db,$_GET["pipval"]);
$idval = $_GET["idval"];
$scnval = $_GET["scnval"];
$date = date("Y-m-d h:i:s");
if(!empty($idval)) {
		$res="UPDATE cos_pip_summary set is_active = 'N',modified_date_and_time='".$date."',modified_by='$name' WHERE COS_PIP_SUMMARY_ID='".$_GET["pipval"]."' and cos_master_id='$idval'";
		$result = mysqli_query($db,$res);
		if(!empty($result)){
			if($scnval == 'Approval')
			{
				header("Location:EmpPIPApproval.php?idval=$idval&scnval=N");
			}
			else if($scnval == 'Complete')
			{
				header("Location:EmpPIPCompletion.php?idval=$idval&scnval=N");
			}
			else 
			{
				header("Location:EmpPIP.php?idval=$idval");
			}
		}
	}
?>