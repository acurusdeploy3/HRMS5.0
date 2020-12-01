<?php
require('fpdf.php');
session_start();
$Degn = $_SESSION['DesChange'];
$Mgmr = $_SESSION['RepMgmr'];
$proj =$_SESSION['Proj'];
$dept = $_SESSION['Dept'];
$eff = $_SESSION['effectivefrom'];
$name= $_SESSION['LOAName'];
$id = $_SESSION['EmpId'];
$cont = ' '.$Degn.$dept.$Mgmr.$proj;
class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
	$this->Ln(5);
    $this->Image('acurus-logo.png',90,6,30);
    // Arial bold 15
    $this->SetFont('times','',12);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'Acurus Solutions Private Limited',0,0,'C');
	$this->Ln(10);
	$this->Cell(80);
    // Title
    $this->Cell(30,10,'No. 4/363, C - Block, Behind MAX Showroom, Kandanchavadi, OMR, Chennai - 600096',0,0,'C');
	$this->Ln(5);
	$this->Cell(80);
    // Title
    $this->Cell(30,10,'Phone: +91 44 43053025',0,0,'C');
    // Line break
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
    $this->Cell(0,10,'Acurus Solutions Private Limited',0,0,'C');
}
}

// Instanciation of inherited class
date_default_timezone_set('Asia/Kolkata');
$dat =date('d-M-Y');
$dat2 =date('Ymd_Hi');
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(20,10,$dat,0,1);
$pdf->Cell(20,10,'Dear '.$name.',',0,1);
$pdf->Ln(10);
$pdf->MultiCell(0,10,$cont,0,0);
$pdf->Ln(5);
$pdf->Cell(20,10,'Changes will be effective from '.$eff.'.',0,1);
$pdf->Ln(15);
$pdf->Cell(20,10,'For Acurus Solutions Private Limited,',0,1);
$pdf->Ln(25);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,'VP - HR & Admin',0,0);
$pdf->Cell(60);
$pdf->Cell(20,10,'CEO / CTO',0,0);
$pdf->Cell(50);
$pdf->Cell(20,10,'Employee Signature',0,1);
$pdf->Output('D',$dat2.'_RESOURCE_MGMT_LOA_'.$id.'.pdf');
?>
