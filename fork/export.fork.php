<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 14:11:21 GMT+8, Kuala Lumpur, Malaysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/send_zqm_message.class.php';

use Gumlet\ImageResize;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Spatie\ImageOptimizer\OptimizerChainFactory;

function fork_export($job)
{
    global $account, $db;// remove the global $db and $account is removed

    require_once 'vendor/autoload.php';
    include_once 'utils/image_functions.php';


    if (!$_data = get_fork_metadata($job)) {
        return false;
    }

    /**
     * @var $account \Account
     * @var $db      \PDO
     */
    $account   = $_data[0];
    $db        = $_data[1];
    $fork_data = $_data[2];

    //return true;
    //print_r($fork_data);
    //exit;

    $inikoo_account_code = $account->get('Account Code');


    $output_type  = $fork_data['output'];
    $sql_count    = $fork_data['sql_count'];
    $sql_data     = $fork_data['sql_data'];
    $ws_key       = $fork_data['ws_key'];
    $download_key = $fork_data['download_key'];


    $creator     = 'aurora.systems';
    $title       = _('Report');
    $subject     = _('Report');
    $description = '';
    $keywords    = '';
    $category    = '';


    $output_filename = 'export_'.$inikoo_account_code.'_'.$fork_data['table'].'_'.$download_key;

    $columns_no_resize = array();
    $number_rows       = 0;

    $sql = "update `Download Dimension` set `Download State`='In Process',`Download Filename`=? where `Download Key`=?  ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $output_filename.'.'.$output_type,
            $download_key
        )
    );


    if ($sql_count != '') {
        if ($result = $db->query($sql_count)) {
            if ($row = $result->fetch()) {
                $number_rows = $row['num'];
            }
        }
    } else {
        $stmt = $db->prepare($sql_data);


        $stmt->execute();

        $number_rows = $stmt->rowCount();
    }


    $sockets = get_zqm_message_sockets();

    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel' => $ws_key,

                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'In Process',
                            'progress_info' => percentage(0, $number_rows),
                            'progress'      => sprintf('%s/%s (%s)', number(0), number($number_rows), percentage(0, $number_rows)),
                            'percentage'    => percentage(0, $number_rows),

                        )

                    ),


                )
            )
        );
    }

    $objPHPExcel = new Spreadsheet();
    Cell::setValueBinder(new AdvancedValueBinder());


    $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
        $category
    );

    $row_index = 1;

    if (empty($fork_data['fields']) or $fork_data['fields'] == '') {
        $sql = sprintf("update `Download Dimension` set `Download State`='Error' where `Download Key`=%d  ", $download_key);
        $db->exec($sql);

        return 1;
    }


    if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
        $placeholders = array(
            'inventory_stock_history_day_stock'       => 'sum(`Quantity On Hand`)',
            'inventory_stock_history_day_stock_value' => 'sum(`Value At Cost`) '
        );
    } else {
        $placeholders = array(
            'inventory_stock_history_day_stock'       => 'sum(`Quantity On Hand`)',
            'inventory_stock_history_day_stock_value' => 'sum(`Value At Day Cost`) '
        );
    }


    $sql_data = strtr($sql_data, $placeholders);


    print $sql_data."\n";

    $show_feedback = microtime(true) + .250;


    if ($result = $db->query($sql_data)) {
        foreach ($result as $row) {

            print_r($row);

            if ($row_index == 1) {
                $char_index = 1;

                foreach ($fork_data['fields'] as $_key) {
                    if (isset($fork_data['field_set'][$_key]['labels'])) {
                        foreach ($fork_data['field_set'][$_key]['labels'] as $label) {
                            $char = number2alpha($char_index);
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $char.$row_index,
                                strip_tags($label)
                            );
                            $char_index++;
                        }
                    } else {
                        if (isset($fork_data['field_set'][$_key]['type']) and $fork_data['field_set'][$_key]['type'] == 'dynamic_headers') {
                            $sql = 'select '.sprintf(
                                    '%s as element from %s %s %s %s',
                                    $fork_data['field_set'][$_key]['header_field'],
                                    $fork_data['sql_table'],
                                    $fork_data['field_set'][$_key]['header_table'],
                                    $fork_data['sql_where'],
                                    $fork_data['field_set'][$_key]['header_group_by']
                                );


                            $dynamic_fields = [];
                            $stmt2          = $db->prepare($sql);
                            $stmt2->execute();
                            while ($row2 = $stmt2->fetch()) {
                                $dynamic_fields[$row2['element']] = '';
                            }
                            $fork_data['field_set'][$_key]['dynamic_fields'] = $dynamic_fields;


                            $prefix = '';
                            if (isset($fork_data['field_set'][$_key]['header_prefix'])) {
                                $prefix = $fork_data['field_set'][$_key]['header_prefix'];
                            }

                            foreach ($dynamic_fields as $key => $dynamic_field) {
                                $char = number2alpha($char_index);
                                $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $prefix.$key);
                                $char_index++;
                            }
                        } else {

                            $char = number2alpha($char_index);
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $char.$row_index,
                                strip_tags($fork_data['field_set'][$_key]['label'])
                            );

                            //print "$char.$row_index ".strip_tags($fork_data['field_set'][$_key]['label'])."\n";

                            $char_index++;
                        }
                    }
                }

                $row_index++;
            }


            $char_index      = 1;
            $real_char_index = 1;
            //print_r($row);
            foreach ($row as $sql_field => $value) {
                $char = number2alpha($char_index);


                //print ">>>>>>>>> $sql_field ==> $value\n";

                if ($sql_field == 'Part Materials') {
                    //print "XXXX Materials\n";

                    $materials = '';
                    if ($value != '') {
                        $materials_data = json_decode($value, true);


                        foreach ($materials_data as $material_data) {
                            if (!array_key_exists('id', $material_data)) {
                                continue;
                            }

                            if ($material_data['may_contain'] == 'Yes') {
                                $may_contain_tag = '±';
                            } else {
                                $may_contain_tag = '';
                            }


                            $materials .= sprintf(
                                ', %s%s',
                                $may_contain_tag,
                                $material_data['name']
                            );


                            if ($material_data['ratio'] > 0) {
                                $materials .= sprintf(
                                    ' (%s)',
                                    percentage($material_data['ratio'], 1)
                                );
                            }
                        }

                        $materials = ucfirst(
                            preg_replace('/^, /', '', $materials)
                        );
                    }


                    $value = $materials;
                }

                if($sql_field=='Part Unit Dimensions' or $sql_field=='Part Package Dimensions'){



                    $dimensions = '';


                    if ($value != '') {
                        $data = json_decode($value, true);
                        if ($data) {
                            include_once 'utils/units_functions.php';
                            switch ($data['type']) {
                                case 'Rectangular':
                                    $dimensions = number(
                                            convert_units(
                                                $data['l'], 'm', $data['units']
                                            )
                                        ).'x'.number(
                                            convert_units(
                                                $data['w'], 'm', $data['units']
                                            )
                                        ).'x'.number(
                                            convert_units(
                                                $data['h'], 'm', $data['units']
                                            )
                                        ).' ('.$data['units'].')';
                                    break;
                                case 'Sheet':
                                    $dimensions = number(
                                            convert_units(
                                                $data['l'], 'm', $data['units']
                                            )
                                        ).'x'.number(
                                            convert_units(
                                                $data['w'], 'm', $data['units']
                                            )
                                        ).' ('.$data['units'].')';
                                    break;
                                case 'Cilinder':
                                    $dimensions = number(
                                            convert_units(
                                                $data['h'], 'm', $data['units']
                                            )
                                        ).'x'.number(
                                            convert_units(
                                                $data['w'], 'm', $data['units']
                                            )
                                        ).' ('.$data['units'].')';
                                    break;
                                case 'Sphere':
                                    $dimensions = 'D:'.number(
                                            convert_units(
                                                $data['h'], 'm', $data['units']
                                            )
                                        ).' ('.$data['units'].')';

                                    break;

                                case 'String':
                                    $dimensions = 'L.'.number(
                                            convert_units(
                                                $data['l'], 'm', $data['units']
                                            )
                                        ).' ('.$data['units'].')';

                                    break;


                                default:
                                    $dimensions = '';
                            }
                        }
                    }
                    $value = $dimensions;

                }

                if ($sql_field == 'Part Main Image Key') {
                    if ($value > 0) {
                        $columns_no_resize[] = $char;

                        $objDrawing = new Drawing();               //create object for Worksheet drawing
                        $objDrawing->setName('Image');             //set name to image
                        $objDrawing->setDescription('Item image'); //set description to image


                        $original_image = get_object('Image', $value);


                        $height = 200;
                        $width  = 200;
                        $ratio  = $original_image->get('Image Width') / $original_image->get('Image Height');

                        if ($ratio > 1) {
                            $height = ceil(200 / $ratio);
                        } else {
                            $width = ceil(200 * $ratio);
                        }

                        $size_r = ceil($width).'x'.ceil($height);


                        $image_path = preg_replace('/^img\//', 'img_'.$account->get('Code').'/', $original_image->get('Image Path'));


                        $cached_image_path = preg_replace('/^.*\/db\//', 'cache/', $image_path);
                        $cached_image_path = preg_replace('/\./', '_'.$size_r.'.', $cached_image_path);


                        if (!file_exists($cached_image_path)) {
                            if (!is_dir('cache/'.$original_image->get('Image File Checksum')[0])) {
                                mkdir('cache/'.$original_image->get('Image File Checksum')[0]);
                            }

                            if (!is_dir('cache/'.$original_image->get('Image File Checksum')[0].'/'.$original_image->get('Image File Checksum')[1])) {
                                mkdir('cache/'.$original_image->get('Image File Checksum')[0].'/'.$original_image->get('Image File Checksum')[1]);
                            }


                            if ($size_r != '') {
                                list($w, $h) = preg_split('/x/', $size_r);
                                $image = new ImageResize($image_path);


                                $image->quality_jpg = 100;
                                $image->quality_png = 9;

                                $image->resizeToBestFit($w, $h);
                                $image->save($cached_image_path);


                                if (file_exists($cached_image_path)) {
                                    usleep(1000);
                                }
                                if (file_exists($cached_image_path)) {
                                    usleep(2000);
                                }
                                if (file_exists($cached_image_path)) {
                                    usleep(3000);
                                }
                                if (file_exists($cached_image_path)) {
                                    usleep(100000);
                                }
                            } else {
                                copy($image_path, $cached_image_path);
                            }
                        }


                        $optimizerChain = OptimizerChainFactory::create();
                        $optimizerChain->optimize($cached_image_path);

                        if (file_exists($cached_image_path)) {
                            usleep(1000);
                        }
                        if (file_exists($cached_image_path)) {
                            usleep(2000);
                        }
                        if (file_exists($cached_image_path)) {
                            usleep(3000);
                        }
                        if (file_exists($cached_image_path)) {
                            usleep(100000);
                        }


                        $objDrawing->setPath($cached_image_path);
                        $objDrawing->setOffsetX(10);                          //setOffsetX works properly
                        $objDrawing->setOffsetY(10);                          //setOffsetY works properly
                        $objDrawing->setCoordinates($char.$row_index);        //set image to cell


                        $objDrawing->setResizeProportional(true);

                        // $objDrawing->setWidth(200);                 //set width, height

                        if ($width > $height) {
                            $objDrawing->setWidth(200);
                        } else {
                            $objDrawing->setHeight(200);
                        }


                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save


                        $objPHPExcel->getActiveSheet()->getRowDimension($row_index)->setRowHeight(220);
                    }


                    $objPHPExcel->getActiveSheet()->getColumnDimension($char)->setWidth(220);
                }


                $type = '';
                if (!empty($fork_data['fields'][$real_char_index - 1])) {
                    $field_index = $fork_data['fields'][$real_char_index - 1];
                    if (!empty($fork_data['field_set'][$field_index]['type'])) {
                        $type = $fork_data['field_set'][$field_index]['type'];
                    }
                }


                //print ">>>>>>>> $type <<<<<<<\n";


                if ($type == 'html' or $type == 'dynamic_headers') {
                    $_value = $value;
                } else {
                    $value  = str_replace("\xc2\xa0", ' ', $value);
                    $_value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5);
                }


                if ($type == 'text') {
                    //print "$char.$row_index $_value\n";
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($char.$row_index, $_value, DataType::TYPE_STRING);
                } elseif ($type == 'dynamic_headers') {
                    $dynamic_fields = $fork_data['field_set'][$fork_data['fields'][$real_char_index - 1]]['dynamic_fields'];


                    foreach (preg_split('/,/', $_value) as $_values_data) {
                        $values_data = preg_split('/\|/', $_values_data);

                        $dynamic_fields[$values_data[0]] = $values_data[1];


                        // print_r($values_data);
                        // exit;

                    }

                    foreach ($dynamic_fields as $dynamic_field) {
                        $char = number2alpha($char_index);
                        $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $dynamic_field);
                        $char_index++;
                    }
                    $char_index--;
                } else {
                    //print "$char.$row_index $_value\n";
                    $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $_value);
                }


                $real_char_index++;
                $char_index++;
            }

            $row_index++;


            if (microtime(true) > $show_feedback) {
                $sql = sprintf(
                    'select `Download State` from  `Download Dimension` where `Download Key`=%d  ',
                    $download_key

                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['Download State'] == 'Cancelled') {
                            return 1;
                        }
                    }
                }

                foreach ($sockets as $socket) {
                    $socket->send(
                        json_encode(
                            array(
                                'channel'      => $ws_key,
                                'progress_bar' => array(
                                    array(
                                        'id'            => 'download_'.$download_key,
                                        'state'         => 'In Process',
                                        'progress_info' => percentage($row_index, $number_rows),
                                        'progress'      => sprintf('%s/%s (%s)', number($row_index), number($number_rows), percentage($row_index, $number_rows)),
                                        'percentage'    => percentage($row_index, $number_rows),

                                    )

                                ),


                            )
                        )
                    );
                }

                $show_feedback = microtime(true) + .400;
            }
        }
    }
    // exit('caca2');

    $sheet = $objPHPExcel->getActiveSheet();

    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

    foreach ($cellIterator as $cell) {
        // print_r($cell->getColumn());
        if (in_array($cell->getColumn(), $columns_no_resize)) {
            $sheet->getColumnDimension($cell->getColumn())->setWidth(250);
        } else {
            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
        }
    }


    $objPHPExcel->getActiveSheet()->freezePane('A2');


    $download_path = 'tmp/';


    //print ">>> $output_type <<<<";

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


    $sql = "update `Download Dimension` set `Download State`='Finish' , `Download Data`=?   where `Download Key`=? ";


    $db->prepare($sql)->execute(
        array(
            file_get_contents($output_file),
            $download_key
        )
    );


    foreach ($sockets as $socket) {
        $socket->send(
            json_encode(
                array(
                    'channel'      => $ws_key,
                    'progress_bar' => array(
                        array(
                            'id'            => 'download_'.$download_key,
                            'state'         => 'Finish',
                            'download_key'  => $download_key,
                            'progress_info' => _('Done'),
                            'progress'      => sprintf('%s/%s (%s)', number($number_rows), number($number_rows), percentage($number_rows, $number_rows)),
                            'percentage'    => percentage($number_rows, $number_rows),

                        )

                    ),


                )
            )
        );
    }


    unlink($output_file);
   // exit('caca');

    return false;
}



