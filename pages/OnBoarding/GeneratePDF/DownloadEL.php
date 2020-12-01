<?php
require('mc_table.php');
session_start();
$employeeID = $_GET['id'];

//$effectivedate = $_GET['date'];
$effectivedate = date('Y-m-d');
$date= date_create($effectivedate);
$dateformatted = date_format($date,"jS F, Y");
include('config.php');
$result=mysqli_query($db,"select  @a:=@a+1 serial_number, Category,expected_deliverables from cos_pip_summary,(SELECT @a:= 0) AS a  where employee_id='$employeeID'  and is_active='Y'");
$getDetails = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI) as name,date_format(date_joined,'%d %b %Y') as date_of_joining
,date_format(services_effective_date,'%D %b, %Y') as services_effective_date from employee_details where employee_id=$employeeID and is_active='Y';");
$getDetailsRow = mysqli_fetch_array($getDetails);
$name = $getDetailsRow['name'];
$doj = $getDetailsRow['date_of_joining'];
$dos = $getDetailsRow['services_effective_date'];
$getDesignation = mysqli_query($db,"select concat(Band,' ',designation,' ',level) as designation from resource_management_table where employee_id=$employeeID and is_active='Y'");

$getDesignationRow = mysqli_fetch_array($getDesignation);
$degn = $getDesignationRow['designation'];
$getSignAuth = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as Name,employee_designation from employee_details where employee_id in (select value from application_configuration where config_type='AL_SIGNING_AUTHORITY')");
$getSignAuthRow = mysqli_fetch_array($getSignAuth);
$SignAuth = $getSignAuthRow['employee_designation'];
$Signeename = $getSignAuthRow['Name'];

$getConfirmationDate = mysqli_query($db,"SELECT date_format(DATE_ADD(date_of_completion_of_probation,INTERVAL 1 DAY),'%d %b %Y') as date,date_format(modified_date_and_time,'%d %b %Y') as date_top FROM `cos_master` where employee_id='$employeeID'");
$EffectivedateRow = mysqli_fetch_array($getConfirmationDate);
$date_top  = $EffectivedateRow['date_top'];

$cont = "We understand that your performance was not up to the required standard in your work deliverables in terms of Productivity as per details provided below.";
$cont1 = "We would like to provide you with yet another opportunity to scale up to the required performance level, as defined, in the next 3 months effective today, failing which we shall be constrained to take necessary action against your employment from Acurus Solutions as per terms and conditions laid down at the time of appointment duly acknowledge by you. ";
$cont2 = "All other terms and conditions of employment will remain unchanged as per your original letter of appointment.";
date_default_timezone_set('Asia/Kolkata');
$dat =date('d M Y');
$dat2 =date('Ymd_Hi');


$pdf=new PDF_MC_Table();
$pdf->AddFont('Calibri','B','Calibri.php');
$pdf->AddPage();
//$pdf->Ln(40);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->SetFont('Calibri','',11);
$pdf->Cell(160);
$pdf->Cell(20,10,$date_top,0,1);
$pdf->Cell(10);

$pdf->Cell(20,10,'Name                 : ');
$pdf->Cell(7);
$pdf->Cell(1,10,$name.' ',0,1);
$pdf->Cell(10);

$pdf->Cell(20,10,'Employee ID     :');
$pdf->Cell(7);
$pdf->Cell(1,10,$employeeID.' ',0,1);
$pdf->Cell(10);

$pdf->Cell(20,10,'Designation      : ');
$pdf->Cell(7);
$pdf->Cell(12,10,ltrim($degn).' ',0,1);

$pdf->Cell(10);
$pdf->Cell(20,10,'Date of Joining : ');
$pdf->Cell(7);
$pdf->Cell(12,10,$doj.' ');


$pdf->Ln(15);
$pdf->Cell(10);


$pdf->Cell(20,10,'Dear '.rtrim($name).',',0,1);
$pdf->Ln(5);

$pdf->Cell(10);
$pdf->MultiCell(0,5,$cont,0,1);
$pdf->Ln(8);
$pdf->Cell(10);

$pdf->SetWidths(array(20,30,130));
$pdf->Row(array('S.No','Category','Expected Deliverables'));

while($row=mysqli_fetch_assoc($result))
{	
	$pdf->Cell(10);
	$pdf->Row(array($row['serial_number'],$row['Category'],$row['expected_deliverables']));
}
$pdf->Ln(5);
$pdf->Cell(10);

$pdf->MultiCell(0,5,$cont1,0,1);
$pdf->Ln(5);
$pdf->Cell(10);
$pdf->Cell(20,10,'Kindly acknowledge by signing copy of this letter and return. ',0,1);
$pdf->Ln(5);
$pdf->Cell(10);
$pdf->Cell(20,10,'For Acurus Solutions Private Limited,',0,1);
$pdf->Ln(10);

$pdf->Cell(10);
$pdf->Cell(20,10,$Signeename,0,0);
$pdf->Ln(5);

$pdf->Cell(10);
$pdf->Cell(20,10,$SignAuth,0,0);
$pdf->Output('D',$dat2.'_EXTENSION_LETTER_'.$employeeID.'.pdf');
?>
