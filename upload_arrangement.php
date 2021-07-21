<?php /** @noinspection DuplicatedCode */

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 11:58:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;

require_once 'common.php';
require_once 'utils/object_functions.php';
include_once 'utils/get_export_edit_template_fields.php';



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

$key_field='';
$valid_fields='';

switch ($object->get_object_name()) {
    case 'Staff':
        $filename = 'new_employees';
        $options  = array();
        break;
    case 'Part':
        $filename = 'new_parts';
        $options  = array(
            'new'        => true,
            'part_scope' => true
        );
        break;
    case 'Prospect':
        $filename = 'new_prospects';
        $options  = array();
        $valid_fields = get_export_edit_template_fields('prospect');
        $key_field    = 'Id: Prospect Key';
        break;
    case 'Supplier Part':
        $filename = 'new_supplier_parts';
        $valid_fields = get_export_edit_template_fields('supplier_part');
        $key_field    = 'Id: Supplier Part Key';
        break;
    case 'Location':
        $filename     = 'new_locations';
        $valid_fields = get_export_edit_template_fields('location');
        if (isset($_REQUEST['parent']) and $_REQUEST['parent'] == 'warehouse_area') {
            unset($valid_fields[1]);
        }
        $key_field = 'Id: Location Key';
        break;
    case 'Warehouse Area':
        $filename     = 'new_warehouse_area';
        $valid_fields = get_export_edit_template_fields('warehouse_area');
        $key_field    = 'Id: Warehouse Area Key';
        break;
    case 'Fulfilment Asset':
        $filename     = 'fulfilment_delivery';
        $valid_fields = get_export_edit_template_fields('fulfilment_asset');
        $key_field    = 'Id: Fulfilment Asset Key';
        break;
    default:
        exit('Object not defined '.$object->get_object_name());

}




$objPHPExcel = new Spreadsheet();
Cell::setValueBinder(new AdvancedValueBinder());


$objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory($category);


$row_index  = 1;
$char_index = 1;

$char = number2alpha($char_index);
$objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, strip_tags($key_field));
$objPHPExcel->getActiveSheet()->getStyle($char.$row_index)->applyFromArray(
    array(
        'borders' => array(
            'bottom' => array(
                'style' => Border::BORDER_THIN,
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
                        'style' => Border::BORDER_THIN,
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
/** @var \PhpOffice\PhpSpreadsheet\Cell\Cell $cell */
foreach ($cellIterator as $cell) {
    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
}
$download_path = 'server_files/tmp/';

try {
    $objPHPExcel->getActiveSheet()->freezePane('A2');

    switch ($output_type) {



        case('xls'):
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls')->save('php://output');
            break;


    }


} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
}






