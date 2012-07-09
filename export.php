<?php

if (!isset($_REQUEST['ar_file'])) {exit;}

if (!isset($_REQUEST['output'])) {
	$output_type='csv';
}else
	$output_type=strtolower($_REQUEST['output']);


$creator='Inikoo';
$title=_('Report');
$subject=_('Report');
$description='';
$keywords='';
$category='';
$filename='output';

include_once $_REQUEST['ar_file'].'.php';
$data=$results['resultset']['data'];

require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator($creator)
->setLastModifiedBy($creator)
->setTitle($title)
->setSubject($subject)
->setDescription($description)
->setKeywords($keywords)
->setCategory($category);


$row_index=2;
foreach ($data as $row) {
	$char_index=1;
	foreach ($row as $value) {
		$char=number2alpha($char_index);
		$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index,strip_tags($value));


		$char_index++;
	}
	$row_index++;
}





switch ($output_type) {

case('csv'):
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')
	->setDelimiter(',')
	->setEnclosure('')
	->setLineEnding("\r\n")
	->setSheetIndex(0)
	->save('php://output');
	break;
case('xlsx'):
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
	->setSheetIndex(0)
	->save('php://output');
	break;
case('xls'):
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
	->save('php://output');
	break;
case('pdf'):
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment;filename="'.$filename.'.pdf"');
	header('Cache-Control: max-age=0');
	$objPHPExcel->getActiveSheet()->setShowGridLines(false);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF')
	->save('php://output');
	break;

}


?>
