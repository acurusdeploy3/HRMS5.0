<?php
require('fpdf.php');
session_start();
$employeeID = $_GET['id'];

//$effectivedate = $_GET['date'];
$effectivedate = date('Y-m-d');
$date= date_create($effectivedate);
$dateformatted = date_format($date,"jS F, Y");
include('config.php');

$getDetails = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI) as name,date_format(date_joined,'%d %b %Y') as date_of_joining
,date_format(services_effective_date,'%D %b, %Y') as services_effective_date from employee_details where employee_id=$employeeID and is_active='Y';");
$getDetailsRow = mysqli_fetch_array($getDetails);
$name = $getDetailsRow['name'];
$doj = $getDetailsRow['date_of_joining'];
$dos = $getDetailsRow['services_effective_date'];
$getDesignation = mysqli_query($db,"select employee_designation from employee_details where employee_id=$employeeID");

$getDesignationRow = mysqli_fetch_array($getDesignation);
$degn = $getDesignationRow['employee_designation'];
$getSignAuth = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as Name,employee_designation from employee_details where employee_id in (select value from application_configuration where config_type='AL_SIGNING_AUTHORITY')");
$getSignAuthRow = mysqli_fetch_array($getSignAuth);
$SignAuth = $getSignAuthRow['employee_designation'];
$Signeename = $getSignAuthRow['Name'];

$getConfirmationDate = mysqli_query($db,"SELECT date_format(DATE_ADD(date_of_completion_of_probation,INTERVAL 1 DAY),'%d %b %Y') as date,date_format(modified_date_and_time,'%d %b %Y') as date_top FROM `cos_master` where employee_id='$employeeID'");
$EffectivedateRow = mysqli_fetch_array($getConfirmationDate);
$EffectiveDate = $EffectivedateRow['date'];
$date_top  = $EffectivedateRow['date_top'];

$cont = "We take pleasure in confirming your employment of services at Acurus Solutions Private Limited with effect from ".$EffectiveDate.". ";
$cont1 = "We place on record our appreciation of your contribution and look forward to your continued excellence in performance and progress of Acurus.";
$cont2 = "All other terms and conditions of employment will remain unchanged as per your original letter of appointment.";
class PDF extends FPDF
{
// Page header
function Header()
{
    $this->Ln(5);
   $this->Image('white.jpg',90,6,30);
   $this->Ln(35);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'',0,0,'C');
}
}
// Instanciation of inherited class
date_default_timezone_set('Asia/Kolkata');
$dat =date('d M Y');
$dat2 =date('Ymd_Hi');
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddFont('Calibri','B','Calibri.php');
$pdf->AddPage();
$pdf->SetFont('Calibri','BU',12);
$pdf->Cell(70);
$pdf->Cell(20,10,'CONFIRMATION LETTER',0,1);
$pdf->SetFont('Calibri','B',11);
$pdf->Cell(160);
$pdf->Cell(20,10,$date_top,0,1);

$pdf->Ln(10);
$pdf->Cell(23);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->SetFont('Calibri','',11);
$pdf->Cell(20,10,'Dear '.$name.', ',0,1);
$pdf->Cell(23);

$pdf->Cell(20,10,'Employee ID     :');
$pdf->Cell(7);
$pdf->Cell(1,10,$employeeID.' ',0,1);
$pdf->Cell(23);

$pdf->Cell(20,10,'Designation      : ');
$pdf->Cell(7);
$pdf->Cell(12,10,ltrim($degn).' ',0,1);

$pdf->Cell(23);
$pdf->Cell(20,10,'Date of Joining : ');
$pdf->Cell(7);
$pdf->Cell(12,10,$doj.' ');


$pdf->Ln(15);
$pdf->Cell(23);

$pdf->Cell(20,10,'Congratulations!',0,1);
$pdf->Ln(5);
$pdf->Cell(23);
$pdf->MultiCell(0,5,$cont,0,1);
$pdf->Ln(3);
$pdf->Cell(23);
$pdf->MultiCell(0,5,$cont1,0,1);
$pdf->Ln(3);
$pdf->Cell(23);
$pdf->MultiCell(0,5,$cont2,0,1);
$pdf->Ln(3);
$pdf->Cell(23);
$pdf->Cell(20,10,'Kindly acknowledge receipt.',0,1);
$pdf->Ln(10);
$pdf->Cell(23);
$pdf->Cell(20,10,'For Acurus Solutions Private Limited,',0,1);
$pdf->Ln(10);

$pdf->Cell(23);
$pdf->Cell(20,10,$Signeename,0,0);
$pdf->Ln(4);
$pdf->Cell(23);
$pdf->Cell(20,10,$SignAuth,0,0);
$pdf->Output('D',$dat2.'_CONFIRMATION_LETTER_'.$employeeID.'.pdf');

header("Location: BoardingHome.php");
?>