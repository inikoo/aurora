<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo
require_once 'aes.php';
require_once 'common_functions.php';
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;


$count_number_used=0;

$worker= new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction("export", "my_export");
while ($worker->work()){

 if ($worker->returnCode() == GEARMAN_SUCCESS)
  {
     exec("kill -9 ". getmypid());
    die();
  }

}







function my_export($job) {

	global $count_number_used;

	if ($count_number_used>3) {
		exit();
	}

	if (!$_data=get_fork_data($job))
		return;

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];
	$inikoo_account_code=$_data['inikoo_account_code'];

	$output_type=$fork_data['output'];
	$sql_count=$fork_data['sql_count'];
	$sql_data=$fork_data['sql_data'];
	
	$creator='Inikoo';
	$title=_('Report');
	$subject=_('Report');
	$description='';
	$keywords='';
	$category='';
	$filename='output';





	$output_filename='export_'.$inikoo_account_code.'_'.$fork_key.'_'.$fork_data['table'];




	
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


	//print $sql_data;

	$res=mysql_query($sql_data);
	while ($row=mysql_fetch_assoc($res)) {
		$char_index=1;
		foreach ($row as $value) {
			$char=number2alpha($char_index);
			//print "$char  $row_index  $value \n";




			$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index,strip_tags($value));


			$char_index++;
		}



		$row_index++;

		if ($row_index % 100 == 0) {
			$sql=sprintf("update `Fork Dimension` set `Fork Operations Done`=%d  where `Fork Key`=%d ",
				($row_index-2),
				$fork_key
			);
			//print "$sql\n";
			mysql_query($sql);
		}
	}



	switch ($output_type) {

	case('csv'):
		$output_file="gearman/downloads_$inikoo_account_code/".$output_filename.'.'.$output_type;
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

		$output_file="gearman/downloads_$inikoo_account_code/".$output_filename.'.'.$output_type;

		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
		->setSheetIndex(0)
		->save($output_file);
		break;
	case('xls'):
		$output_file="gearman/downloads_$inikoo_account_code/".$output_filename.'.'.$output_type;
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
		->save($output_file);
		break;
	case('pdf'):
		$output_file="gearman/downloads_$inikoo_account_code/".$output_filename.'.'.$output_type;

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
	$count_number_used++;
	return false;

}



function get_fork_data($job) {

	$fork_raw_data=$job->workload();
	$fork_metadata=unserialize($fork_raw_data);
	$salt=$fork_metadata['salt'];
	$inikoo_account_code=$fork_metadata['code'];
	include "gearman/conf/dns.$inikoo_account_code.php";

	$encrypt_key=$fork_encrypt_key.$salt;
	$decrypted_data= base64_decode(AESDecryptCtr($fork_metadata['endata'],$encrypt_key,256));

	$secret_data=unserialize($fork_metadata['data']);
	$secret_data=unserialize($decrypted_data);

	$fork_key=$secret_data['fork_key'];
	$token=$secret_data['token'];
	$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
	if (!$default_DB_link) {
		print "Error can not connect with database server\n";
		return false;
	}
	$db_selected=mysql_select_db($dns_db, $default_DB_link);
	if (!$db_selected) {
		print "Error can not access the database\n";
		return false;
	}

	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET time_zone='+0:00'");
	$sql=sprintf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d and `Fork Token`=%s",
		$fork_key,
		prepare_mysql($token)
	);


	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$fork_data=unserialize($row['Fork Process Data']);
		return array('fork_key'=>$fork_key,'inikoo_account_code'=>$inikoo_account_code,'fork_data'=>$fork_data);
	}else {

		print "fork data not found";
		return false;
	}

}

?>
