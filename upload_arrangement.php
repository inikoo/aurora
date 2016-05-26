<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 11:58:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/object_functions.php';

require_once 'conf/object_fields.php';
include_once 'utils/invalid_messages.php';

require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once 'external_libs/PHPExcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php';


$creator='Aurora.systems';
$title=_('Upload field arrangements');
$subject='';
$description='';
$keywords='';
$category='';

$output_type='xls';

if (!isset($_REQUEST['object']))exit();
$object=get_object($_REQUEST['object'],0);


switch ($object->get_object_name()) {
case 'Staff':
	$filename=_('upload_employees');
	$options=array();
	break;
case 'Part':

	$filename=_('upload_part');
	$options=array('new'=>true,'part_scope'=>true);
	break;		
case 'Supplier Part':

	$filename=_('upload_supplier_part');
	$supplier=get_object('Supplier',$_REQUEST['parent_key']);
	$options=array('supplier'=>$supplier,'new'=>true,'supplier_part_scope'=>true);
	break;	
default:
	exit('Object not defined '.$object->get_object_name());
	break;
}

if (!$object_fields=get_object_fields($object, $db, $user, $smarty, $options)) {
	exit("Error. can't get object fields");
}

//print_r($object_fields);



$objPHPExcel = new PHPExcel();
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );






$objPHPExcel->getProperties()->setCreator($creator)
->setLastModifiedBy($creator)
->setTitle($title)
->setSubject($subject)
->setDescription($description)
->setKeywords($keywords)
->setCategory($category);


$row=array('axxx'=>'a1', 'bxx'=>'b1');





$row_index=1;
$char_index=1;
foreach ($object_fields as $field_group) {
	if (array_key_exists('fields', $field_group)) {
		foreach ($field_group['fields'] as $field) {

			if (array_key_exists('edit', $field)  and !array_key_exists('hidden', $field)   and !( array_key_exists('render', $field)  and $field['render']==false)   ) {

				$char=number2alpha($char_index);
				$objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($field['label']));

				if (!array_key_exists('required', $field)  or  $field['required'] )
					$objPHPExcel->getActiveSheet()->getStyle($char . $row_index)->applyFromArray(
						array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'e5edf5')
							)
						)
					);

				$char_index++;
			}

		}
	}
}

$download_path='server_files/tmp/';

switch ($output_type) {

case('csv'):
	$output_file=$download_path.$filename.'.'.$output_type;
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

	$output_file=$download_path.$filename.'.'.$output_type;

	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	//header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')
	->setSheetIndex(0)
	->save($output_file);
	break;
case('xls'):
	$output_file=$download_path.$filename.'.'.$output_type;
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')
	->save('php://output');
	break;


}


?>
