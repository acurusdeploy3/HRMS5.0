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


$result = mysqli_query($db,"select concat(e.first_name,' ',e.mi,' ',e.last_name) as employee_name,e.employee_id,concat(e1.first_name,' ',e1.mi,' ',e1.last_name) as manager_name,
e.department,date_format(d.date,'%d %b %Y') as `dt`,d.shift_code,concat(TIME_FORMAT(sh.start_time, '%h %p'),' - ',TIME_FORMAT(sh.end_time, '%h %p')) as Shift_time,group_concat(distinct if(manager_comments='',null,manager_comments) separator '\n') as mngr_comm
from dsr_summary d
left join employee_details e on d.employee_id=e.employee_id
left join employee_details e1 on d.manager_id=e1.employee_id
left join employee_shift s on d.employee_id=s.employee_id
left join shift sh on d.shift_code =sh.shift_code
where date='$dateval' and date between start_date and end_date and d.manager_id=$name
group by employee_id");


$numrows = mysqli_num_rows($result);

$numrows=$numrows+2;

require_once dirname(__FILE__) . '/PHPExcel-1.8/Classes/PHPExcel.php';
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


$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->SetCellValue('A1',$department.' - Daily Status Report'); 
	
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
$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, 'A3:H'.$numrows);

$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Name'); 
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Employee ID'); 
$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Reporting Superior'); 
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Department'); 
$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Date'); 
$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Shift-Code'); 
$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Shift-Time'); 
$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Daily work progress');


$objPHPExcel->getActiveSheet()->getStyle('G1:G999')
    ->getAlignment()->setWrapText(true); 

$rowCount = 3; 
// Iterate through each result from the SQL query in turn
// We fetch each database result row into $row in turn

while($row = mysqli_fetch_array($result)){ 
    // Set cell An to the "name" column from the database (assuming you have a column called name)
    //    where n is the Excel row number (ie cell A1 in the first row)
	
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['employee_name']); 
    // Set cell Bn to the "age" column from the database (assuming you have a column called age)
    //    where n is the Excel row number (ie cell A1 in the first row)
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['employee_id']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['manager_name']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['department']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['dt']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['shift_code']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['Shift_time']); 
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['mngr_comm']); 
    // Increment the Excel row counter
    $rowCount++; 
} 

if (!file_exists('D:/DSR_DOWNLOADS/DSR_FILES')) {
    mkdir('D:/DSR_DOWNLOADS/DSR_FILES', 0777, true);
}

// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 


// Write the Excel file to filename some_excel_file.xlsx in the current directory
//$objWriter->save('DSR_'.$date1.'_'.$name.'.xlsx');
$objWriter->save(str_replace(__FILE__,'D:\DSR_DOWNLOADS\DSR_FILES\DSR_'.$date1.'_'.$department.'_'.$SenderNam.'&Team.xlsx',__FILE__));


header("Location: sendemailManger.php?dateval=$dateval");
exit;
?>

