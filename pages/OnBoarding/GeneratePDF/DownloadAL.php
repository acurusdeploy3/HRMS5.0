<?php
require('fpdf.php');
session_start();
$employeeID = $_GET['id'];
$effectivedate = $_GET['date'];
$date= date_create($effectivedate);
$dateformatted = date_format($date,"jS F, Y");
include('config.php');

$getDetails = mysqli_query($db,"SELECT employee_id,concat(first_name,' ',last_name,' ',MI) as name,date_format(date_joined,'%D %b %Y') as date_of_joining
,date_format(services_effective_date,'%D %b, %Y') as services_effective_date,
permanent_address_line_1,permanent_address_line_2,permanent_address_line_3,permanent_street,permanent_city,permanent_state,
permanent_address_zip,gender,marital_status from employee_details where employee_id=$employeeID and is_active='Y';");
$getDetailsRow = mysqli_fetch_array($getDetails);
$name = $getDetailsRow['name'];
$doj = $getDetailsRow['date_of_joining'];
$dos = $getDetailsRow['services_effective_date'];
$getDoc = mysqli_query($db,"select document_type,document_number from kye_Details where employee_id=$employeeID and is_Active='Y' and document_type like '%ADHAAR%'");
$getDocRow = mysqli_fetch_array($getDoc);
$AadhaarNum = $getDocRow['document_number'];
if($getDetailsRow['gender']=='Male')
{
	$getFatherName = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee ='Father' and employee_id=$employeeID and is_active='Y';");
	$getFatherNameRow = mysqli_fetch_array($getFatherName);
	$relation = 'S/O '.$getFatherNameRow['family_member_name'];
}
else
{
	if($getDetailsRow['marital_status']=='Married')
	{
		$getFatherName = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee ='Husband' and employee_id=$employeeID and is_active='Y';");
		$getFatherNameRow = mysqli_fetch_array($getFatherName);
		$relation = 'W/O '.$getFatherNameRow['family_member_name'];
	}
	else
	{
		$getFatherName = mysqli_query($db,"select family_member_name from employee_family_particulars where relationship_with_employee ='Father' and employee_id=$employeeID and is_active='Y';");
		$getFatherNameRow = mysqli_fetch_array($getFatherName);
		$relation = 'D/O '.$getFatherNameRow['family_member_name'];
	}
}
$getDesignation = mysqli_query($db,"select concat(Band,' ',designation,' ',level) as designation from resource_management_table where employee_id=$employeeID and is_active='Y'");

$getDesignationRow = mysqli_fetch_array($getDesignation);
$degn = $getDesignationRow['designation'];
$getSignAuth = mysqli_query($db,"select employee_designation from employee_details where employee_id in (select value from application_configuration where config_type='AL_SIGNING_AUTHORITY')");
$getSignAuthRow = mysqli_fetch_array($getSignAuth);
$SignAuth = $getSignAuthRow['employee_designation'];

$cont = "Welcome to Acurus. We are very selective in whom we bring in as members of this Organization and you were chosen from among several hundred candidates based on your performance in the interview and your commitment to apply your knowledge to deliver results.";
$cont1 = "Acurus is a very entrepreneurial Company and with it brings its own challenges and rewards, where you shall have a lot of opportunity to showcase your talent and at the same time the Company looks forward from its members to have a 'Can and Will Do' attitude. ";
$cont2 = "Acurus environment is what each one of us creates. We are hopeful that you will contribute to our culture, the one of hard work, results oriented, professional and yet a fun place to work. We look forward to working with you and are positive that you will be one of the key contributors to Acurus's growth, which in turn would also blossom your career in Acurus.";
$cont3 = "At Acurus, we are dealing with confidential patient data. This requires that all employees to sign a non-disclosure and confidentiality undertaking. As U.S. and/or Indian laws change, we may require you to sign other required documents.";
class PDF extends FPDF
{
// Page header
function Header()
{
	$this->Ln(5);
    $this->Image('white.jpg',90,6,30);
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
$pdf->Cell(160);
$pdf->Cell(20,10,$dat,0,1);
$pdf->Cell(20,10,'To',0,1);
$pdf->Cell(20,7,$name.',',0,1);
$pdf->Cell(20,7,$relation.',',0,1);
$pdf->Cell(20,7,$getDetailsRow['permanent_address_line_2'].' '.$getDetailsRow['permanent_address_line_3'].' '.$getDetailsRow['permanent_address_line_1'].',',0,1);
$pdf->Cell(20,7,$getDetailsRow['permanent_street'].', ',0,1);
$pdf->Cell(20,7,$getDetailsRow['permanent_city'].', '.$getDetailsRow['permanent_state'].' - '.$getDetailsRow['permanent_address_zip'].'.',0,1);
$pdf->Cell(20,7,'Aadhaar : '.$AadhaarNum,0,1);
$pdf->Ln(7);
$pdf->Cell(20,10,'Dear '.$name.',',0,1);
$pdf->Ln(5);
$pdf->SetFont('Times','',12);
$pdf->MultiCell(0,7,$cont,0,1);
$pdf->Ln(3);
$pdf->MultiCell(0,7,$cont1,0,1);
$pdf->Ln(3);
$pdf->MultiCell(0,7,$cont2,0,1);
$pdf->Ln(3);
$pdf->MultiCell(0,7,$con3,0,1);
$pdf->Ln(2);
$pdf->Cell(20,5,"Your job title will be '".$degn."'.",0,1);
$pdf->Ln(3);
$pdf->Cell(20,8,"Your start date at Acurus shall be from  ".$getDetailsRow['date_of_joining'].".",0,1);
$pdf->Ln(3);
$pdf->MultiCell(0,8,'Let us make Acurus the preferred employer for other bright, young talented individuals like you.',0,1);
$pdf->Ln(15);
$pdf->Cell(20,10,'For Acurus Solutions Private Limited,',0,1);
$pdf->Ln(25);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,$SignAuth.'.',0,0);
$pdf->Output('D',$dat2.'_APPOINTMENT_LETTER_'.$employeeID.'.pdf');

header("Location: BoardingHome.php");
?>