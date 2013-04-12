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
$worker->addFunction("export", "my_export");
while ($worker->work());




function my_export($job) {



	$fork_key=$job->workload();


	$sql=sprintf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d",$fork_key);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$fork_data=unserialize($row['Fork Process Data']);

	}else {
		print "Error no fork data\n";
		exit;
	}




	//$ar_file=$fork_data['ar_file'];
	$output_type=$fork_data['output'];

	$creator='Inikoo';
	$title=_('Report');
	$subject=_('Report');
	$description='';
	$keywords='';
	$category='';
	$filename='output';





	$output_filename='export_'.$fork_key.'_'.$fork_data['request']['table'];




	list ($sql_count,$sql_data)=get_sql_query($fork_data['request']);

	$res=mysql_query($sql_count);
	$number_rows=0;
	if ($row=mysql_fetch_assoc($res)) {
		$number_rows=$row['num'];
	}


	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$number_rows,
		$fork_key
	);
	mysql_query($sql);



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

	$res=mysql_query($sql_data);
	while ($row=mysql_fetch_assoc($res)) {
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
	$output_file='app_files/downloads/'.$output_filename.'.'.$output_type;
		// header('Content-Type: text/csv');
		// header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		// header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')
		->setDelimiter(',')
		->setEnclosure('')
		->setLineEnding("\r\n")
		->setSheetIndex(0)
		->save($output_file);
		break;
	case('xlsx'):

	$output_file='app_files/downloads/'.$output_filename.'.'.$output_type;

		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
		->setSheetIndex(0)
		->save($output_file);
		break;
	case('xls'):
	$output_file='app_files/downloads/'.$output_filename.'.'.$output_type;
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
		->save($output_file);
		break;
	case('pdf'):
		$output_file='app_files/downloads/'.$output_filename.'.'.$output_type;

		//header('Content-Type: application/pdf');
		//header('Content-Disposition: attachment;filename="'.$filename.'.pdf"');
		//header('Cache-Control: max-age=0');
		$objPHPExcel->getActiveSheet()->setShowGridLines(false);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF')
		->save($output_file);
		break;

	}


	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s where `Fork Key`=%d ",
		($row_index-2),
		prepare_mysql($output_file),
		$fork_key
	);
	//print $sql;
	mysql_query($sql);



}

function get_sql_query($data) {
//print_r($data);

	switch ($data['table']) {
	case 'customers':
		return customers_sql_query($data);
		break;
	default:
		return false;
	}
}

function customers_sql_query($data) {

	//print_r($data);

	$where=' where ';
	switch ($data['parent']) {
	case 'store':
		$where.=sprintf('`Customer Store Key`=%d',$data['parent_key']);
		break;
	default;
		$where.='false';
	}
	$sql_count=sprintf("select count(*) as num from `Customer Dimension` %s ",$where);


	$sql_data=sprintf("select `Customer Key`,`Customer Name`,`Customer Main Plain Email` from `Customer Dimension` %s ",$where);

	return array($sql_count,$sql_data);
}

?>
