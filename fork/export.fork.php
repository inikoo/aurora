<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 14:11:21 GMT+8, Kuala Lumpur, Malysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/


function fork_export($job) {

 

	if (!$_data=get_fork_data($job))
		return;

	$db=$_data['db'];
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

	//print $sql_count;

	$number_rows=0;


	if ($result=$db->query($sql_count)) {
		if ($row = $result->fetch()) {
			$number_rows=$row['num'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}






	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$number_rows,
		$fork_key
	);
	$db->exec($sql);



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



	$row_index=1;


	if ($result=$db->query($sql_data)) {
		foreach ($result as $row) {


			if ($row_index==1) {
				$char_index=1;
				foreach ($row as $_key=>$value) {
					$char=number2alpha($char_index);
					$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($_key));
					$char_index++;
				}
				$row_index++;
			}


			$char_index=1;
			foreach ($row as $value) {
				$char=number2alpha($char_index);
				$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($value));
				$char_index++;
			}
			$row_index++;
			if ($row_index % 100 == 0) {
				$sql=sprintf("update `Fork Dimension` set `Fork Operations Done`=%d  where `Fork Key`=%d ",
					($row_index-2),
					$fork_key
				);
				$db->exec($sql);
			}

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	


	if (isset($_data['fork_data']['download_path'])) {
		$download_path=$_data['fork_data']['download_path']."_$inikoo_account_code/";
	}else {
		$download_path="downloads_$inikoo_account_code/";
	}



	switch ($output_type) {

	case('csv'):
		$output_file=$download_path.$output_filename.'.'.$output_type;
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

		$output_file=$download_path.$output_filename.'.'.$output_type;

		//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
		->setSheetIndex(0)
		->save($output_file);
		break;
	case('xls'):
		$output_file=$download_path.$output_filename.'.'.$output_type;
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		//header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
		->save($output_file);
		break;
	case('pdf'):
		$output_file=$download_path.$output_filename.'.'.$output_type;

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
		prepare_mysql('downloads/'.$output_filename.'.'.$output_type),
		$fork_key
	);

	$db->exec($sql);

	return false;
}


?>
