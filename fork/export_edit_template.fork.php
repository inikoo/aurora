<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 14:05:04 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


function fork_export_edit_template($job) {

	include_once 'conf/export_edit_template_fields.php';


	if (!$_data=get_fork_data($job))
		return;


	//print_r($_data);

	$db=$_data['db'];
	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];
	$inikoo_account_code=$_data['inikoo_account_code'];

	$output_type=$fork_data['output'];

	$user_key=$fork_data['user_key'];

	$parent=$fork_data['parent'];
	$parent_key=$fork_data['parent_key'];
	$parent_code=$fork_data['parent_code'];
	$objects=$fork_data['objects'];
	$field_keys=$fork_data['fields'];

	$creator='aurora.systems';
	$title=_('Report');
	$subject=_('Report');
	$description='';
	$keywords='';
	$category='';
	$filename='output';

	$output_filename='edit_'.$inikoo_account_code.'_'.$fork_key.'_'.$parent_code.'_'.$objects;
	$output_filename=preg_replace('/\s+/', '', $output_filename);

	$number_rows=0;



	switch ($objects) {
	case 'supplier_parts':
		include_once 'class.SupplierPart.php';
		$object_id_name='Id: Supplier Part Key';
		$download_type='edit_supplier_parts';
		switch ($parent) {
		case 'supplier':

			$sql_count=sprintf('select count(*) as num from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d', $parent_key);
			$sql_data=sprintf('select `Supplier Part Key` as id from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d', $parent_key);

			break;
		default:

			break;
		}


		break;
	default:

		break;
	}




	if ($result=$db->query($sql_count)) {
		if ($row = $result->fetch()) {
			$number_rows=$row['num'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}






	$fields=array();
	foreach ($field_keys as $field_key) {
		$fields[]=$export_edit_template_fields[$objects][$field_key];
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
			//print_r($row);
			switch ($objects) {
			case 'supplier_parts':
				$object=new SupplierPart($row['id']);
				$object->get_supplier_data();


				$data_rows=array();

				$data_rows[]=array(
					'cell_type'=>'auto',
					'value'=>$object->id
				);

				foreach ($fields as $field) {
					//print_r($object);
					//print $field['name'];
					$data_rows[]=array(
						'cell_type'=>(isset($field['cell_type'])?$field['cell_type']:'auto'),
						'value'=>$object->get($field['name']),
						'field'=>$field['name']
					);
				}

				break;
			default:

				break;
			}

			print_r($data_rows);

			if ($row_index==1) {
				$char_index=1;

				$char=number2alpha($char_index);
				$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, $object_id_name);
				$char_index++;

				foreach ($fields as $field) {

					$char=number2alpha($char_index);


					$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($field['field']));



					$char_index++;
				}



				$row_index++;
			}


			$char_index=1;
			foreach ($data_rows as $data_row) {
				$char=number2alpha($char_index);


				if ($data_row['cell_type']=='string') {

					$objPHPExcel->getActiveSheet()->setCellValueExplicit($char . $row_index, strip_tags($data_row['value']), PHPExcel_Cell_DataType::TYPE_STRING);
				}else {
					$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($data_row['value']));

				}




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



	/*
	if (isset($_data['fork_data']['download_path'])) {
		$download_path=$_data['fork_data']['download_path']."_$inikoo_account_code/";
	}else {
		$download_path="downloads_$inikoo_account_code/";
	}
*/
	$download_path='tmp/';

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


	$sql=sprintf("insert into `Download Dimension` (`Download Date`,`Download Type`,`Download Filename`,`Download User Key`,`Download Fork Key`,`Download Data`) values (%s,%s,%s,%d,%d,%s) ",
		prepare_mysql(gmdate('Y-m-d H:i:s')),
		prepare_mysql($download_type),
		prepare_mysql($output_filename.'.'.$output_type),
		$user_key,
		$fork_key,
		prepare_mysql(file_get_contents($output_file))
	);

	$db->exec($sql);

	$download_id=$db->lastInsertId();


	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s where `Fork Key`=%d ",
		($row_index-2),
		prepare_mysql($download_id),
		$fork_key
	);

	$db->exec($sql);

	return false;
}


?>
