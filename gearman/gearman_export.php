<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo

require_once 'app_files/db/dns.php';
require_once 'common_functions.php';



$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}

mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");

$worker= new GearmanWorker();
$worker->addServer();
$worker->addFunction("export", "gearman_export");





while ($worker->work());

function gearman_export($job) {


$fork_key=$job->workload();


	$sql=sprintf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d",$fork_key);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$fork_data=unserialize($row['Fork Process Data']);

	}else {
		print "Error no fork data\n";
		exit;
	}
	

	

	$ar_file=$fork_data['ar_file'];
	$output_type=$fork_data['output'];

	$creator='Inikoo';
	$title=_('Report');
	$subject=_('Report');
	$description='';
	$keywords='';
	$category='';
	$filename='output';
	
	$_REQUEST=$fork_data['request'];
	
	$output_filename='xxx';
	
	include_once $ar_file.'.php';
	$data=$results['resultset']['data'];

	require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
	require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';

	$objPHPExcel = new PHPExcel();
	require_once 'external_libs/PHPExcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php';
	PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );


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
		// header('Content-Type: text/csv');
		// header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		// header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')
		->setDelimiter(',')
		->setEnclosure('')
		->setLineEnding("\r\n")
		->setSheetIndex(0)
		->save('/tmp/'.$output_filename.'.csv');
		break;
	case('xlsx'):



		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
		->setSheetIndex(0)
		->save('/tmp/'.$output_filename.'.csv');
		break;
	case('xls'):
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
		->save('/tmp/'.$output_filename.'.csv');
		break;
	case('pdf'):
		//header('Content-Type: application/pdf');
		//header('Content-Disposition: attachment;filename="'.$filename.'.pdf"');
		//header('Cache-Control: max-age=0');
		$objPHPExcel->getActiveSheet()->setShowGridLines(false);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF')
		->save('/tmp/'.$output_filename.'.csv');
		break;

	}

}



function get_sql_query($data){
switch($data['tipo']){
	case 'customers':
		return customers_sql_query($data)
	break;
default:
 return false;
}
}

function customers_sql_query(){

}


?>
