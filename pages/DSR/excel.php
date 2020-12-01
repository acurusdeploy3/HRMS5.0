<?php
//require_once("queries.php");
session_start();
$name = $_SESSION['login_user'];


echo(date_default_timezone_set('Asia/Calcutta'));

$dateval = $_GET['dateval'];


include('config.php');

$deptdetails = mysqli_query($db,"select concat(First_Name,'',MI,' ',Last_Name) as Name,department from  employee_details  where employee_id=$name");
$deptdetailsrow = mysqli_fetch_array($deptdetails);
$department = $deptdetailsrow['department'];
$SenderNam = $deptdetailsrow['Name'];

$getdate = mysqli_query($db,"select date_format('$dateval','%Y%m%d') as date1");
$getdaterow = mysqli_fetch_array($getdate);
$date1 = $getdaterow['date1'];


$result = mysqli_query($db,"select  e.employee_id,concat(e.first_name,' ',e.mi,' ',e.last_name) as employee_name,e.department,concat(e1.first_name,' ',e1.mi,' ',e1.last_name) as manager_name,
date_format(d.date,'%d %b %Y') as `dt`,s.shift_code,' - ',group_concat(distinct if(manager_comments<>'',manager_comments,(if(tl_comments='',employee_comments,tl_comments))) separator '\n') as mngr_comm,status,
is_submitted,is_sent,is_approved,is_expired from dsr_summary as d
left join dsr_comments c on d.dsr_summary_id=c.summary_id
left join employee_details e on d.employee_id=e.employee_id
left join employee_details e1 on e.reporting_manager_id=e1.employee_id
left join employee_shift s on d.employee_id=s.employee_id
where date='$dateval'  and e.is_active='Y' and '$dateval' between start_date and end_date and e.reporting_manager_id not in(0)
group by employee_id  ORDER BY e.employee_id ASC");


$numrows = mysqli_num_rows($result);

$numrows=$numrows+2;


require_once dirname(__FILE__) . '/PHPExcel-1.8.1/Classes/PHPExcel.php';
// Instantiate a new PHPExcel object
$objPHPExcel = new PHPExcel(); 
$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle3 = new PHPExcel_Style();
// Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0); 



// Initialise the Excel row number
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1','Employee Status Report For - '.$dateval); 
	
$sharedStyle1->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'DDDDDDD')
							),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
		 'font' => array(
				'bold' => true
			),
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
							)
		 ));
$sharedStyle2->applyFromArray(
	array('fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('argb' => 'DDDDDDD')
							),
		 'font' => array(
				'bold' => true,
				'color' => array('argb' => '666699')
			),
		 'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
							)
		 ));
		 
$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A1:H1");
$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "A2:H2");

$sharedStyle3->applyFromArray(
	array(
		  'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
							)
		 ));
$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, 'A3:I'.$numrows);

$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Employee_ID'); 
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name');  
$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Department'); 
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Reporting Superior'); 
$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Date'); 
$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Shift-Code'); 
$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Daily work progress');
$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Status');


$objPHPExcel->getActiveSheet()->getStyle('G1:G999')
    ->getAlignment()->setWrapText(true); 

$rowCount = 3; 
// Iterate through each result from the SQL query in turn
// We fetch each database result row into $row in turn
$status='';
while($row = mysqli_fetch_array($result)){ 
    // Set cell An to the "name" column from the database (assuming you have a column called name)
    //    where n is the Excel row number (ie cell A1 in the first row)
	                                  if($row['is_submitted']=='N' && $row['is_expired']=='N'){
										$status = "Pending in employee";
										}
										elseif($row['is_sent']=='N' && $row['is_expired']=='N'){
										$status = "Pending in Tl";
										}
										elseif($row['is_approved']=='N' && $row['is_expired']=='N'){
										$status = "Pending in Manager";
										}
                                       elseif($row['is_submitted']=='N' && $row['is_expired']=='Y'){
										$status = "Expired in employee";
										}
										elseif($row['is_sent']=='N' && $row['is_expired']=='Y'){
										$status = "Expired in Tl";
										}
										elseif($row['is_approved']=='N' && $row['is_expired']=='Y'){
										$status = "Expired in Manager";
										}
                                        else{
										$status = $row['status'];
										}
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['employee_id']);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['employee_name']); 
    // Set cell Bn to the "age" column from the database (assuming you have a column called age)
    //    where n is the Excel row number (ie cell A1 in the first row)
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['department']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['manager_name']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['dt']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['shift_code']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['mngr_comm']);
    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $status );	
    // Increment the Excel row counter
    $rowCount++; 
} 
ob_clean();
header('Content-Transfer-Encoding: binary');
header("Content-Type: application/octet-stream"); 
header("Content-Transfer-Encoding: binary"); 
header('Expires: '.gmdate('D, d M Y H:i:s').' GMT'); 
header("Content-Disposition: attachment; filename=DSR_".$date1.".xlsx");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
ob_end_clean();
$objWriter->save('php://output');
exit;
?>

