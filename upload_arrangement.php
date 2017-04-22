<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 11:58:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/
require_once 'common.php';
require_once 'utils/object_functions.php';

require_once 'conf/export_edit_template_fields.php';

require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once 'external_libs/PHPExcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php';


$creator     = 'Aurora.systems';
$title       = _('Upload field arrangements');
$subject     = '';
$description = '';
$keywords    = '';
$category    = '';

$output_type = 'xls';

if (!isset($_REQUEST['object'])) {
    exit();
}
$object = get_object($_REQUEST['object'], 0);


switch ($object->get_object_name()) {
    case 'Staff':
        $filename = _('new_employees');
        $options  = array();
        break;
    case 'Part':
        $filename = _('new_parts');
        $options  = array(
            'new'        => true,
            'part_scope' => true
        );
        break;
    case 'Supplier Part':

        $filename = _('new_supplier_parts');


        $valid_fields = $export_edit_template_fields['supplier_part'];
        $key_field    = 'Id: Supplier Part Key';
        // $supplier=get_object('Supplier',$_REQUEST['parent_key']);
        // $options=array('parent'=>'supplier','parent_object'=>$supplier,'new'=>true,'supplier_part_scope'=>true);
        break;
    case 'Location':
        $filename = _('new_locations');
        $valid_fields = $export_edit_template_fields['location'];

        $key_field    = 'Id: Location Key';
        break;
    default:
        exit('Object not defined '.$object->get_object_name());
        break;
}

//if (!$object_fields=get_object_fields($object, $db, $user, $smarty, $options)) {
// exit("Error. can't get object fields");
//}

//print_r($object_fields);


$objPHPExcel = new PHPExcel();
PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());


$objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory($category);


$row_index  = 1;
$char_index = 1;

$char = number2alpha($char_index);
$objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, strip_tags($key_field));
$objPHPExcel->getActiveSheet()->getStyle($char.$row_index)->applyFromArray(
    array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '777777')
            )
        )
    )
);
$char_index++;

foreach ($valid_fields as $field) {


    if ($field['show_for_new']) {


        $char = number2alpha($char_index);
        $objPHPExcel->getActiveSheet()->setCellValue(
            $char.$row_index, strip_tags($field['header'])
        );

        if ($field['required']) {
            $objPHPExcel->getActiveSheet()->getStyle($char.$row_index)->applyFromArray(
                array(
                    'font' => array(
                        'color' => array('rgb' => 'EA3C53'),

                    )

                )
            );
        }
        $objPHPExcel->getActiveSheet()->getStyle($char.$row_index)->applyFromArray(
            array(
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '777777')
                    )
                )
            )
        );


        $char_index++;
    }

}


$row_index++;
$char_index = 1;
$char       = number2alpha($char_index);
$objPHPExcel->getActiveSheet()->setCellValue(
    $char.$row_index, strip_tags('NEW')
);

$char_index++;

foreach ($valid_fields as $field) {


    if ($field['show_for_new']) {


        $char = number2alpha($char_index);
        $objPHPExcel->getActiveSheet()->setCellValue(
            $char.$row_index, strip_tags($field['default_value'])
        );

        if ($field['required']) {
            $objPHPExcel->getActiveSheet()->getStyle($char.$row_index);
        }
        $char_index++;
    }

}

$sheet        = $objPHPExcel->getActiveSheet();
$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(true);
/** @var PHPExcel_Cell $cell */
foreach ($cellIterator as $cell) {
    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->freezePane('A2');

$download_path = 'server_files/tmp/';


switch ($output_type) {

    case('csv'):
        $output_file = $download_path.$filename.'.'.$output_type;
        // header('Content-Type: text/csv');
        // header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        // header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')->setEnclosure('')->setLineEnding("\r\n")->setSheetIndex(0)->save($output_file);
        break;
    case('xlsx'):

        $output_file = $download_path.$filename.'.'.$output_type;

        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        //header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'EXCEL2007')->setSheetIndex(0)->save($output_file);
        break;
    case('xls'):
        $output_file = $download_path.$filename.'.'.$output_type;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')->save('php://output');
        break;


}


?>
