<?php
use setasign\Fpdi\Fpdi;

require_once('fpdf.php');
require_once('src/autoload.php');
require_once('src/Fpdi.php');
date_default_timezone_set('Asia/Kolkata');
$dat =date('d-m-Y');
$dat2 =date('Ymd_Hi');
$name = $_GET['id'];
// initiate FPDI
$pdf = new FPDI();
// add a page
$pdf->AddPage();
$pdf->setSourceFile("NDA.pdf");
$tplIdx = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tplIdx);
$pdf->AddPage();
$tplIdx1 = $pdf->importPage(2);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx1);
// now write some text above the imported page
$pdf->SetFont('Helvetica');
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(43, 201);
$pdf->Write(0, $name);
$pdf->SetXY(22, 229.5);
$pdf->Write(0, $dat);
$pdf->Output('D',$dat2.'_NDA_'.$name.'.pdf');  