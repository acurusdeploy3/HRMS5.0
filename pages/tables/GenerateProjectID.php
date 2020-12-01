<?php
include("config2.php");

$client = $_POST['client'];
$Department = $_POST['Department'];
$projFrom = $_POST['projFrom'];
$projAbb = $_POST['projAbb'];
$ProjectSeries = $_POST['ProjectSeries'];
$datefrom=date_create($projFrom);
$AbbDate = date_format($datefrom,'Ym');
$CheckifCapella = mysqli_query($db,"select * from all_project_departments where Dept_Abb='$Department' and is_capella_suite='Y'");
$IsCapella = mysqli_num_rows($CheckifCapella);
if($IsCapella==0)
{
	$ProjecID = $client.'_'.$Department.'_'.$projAbb.'_'.$AbbDate.'_'.$ProjectSeries;
}
else
{
	$ProjecID = $client.'_'.$Department.'_'.$AbbDate.'_'.$ProjectSeries;
} 





echo $ProjecID;
  	exit();
?>