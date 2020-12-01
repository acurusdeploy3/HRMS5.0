<?php
require('fpdf.php');
session_start();
$resignationId = $_GET['id'];
include('config.php');
$getDetails = mysqli_query($db,"SELECT a.employee_id,concat(b.first_name,' ',b.last_name,' ',b.MI) as Name,date_format(date_of_leaving,'%d %b %Y') as date_of_leaving,
date_format(b.date_joined,'%d %b %Y') as date_of_joining,gender
 FROM `employee_resignation_information` a

left join employee_details b on a.employee_id = b.employee_id
 where resignation_id=$resignationId");
$getDetailsRow = mysqli_fetch_array($getDetails);
$name = trim($getDetailsRow['Name']);
$employeeid = $getDetailsRow['employee_id'];
$doj = $getDetailsRow['date_of_joining'];
$dol = $getDetailsRow['date_of_leaving'];
$gender = $getDetailsRow['gender'];
$count = strlen($name);
if($gender=='Male')
{
	$title='Mr.';
	$pronoun ='His';	
$pronoun1='his';
}
else
{
	$title='Ms.';
	$pronoun ='Her';
$pronoun1='her';
	}
$getDesignation = mysqli_query($db,"select concat(Band,' ',designation,' ',level) as designation from resource_management_table where employee_id=$employeeid order by created_date_and_time desc");
$getDesignationRow = mysqli_fetch_array($getDesignation);
$degn = $getDesignationRow['designation'];

$getSignAuth = mysqli_query($db,"select concat(first_name,' ',last_name,' ',MI) as Name,employee_designation from employee_details where employee_id in (select value from application_configuration where config_type='AL_SIGNING_AUTHORITY')");
$getSignAuthRow = mysqli_fetch_array($getSignAuth);
$SignAuth = $getSignAuthRow['employee_designation'];
$Signeename = $getSignAuthRow['Name'];
if ($count >=15 && $count<18){	
$cont = "This is to certify that  ".$title." ".$name." (Employee ID: ".$employeeid.") was employed with our Organization \n from  ".$doj." to ".$dol." ";	
}	
elseif ($count>=18){	
$cont = "This is to certify that  ".$title." ".$name." (Employee ID: ".$employeeid.") was employed with our \n Organization from ".$doj." to ".$dol." ";	
}	
else{	
$cont = "This is to certify that  ".$title." ".$name." (Employee ID: ".$employeeid.") was employed with our Organization \n from  ".$doj." to ".$dol." ";	
}
class PDF extends FPDF
{
// Page header
function Header()
{
   $this->Ln(5);
   $this->Image('white.jpg',90,6,30);
   $this->Ln(45);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    // 
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Acurus Solutions Private Limited',0,0,'C');
}
}
ob_start();
date_default_timezone_set('Asia/Kolkata');
$dat = date('d M Y');
$dat2 =date('Ymd_Hi');
$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddFont('Calibri','B','Calibri.php');
$pdf->AddPage();
$pdf->Ln(40);
$pdf->SetFont('Calibri','B',11);
$pdf->Cell(160);
$pdf->Cell(20,10,$dat,0,1);
$pdf->Cell(60);
$pdf->Cell(20,10,'TO WHOMSOEVER IT MAY CONCERN',0,1);
$pdf->Ln(10);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->SetFont('Calibri','',11);
$pdf->Cell(23);
$pdf->MultiCell(0,10,$cont,0,1);
$pdf->Ln(5);
$pdf->Cell(23);
$pdf->Cell(20,10,$pronoun.' last designation during the employment was "'.$degn.'".',0,1);
$pdf->Ln(5);
$pdf->Cell(23);
$pdf->Cell(20,10,"During the course of ".$pronoun1." employment, ".$pronoun1." work has been found satisfactory.",0,1);
$pdf->Ln(5);
$pdf->Cell(23);
$pdf->Cell(20,10,'For Acurus Solutions Private Limited,',0,1);
$pdf->Ln(9);
$pdf->SetFont('Calibri','',11);
$pdf->Cell(23);
$pdf->Cell(20,10,$Signeename,0,0);
$pdf->Ln(6);
$pdf->Cell(23);
$pdf->Cell(20,10,$SignAuth,0,0);
$pdf->Output('D',$dat2.'_RELIEVING_LETTER_'.$employeeid.'.pdf');
ob_end_flush(); 
?>