<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 14:05:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

function fork_export_edit_template($job) {

    global $account, $db;// remove the global $db and $account is removed

    include_once 'conf/export_edit_template_fields.php';


    if (!$_data = get_fork_metadata($job)) {
        return;
    }

    list($account, $db, $fork_data, $editor, $ES_hosts) = $_data;

    $inikoo_account_code = $account->get('Account Code');

    // print_r($_data);


    $output_type = $fork_data['output'];

    if ($output_type == 'Excel') {
        $output_type = 'xls';
    }


    $user_key = $fork_data['user_key'];

    $parent       = $fork_data['parent'];
    $parent_key   = $fork_data['parent_key'];
    $parent_code  = $fork_data['parent_code'];
    $objects      = $fork_data['objects'];
    $field_keys   = $fork_data['fields'];
    $metadata     = $fork_data['metadata'];
    $download_key = $fork_data['download_key'];


    $creator     = 'aurora.systems';
    $title       = _('Report');
    $subject     = _('Report');
    $description = '';
    $keywords    = '';
    $category    = '';


    $output_filename = 'edit_'.$inikoo_account_code.'_'.$download_key.'_'.$parent_code.'_'.$objects;
    $output_filename = preg_replace('/\s+/', '', $output_filename);


    $sql = sprintf(
        "update `Download Dimension` set `Download State`='In Process',`Download Filename`=%s where `Download Key`=%d  ", prepare_mysql($output_filename.'.'.$output_type), $download_key

    );
    $db->exec($sql);


    $number_rows = 0;


    switch ($objects) {
        case 'supplier_part':
            include_once 'class.SupplierPart.php';
            $object_id_name = 'Id: Supplier Part Key';
            switch ($parent) {
                case 'agent':

                    $sql_count = sprintf('SELECT count(*) AS num FROM `Supplier Part Dimension` SP left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`) WHERE `Agent Supplier Agent Key`=%d', $parent_key);
                    $sql_data  = sprintf('SELECT `Supplier Part Key` AS id FROM `Supplier Part Dimension` SP left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`) WHERE `Agent Supplier Agent Key`=%d', $parent_key);

                    break;
                case 'supplier':
                case 'supplier_production':

                    $sql_count = sprintf('SELECT count(*) AS num FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d', $parent_key);
                    $sql_data  = sprintf('SELECT `Supplier Part Key` AS id FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d', $parent_key);

                    break;
                default:
                    return true;
                    break;
            }
            break;

        case 'part':
            include_once 'class.Part.php';
            $object_id_name = 'Id: Part SKU';
            switch ($parent) {
                case 'category':

                    $sql_count = sprintf(
                        "SELECT count(*) AS num FROM `Category Bridge` WHERE `Subject`='Part' AND  `Category Key`=%d", $parent_key
                    );
                    $sql_data  = sprintf(
                        "SELECT `Subject Key` AS id FROM `Category Bridge` WHERE `Subject`='Part' AND `Category Key`=%d", $parent_key
                    );

                    break;
                default:

                    break;
            }
            break;

        case 'location':
            // include_once 'class.Location.php';
            $object_id_name = 'Id: Location Key';
            switch ($parent) {
                case 'warehouse':


                    $sql_count = sprintf(
                        'SELECT count(*) AS num FROM `Location Dimension` WHERE  `Location Warehouse Key`=%d  and `Location Type`!="Unknown" ', $parent_key
                    );
                    $sql_data  = sprintf(
                        'SELECT `Location Key` AS id FROM `Location Dimension`  left join `Warehouse Area Dimension` WA on (`Warehouse Area Key`=`Location Warehouse Area Key`)  WHERE `Location Warehouse Key`=%d and `Location Type`!="Unknown"', $parent_key
                    );

                    break;
                case 'warehouse_area':


                    $sql_count = sprintf(
                        'SELECT count(*) AS num FROM `Location Dimension` WHERE  `Location Warehouse Area Key`=%d  and `Location Type`!="Unknown" ', $parent_key
                    );
                    $sql_data  = sprintf(
                        'SELECT `Location Key` AS id FROM `Location Dimension`  left join `Warehouse Area Dimension` WA on (`Warehouse Area Key`=`Location Warehouse Area Key`) WHERE `Location Warehouse Area Key`=%d and `Location Type`!="Unknown" ', $parent_key
                    );

                    break;
                default:

                    break;
            }
            break;
        case 'warehouse_area':
            $object_id_name = 'Id: Warehouse Area Key';


            $sql_count = sprintf(
                'SELECT count(*) AS num FROM `Warehouse Area Dimension` WHERE  `Warehouse Area Warehouse Key`=%d  ', $parent_key
            );
            $sql_data  = sprintf(
                'SELECT `Warehouse Area Key` AS id FROM `Warehouse Area Dimension`  WHERE  `Warehouse Area Warehouse Key`=%d  ', $parent_key
            );

            break;

            break;
        case 'product':
            include_once 'class.Product.php';
            include_once 'class.Store.php';

            $object_id_name = 'Id: Product ID';


            switch ($parent) {
                case 'part_category':
                    include_once 'class.Part.php';
                    include_once 'class.Category.php';
                    include_once 'utils/currency_functions.php';

                    $account = new Account($db);

                    $sql_count = sprintf(
                        'SELECT count(*) AS num FROM `Category Bridge` WHERE `Subject`="Part" AND  `Category Key`=%d', $parent_key
                    );
                    $sql_data  = sprintf(
                        'SELECT `Subject Key` AS id FROM `Category Bridge` WHERE `Subject`="Part" AND `Category Key`=%d', $parent_key
                    );


                    $store    = new Store($metadata['store_key']);
                    $family   = new Category($parent_key);
                    $exchange = currency_conversion($db, $account->get('Account Currency'), $store->get('Store Currency Code'));


                    break;

                case 'category':


                    include_once 'class.Product.php';
                    include_once 'class.Category.php';


                    $sql_count = sprintf(
                        'SELECT count(*) AS num FROM `Category Bridge` WHERE `Subject`="Product" AND  `Category Key`=%d', $parent_key
                    );
                    $sql_data  = sprintf(
                        'SELECT `Subject Key` AS id FROM `Category Bridge` WHERE `Subject`="Product" AND `Category Key`=%d', $parent_key
                    );


                    //$exchange=currency_conversion($db, $account->get('Account Currency'), $store->get('Store Currency Code'));


                    break;
                case 'part_family':
                    $sql_count = sprintf(
                        "SELECT count(DISTINCT P.`Product ID`) AS num FROM `Category Bridge` LEFT JOIN `Part Dimension` ON (`Subject Key`=`Part SKU`) LEFT JOIN `Product Part Bridge` ON (`Product Part Part SKU`=`Part SKU`) LEFT JOIN `Product Dimension` P ON (`Product Part Product ID`=`Product ID`) LEFT JOIN `Product Data` PD ON (PD.`Product ID`=P.`Product ID`) LEFT JOIN `Product DC Data` PDCD ON (PDCD.`Product ID`=P.`Product ID`) LEFT JOIN `Store Dimension` S ON (`Product Store Key`=`Store Key`) WHERE P.`Product Type`='Product' AND`Subject`='Part' AND `Category Key`=%d ",
                        $parent_key
                    );

                    $sql_data = sprintf(
                        "SELECT P.`Product ID` AS id FROM `Category Bridge` LEFT JOIN `Part Dimension` ON (`Subject Key`=`Part SKU`) LEFT JOIN `Product Part Bridge` ON (`Product Part Part SKU`=`Part SKU`) LEFT JOIN `Product Dimension` P ON (`Product Part Product ID`=`Product ID`) LEFT JOIN `Product Data` PD ON (PD.`Product ID`=P.`Product ID`) LEFT JOIN `Product DC Data` PDCD ON (PDCD.`Product ID`=P.`Product ID`) LEFT JOIN `Store Dimension` S ON (`Product Store Key`=`Store Key`) WHERE P.`Product Type`='Product' AND`Subject`='Part' AND `Category Key`=%d ",
                        $parent_key
                    );

                    break;

                default:
                    break;
            }
            break;


        default:

            //   exit;
            break;
    }


    if ($result = $db->query($sql_count)) {
        if ($row = $result->fetch()) {
            $number_rows = $row['num'];
        }
    }

    $socket  = get_zqm_message_socket();


    $socket->send(
        json_encode(
            array(
                'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                'progress_bar' => array(
                    array(
                        'id'    => 'download_'.$download_key,
                        'state' => 'In Process',

                        'progress_info' => percentage(0, $number_rows),
                        'progress'      => sprintf(
                            '%s/%s (%s)', number(0), number($number_rows), percentage(
                                            0, $number_rows
                                        )
                        ),
                        'percentage'    => percentage(0, $number_rows),

                    )

                ),


            )
        )
    );


    $_objects = $objects;

    //print $_objects;
    //print_r($export_edit_template_fields);
    //print_r($field_keys);

    $fields = array();
    foreach ($field_keys as $field_key) {
        if ($field_key != '') {
            $fields[] = $export_edit_template_fields[$_objects][$field_key];

        }
    }


    $objPHPExcel = new Spreadsheet();
    Cell::setValueBinder(new AdvancedValueBinder());


    $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
        $category
    );

    $row_index = 1;

    $show_feedback = (float)microtime(true) + .400;


    if ($result = $db->query($sql_data)) {
        foreach ($result as $row) {



            switch ($objects) {
                case 'supplier_part':
                    $object = new SupplierPart($row['id']);
                    $object->get_supplier_data();


                    $data_rows = array();

                    $data_rows[] = array(
                        'cell_type' => 'auto',
                        'value'     => $object->id
                    );

                    foreach ($fields as $field) {
                        //print_r($object);
                        //print $field['name'];
                        $data_rows[] = array(
                            'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                            'value'     => $object->get($field['name']),
                        );
                    }

                    break;
                case 'location':
                    $object = get_object('Location', $row['id']);

                    $data_rows = array();

                    $data_rows[] = array(
                        'cell_type' => 'auto',
                        'value'     => $object->id
                    );

                    foreach ($fields as $field) {

                        $data_rows[] = array(
                            'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                            'value'     => $object->get($field['name']),
                            'field'     => $field['name']
                        );
                    }

                    break;
                case 'warehouse_area':
                    $object = get_object('WarehouseArea', $row['id']);

                    $data_rows = array();

                    $data_rows[] = array(
                        'cell_type' => 'auto',
                        'value'     => $object->id
                    );

                    foreach ($fields as $field) {

                        $data_rows[] = array(
                            'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                            'value'     => $object->get($field['name']),
                            'field'     => $field['name']
                        );
                    }

                    break;
                case 'part':


                    $object = get_object('Part', $row['id']);


                    $data_rows = array();

                    $data_rows[] = array(
                        'cell_type' => 'auto',
                        'value'     => $object->id
                    );

                    foreach ($fields as $field) {

                        if ($field['name'] == 'Part Barcode') {
                            $_field_name = 'Part Barcode Number';
                        } else {
                            $_field_name = $field['name'];
                        }


                        $data_rows[] = array(
                            'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                            'value'     => $object->get($_field_name),
                            'field'     => $field['name']
                        );
                    }

                    break;

                case 'product':


                    switch ($parent) {

                        case 'category':
                        case 'part_family':


                            $object = new Product($row['id']);

                            $data_rows = array();

                            $data_rows[] = array(
                                'cell_type' => 'auto',
                                'value'     => $object->id
                            );

                            foreach ($fields as $field) {

                                $data_rows[] = array(
                                    'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                                    'value'     => $object->get($field['name']),
                                    'field'     => $field['name']
                                );
                            }
                            break;

                        case 'part_category':
                            $object = get_object('Part',$row['id']);
                            $data_rows      = array();
                            if ($object->get('Part Status') != 'Not In Use' and $object->id) {


                                // print $object->get('Part Reference')."\n";


                                $product_exists = false;
                                $sql            = "SELECT count(*) as num FROM `Product Dimension` WHERE `Product Status`!='Discontinued' AND `Product Store Key`=? AND `Product Code`=? ";
                                $stmt           = $db->prepare($sql);
                                $stmt->execute(
                                    array(
                                        $store->id,
                                        $object->get('Part Reference')
                                    )
                                );
                                if ($row = $stmt->fetch()) {
                                    if ($row['num'] > 0) {
                                        $product_exists = true;
                                    }
                                }


                                if (!$product_exists) {

                                    $op = 'NEW';

                                    if ($object->get('Part Status') == 'In Process') {
                                        $op = 'NOT READY (';

                                        if (!$object->get('Part Main Image Key') > 0) {
                                            $op .= 'NO PIC, ';

                                        }
                                        if (!$object->get('Part Current On Hand Stock') > 0) {
                                            $op .= 'NO STOCK, ';

                                        }

                                        $op = preg_replace('/,\s*$/', '', $op).")";


                                    }


                                    $data_rows[] = array(
                                        'cell_type' => 'auto',
                                        'value'     => $op
                                    );


                                    if (is_numeric($object->get('Part Recommended Packages Per Selling Outer')) and $object->get('Part Recommended Packages Per Selling Outer') > 0) {
                                        $skos_per_outer = $object->get('Part Recommended Packages Per Selling Outer');
                                    } else {
                                        $skos_per_outer = 1;
                                    }

                                    foreach ($fields as $field) {


                                        switch ($field['name']) {
                                            case 'Product Code':
                                                $value = $object->get('Reference');
                                                break;
                                            case 'Parts':

                                                $value = $skos_per_outer.'x '.$object->get('Reference');
                                                break;
                                            case 'Product Name':
                                                $value = $object->get('Part Recommended Product Unit Name');
                                                break;
                                            case 'Product Inner':
                                                $value = $object->get('Part Units Per Package');
                                                break;
                                            case 'Product Family Category Code':
                                                $value = $family->get('Code');
                                                break;
                                            case 'Product Label in Family':
                                                $value = $object->get('Part Label in Family');
                                                break;
                                            case 'Product Units Per Case':
                                                $value = $object->get('Part Units Per Package') * $skos_per_outer;
                                                break;
                                            case 'Product Unit Label':
                                                $value = $object->get('Part Unit Label');
                                                break;
                                            case 'Product Price':
                                                if ($object->get('Part Unit Price') == '') {
                                                    $value = '';
                                                } else {
                                                    $value = round($skos_per_outer * $exchange * $object->get('Part Unit Price') * $object->get('Part Units Per Package'), 2);
                                                }
                                                break;
                                            case 'Product Unit Price':
                                                if ($object->get('Part Unit Price') == '') {
                                                    $value = '';
                                                } else {
                                                    $value = round($exchange * $object->get('Part Unit Price'), 2);
                                                }
                                                break;
                                            case 'Product Unit RRP':

                                                if ($object->get('Part Unit RRP') == '') {
                                                    $value = '';
                                                } else {
                                                    $value = round($exchange * $object->get('Part Unit RRP'), 2);
                                                }
                                                break;


                                            // case 'Product Units Per Case':
                                            //    $value = $object->get('Part Units Per Package') * $skos_per_outer;
                                            //   break;
                                            default:
                                                $value = $object->get($field['name']);
                                                break;
                                        }


                                        $data_rows[] = array(
                                            'cell_type' => (isset($field['cell_type']) ? $field['cell_type'] : 'auto'),
                                            'value'     => $value,
                                            'field'     => $field['name']
                                        );
                                    }
                                }

                            }

                            break;
                        default:

                            break;
                    }


                    break;

                default:


                    break;
            }



            if ($row_index == 1) {
                $char_index = 1;

                $char = number2alpha($char_index);
                $objPHPExcel->getActiveSheet()->setCellValue(
                    $char.$row_index, $object_id_name
                );
                $char_index++;

                foreach ($fields as $field) {

                    $char = number2alpha($char_index);


                    $objPHPExcel->getActiveSheet()->setCellValue(
                        $char.$row_index, strip_tags($field['header'])
                    );

                    if ($field['required']) {
                        $objPHPExcel->getActiveSheet()->getStyle(
                            $char.$row_index
                        )->applyFromArray(
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


                $row_index++;
            }

            if(count($data_rows)>0) {

                $char_index = 1;
                foreach ($data_rows as $data_row) {
                    $char = number2alpha($char_index);


                    if ($data_row['cell_type'] == 'string' or $char_index == 1) {

                        $objPHPExcel->getActiveSheet()->setCellValueExplicit(
                            $char.$row_index, strip_tags($data_row['value']), DataType::TYPE_STRING
                        );
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue(
                            $char.$row_index, strip_tags($data_row['value'])
                        );

                    }


                    $char_index++;
                }

                $row_index++;

                if (microtime(true) > $show_feedback) {
                    //  print 'xx '.microtime(true) ." -> $show_feedback\n";


                    $sql = sprintf(
                        'select `Download State` from  `Download Dimension` where `Download Key`=%d  ', $download_key

                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            if ($row['Download State'] == 'Cancelled') {
                                return 1;
                            }
                        }
                    }


                    $socket->send(
                        json_encode(
                            array(
                                'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                                'progress_bar' => array(
                                    array(
                                        'id'    => 'download_'.$download_key,
                                        'state' => 'In Process',

                                        'progress_info' => percentage($row_index, $number_rows),
                                        'progress'      => sprintf(
                                            '%s/%s (%s)', number($row_index), number($number_rows), percentage(
                                                            $row_index, $number_rows
                                                        )
                                        ),
                                        'percentage'    => percentage($row_index, $number_rows),

                                    )

                                ),


                            )
                        )
                    );


                    $show_feedback = (float)microtime(true) + .400;


                }
            }

        }
    }



    $sheet        = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();


    try {
        $cellIterator->setIterateOnlyExistingCells(true);
    } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {

    }

    /** @var \PhpOffice\PhpSpreadsheet\Cell\Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

    $objPHPExcel->getActiveSheet()->freezePane('A2');


    $download_path = 'tmp/';


    switch ($output_type) {

        case('csv'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Csv')->setDelimiter(',')->setEnclosure('')->setLineEnding("\r\n")->setSheetIndex(0)->save($output_file);
            break;
        case('xlsx'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Xlsx')->save($output_file);
            break;
        case('xls'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            IOFactory::createWriter($objPHPExcel, 'Xls')->save($output_file);
            break;
        case('pdf'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            $objPHPExcel->getActiveSheet()->setShowGridLines(false);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            IOFactory::createWriter($objPHPExcel, 'Pdf')->save($output_file);
            break;

    }


    $sql = "update `Download Dimension` set `Download State`='Finish' , `Download Data`=?  ,`Download Filename`=? where `Download Key`=? ";
    $db->prepare($sql)->execute(
        array(
            file_get_contents($output_file),
            $output_filename.'.'.$output_type,
            $download_key
        )
    );



    $socket->send(
        json_encode(
            array(
                'channel'      => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,
                'progress_bar' => array(
                    array(
                        'id'           => 'download_'.$download_key,
                        'state'        => 'Finish',
                        'download_key' => $download_key,

                        'progress_info' => _('Done'),
                        'progress'      => sprintf(
                            '%s/%s (%s)', number($number_rows), number($number_rows), percentage(
                                            $number_rows, $number_rows
                                        )
                        ),
                        'percentage'    => percentage($number_rows, $number_rows),

                    )

                ),


            )
        )
    );
    unlink($output_file);

    return false;
}



