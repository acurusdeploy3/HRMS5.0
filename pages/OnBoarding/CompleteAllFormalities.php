<?php
session_start();
$usergrp=$_SESSION['login_user_group'];
$name = $_SESSION['login_user'];
include('config2.php');
//$MRFIDVal = $_POST['MRFID'];
$emplid= $_SESSION['Employee_id'];
	
			
$isFormComplete = 'Y';

date_default_timezone_set('Asia/Kolkata');

		$UpdateEmployee = mysqli_query($db,"update employee_boarding set are_documents_uploaded='Y' where
		employee_id='$emplid'");
	

	