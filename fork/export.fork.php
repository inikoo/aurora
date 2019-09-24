<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 14:11:21 GMT+8, Kuala Lumpur, Malaysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

use \Gumlet\ImageResize;
use Spatie\ImageOptimizer\OptimizerChainFactory;

function fork_export($job) {


    require_once 'vendor/autoload.php';
    include_once 'utils/image_functions.php';


    if (!$_data = get_fork_metadata($job)) {
        return;
    }

    list($account, $db, $fork_data, $editor, $session) = $_data;

    $inikoo_account_code = $account->get('Account Code');


    $output_type   = $fork_data['output'];
    $sql_count     = $fork_data['sql_count'];
    $sql_data      = $fork_data['sql_data'];
    $user_key      = $fork_data['user_key'];
    $download_type = $fork_data['table'];
    $download_key  = $fork_data['download_key'];


    $creator     = 'aurora.systems';
    $title       = _('Report');
    $subject     = _('Report');
    $description = '';
    $keywords    = '';
    $category    = '';
    //$filename    = 'output';


    $output_filename = 'export_'.$inikoo_account_code.'_'.$fork_data['table'].'_'.$download_key;

    //print "Exporting $output_filename\n";


    $sql = sprintf(
        'update `Download Dimension` set `Download State`="In Process",`Download Filename`=%s where `Download Key`=%d  ', prepare_mysql($output_filename.'.'.$output_type), $download_key

    );

    $db->exec($sql);


    $files_to_delete = array();


    $columns_no_resize = array();

    $number_rows = 0;


    if ($sql_count != '') {

        if ($result = $db->query($sql_count)) {
            if ($row = $result->fetch()) {
                $number_rows = $row['num'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            //  exit;
        }

    } else {
        $stmt = $db->prepare($sql_data);


        $stmt->execute();

        $number_rows = $stmt->rowCount();

    }


    $context = new ZMQContext();
    $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");


    $socket->send(
        json_encode(
            array(
                'channel' => 'real_time.'.strtolower($account->get('Account Code')).'.'.$user_key,

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


    require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
    require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    require_once 'external_libs/PHPExcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php';
    PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());


    $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
        $category
    );


    $row_index = 1;
    //print "$sql_data\n";
    //  return 1;
    //exit;

    //  print_r($fork_data);

    if (empty($fork_data['fields']) or $fork_data['fields'] == '') {

        $sql = sprintf('update `Download Dimension` set `Download State`="Error" where `Download Key`=%d  ', download_key);
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
    // print $sql_data;
    // exit;

    $show_feedback = (float)microtime(true) + .250;


    if ($result = $db->query($sql_data)) {
        foreach ($result as $row) {

           // print_r($row);
            //usleep(10000);

            // exit;

            if ($row_index == 1) {

                //print_r($fork_data);
                //	print_r($row);


                /*
                foreach ($fork_data['fields'] as $field_key) {
                    if (isset($field_set[$field_key]))
                        $fields.=$field_set[$field_key]['name'].',';
                }
*/


                $char_index = 1;

                foreach ($fork_data['fields'] as $_key) {


                    if (isset($fork_data['field_set'][$_key]['labels'])) {

                        foreach ($fork_data['field_set'][$_key]['labels'] as $label) {
                            $char = number2alpha($char_index);
                            $objPHPExcel->getActiveSheet()->setCellValue(
                                $char.$row_index, strip_tags($label)
                            );
                            $char_index++;
                        }


                    } else {
                        $char = number2alpha($char_index);
                        $objPHPExcel->getActiveSheet()->setCellValue(
                            $char.$row_index, strip_tags($fork_data['field_set'][$_key]['label'])
                        );
                        $char_index++;
                    }


                }
                /*
                foreach ($row as $_key=>$value) {
                    $char=number2alpha($char_index);
                    $objPHPExcel->getActiveSheet()->setCellValue($char . $row_index, strip_tags($_key));
                    $char_index++;
                }
                */
                $row_index++;
            }


            $char_index = 1;
            foreach ($row as $sql_field => $value) {
                $char = number2alpha($char_index);


                if ($sql_field == 'Part Materials') {


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
                                ', %s%s', $may_contain_tag, $material_data['name']
                            );


                            if ($material_data['ratio'] > 0) {
                                $materials .= sprintf(
                                    ' (%s)', percentage($material_data['ratio'], 1)
                                );
                            }
                        }

                        $materials = ucfirst(
                            preg_replace('/^\, /', '', $materials)
                        );

                    }


                    $value = $materials;
                }


                if ($sql_field == 'Part Main Image Key') {

                    if ($value > 0) {


                        $columns_no_resize[] = $char;

                        $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
                        $objDrawing->setName('Image');        //set name to image
                        $objDrawing->setDescription('Item image'); //set description to image


                        //$tmp_file = 'tmp/'.$output_filename.date('U').$row_index.'.png';

                        $original_image = get_object('Image', $value);




                        $height=200;
                        $width=200;
                        $ratio = $original_image->get('Image Width') / $original_image->get('Image Height');

                        if ($ratio > 1) {
                            $height = ceil(200 / $ratio);
                        } else {
                            $width = ceil(200 * $ratio);
                        }

                        $size_r=ceil($width).'x'.ceil($height);



                        $image_path = preg_replace('/^img\//', 'img_'.$account->get('Code').'/', $original_image->get('Image Path'));;


                        $cached_image_path = preg_replace('/^.*\/db\//', 'img_cache/', $image_path);
                        $cached_image_path = preg_replace('/\./', '_'.$size_r.'.', $cached_image_path);



                      // print "$cached_image_path\n";

                        if (!file_exists($cached_image_path)) {

                            if (!is_dir('img_cache/'.$original_image->get('Image File Checksum')[0])) {
                                mkdir('img_cache/'.$original_image->get('Image File Checksum')[0]);
                            }

                            if (!is_dir('img_cache/'.$original_image->get('Image File Checksum')[0].'/'.$original_image->get('Image File Checksum')[1])) {
                                mkdir('img_cache/'.$original_image->get('Image File Checksum')[0].'/'.$original_image->get('Image File Checksum')[1]);
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
                        $objDrawing->setOffsetX(10);                       //setOffsetX works properly
                        $objDrawing->setOffsetY(10);                       //setOffsetY works properly
                        $objDrawing->setCoordinates($char.$row_index);        //set image to cell


                        $objDrawing->setResizeProportional(true);

                       // $objDrawing->setWidth(200);                 //set width, height

                       if($width>$height){
                           $objDrawing->setWidth(200);
                       }else{
                           $objDrawing->setHeight(200);
                       }


                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save


                        $objPHPExcel->getActiveSheet()->getRowDimension($row_index)->setRowHeight(220);

                        //$files_to_delete[] = $tmp_file;


                    }


                    $objPHPExcel->getActiveSheet()->getColumnDimension($char)->setWidth(220);

                } else {


                    $type = (empty($fork_data['field_set'][$char_index]['type']) ? '' : $fork_data['field_set'][$char_index]['type']);


                    if ($type == 'html') {
                        $_value = $value;
                    } else {
                        $_value = strip_tags($value);

                    }

                    //print "$sql_field --> $_value\n";

                    //  print_r($fork_data['field_set'][$char_index - 1]);

                    // $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $_value);

                    if ($type == 'text') {

                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($char.$row_index, $_value, PHPExcel_Cell_DataType::TYPE_STRING);

                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $_value);
                    }
                }


                $char_index++;
            }
            $row_index++;


            if (microtime(true) > $show_feedback) {
                //print 'xx '.microtime(true) ." -> $show_feedback\n";


                $sql = sprintf(
                    'select `Download State` from  `Download Dimension` where `Download Key`=%d  ', $download_key

                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['Download State'] == 'Cancelled') {
                            return 1;
                        }
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
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


            //


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql_data\n";
        // exit;
    }



    /*
    if (isset($_data['fork_data']['download_path'])) {
        $download_path=$_data['fork_data']['download_path']."_$inikoo_account_code/";
    }else {
        $download_path="downloads_$inikoo_account_code/";
    }
*/




    $sheet        = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
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

    switch ($output_type) {

        case('csv'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            // header('Content-Type: text/csv');
            // header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
            // header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',')->setEnclosure('')->setLineEnding("\r\n")->setSheetIndex(0)->save($output_file);
            break;
        case('xlsx'):

            $output_file = $download_path.$output_filename.'.'.$output_type;

            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            //header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter(
                $objPHPExcel, 'EXCEL2007'
            )->setSheetIndex(0)->save($output_file);
            break;
        case('xls'):
            $output_file = $download_path.$output_filename.'.'.$output_type;
            //header('Content-Type: application/vnd.ms-excel');
            //header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            //header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter(
                $objPHPExcel, 'Excel5'
            )->save($output_file);
            break;
        case('pdf'):
            $output_file = $download_path.$output_filename.'.'.$output_type;

            //header('Content-Type: application/pdf');
            //header('Content-Disposition: attachment;filename="'.$filename.'.pdf"');
            //header('Cache-Control: max-age=0');
            $objPHPExcel->getActiveSheet()->setShowGridLines(false);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(
                PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE
            );


            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF')->save($output_file);
            break;

    }


    $sql = sprintf(
        'update `Download Dimension` set `Download State`="Finish" , `Download Data`=%s   where `Download Key`=%d ', prepare_mysql(file_get_contents($output_file)), $download_key

    );

    $db->exec($sql);


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


    foreach ($files_to_delete as $file_to_delete) {
        unlink($file_to_delete);
    }


    return false;
}



