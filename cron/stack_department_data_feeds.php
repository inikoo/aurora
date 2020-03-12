<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 March 2020  13:23::31  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once 'common.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack department data feeds)',
    'Author Alias' => 'System (Stack department data feeds)',


);

$sql  = "SELECT count(*) AS num FROM `Stack Dimension`  where `Stack Operation`='department_data_feed'";
$stmt = $db->prepare($sql);
$stmt->execute();
if ($row = $stmt->fetch()) {
    $total = $row['num'];
} else {
    $total = 0;
}


$lap_time0 = date('U');
$lap_time1 = date('U');
$contador  = 0;


$sql    = "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='department_data_feed' ORDER BY RAND() ";
$stmt_3 = $db->prepare($sql);
$stmt_3->execute();
while ($row3 = $stmt_3->fetch()) {
    $department = get_object('Category', $row3['Stack Object Key']);

    if ($department->id) {

        $sql = "select `Stack Key` from `Stack Dimension` where `Stack Key`=?";

        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            array(
                $row3['Stack Key']
            )
        );
        if ($row2 = $stmt2->fetch()) {
            $sql = "delete from `Stack Dimension`  where `Stack Key`=?";
            $db->prepare($sql)->execute([$row3['Stack Key']]);

            $export_fields_type = 'website_catalogue_items';
            $use_php_excel      = true;

            include_once 'conf/export_fields.php';
            $export_fields = get_export_fields($export_fields_type);

            foreach ($export_fields as $key => $field) {

                if ($use_php_excel and isset($field['type']) and $field['type'] == 'array') {
                    unset($export_fields[$key]);
                }
                if (!$use_php_excel and isset($field['ignore_json'])) {
                    unset($export_fields[$key]);
                };
            }

            $use_php_excel = false;


            $export_fields_json = get_export_fields($export_fields_type);

            foreach ($export_fields_json as $key => $field) {

                if ($use_php_excel and isset($field['type']) and $field['type'] == 'array') {
                    unset($export_fields_json[$key]);
                }
                if (!$use_php_excel and isset($field['ignore_json'])) {
                    unset($export_fields_json[$key]);
                };
            }

            $editor['Date']     = gmdate('Y-m-d H:i:s');
            $department->editor = $editor;

            $website      = get_object('Website', $department->get('Store Website Key'));
            $placeholders = array(
                '[image_address]' => 'https://'.$website->get('Website URL').'/wi/'
            );
            $sql_args     = array(
                $department->id
            );
            if ($department->id and $department->get('Product Category Status') == 'Active' and $department->get('Product Category Public')=='Yes') {


                $use_php_excel = true;


                $sql = "select ";
                foreach ($export_fields as $field) {
                    $sql .= $field['name'].',';
                }
                $sql = preg_replace('/,$/', ' from ', $sql);

                $sql .= "`Product Dimension` P left join `Page Store Dimension` W on (W.`Page Key`=`Product Webpage Key`) ";

                $sql .= "where `Product Department Category Key`=?  and `Webpage State`='Online' ";


                $sql  = strtr($sql, $placeholders);
                $stmt = $db->prepare($sql);
                $stmt->execute($sql_args);


                $title   = 'Department products';
                $subject = 'Data feed';


                $objPHPExcel = new Spreadsheet();
                Cell::setValueBinder(new AdvancedValueBinder());

                $creator = 'aurora.systems';

                $description = '';
                $keywords    = '';
                $category    = '';

                $columns_no_resize = array();

                $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
                    $category
                );


                $row_index = 1;
                while ($row = $stmt->fetch()) {




                    if ($row_index == 1) {
                        $char_index = 1;
                        foreach ($export_fields as $field) {


                            if (isset($field['labels'])) {

                                foreach ($field['labels'] as $label) {
                                    $char = number2alpha($char_index);
                                    $objPHPExcel->getActiveSheet()->setCellValue(
                                        $char.$row_index, strip_tags($label)
                                    );
                                    $char_index++;
                                }


                            } else {
                                $char = number2alpha($char_index);
                                $objPHPExcel->getActiveSheet()->setCellValue(
                                    $char.$row_index, strip_tags($field['label'])
                                );
                                $char_index++;
                            }


                        }

                        $row_index++;
                    }


                    $char_index = 1;
                    foreach ($row as $sql_field => $value) {

                        $type = (empty($export_fields[$char_index - 1]['type']) ? '' : $export_fields[$char_index - 1]['type']);


                        if ($type == 'html') {
                            $_value = $value;
                        } else {
                            $_value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5);

                        }
                        $char = number2alpha($char_index);
                        if ($type == 'text') {
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit($char.$row_index, $_value, DataType::TYPE_STRING);

                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue($char.$row_index, $_value);
                        }

                        $char_index++;
                    }

                    $row_index++;
                }


                try {
                    $sheet = $objPHPExcel->getActiveSheet();

                    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(true);

                    foreach ($cellIterator as $cell) {

                        if (in_array($cell->getColumn(), $columns_no_resize)) {
                            $sheet->getColumnDimension($cell->getColumn())->setWidth(250);
                        } else {
                            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);

                        }

                    }


                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {

                }

                $objPHPExcel->getActiveSheet()->freezePane('A2');

                IOFactory::createWriter($objPHPExcel, 'Csv')->setDelimiter(',')->setEnclosure('"')->setLineEnding("\r\n")->setSheetIndex(0)->save('EcomB2B/data_feeds/data_feed_department_'.$department->id.'.csv');
                IOFactory::createWriter($objPHPExcel, 'Xls')->save('EcomB2B/data_feeds/data_feed_department_'.$department->id.'.xls');


                $use_php_excel = false;

                $sql = "select ";
                foreach ($export_fields_json as $field) {
                    $sql .= $field['name'].',';
                }
                $sql = preg_replace('/,$/', ' from ', $sql);


                $sql .= "`Product Dimension` P left join `Page Store Dimension` W on (W.`Page Key`=`Product Webpage Key`) ";

                $sql .= "where `Product Department Category Key`=?  and `Webpage State`='Online' ";


                $sql  = strtr($sql, $placeholders);




                $stmt = $db->prepare($sql);
                $stmt->execute($sql_args);

                $data_header = [];
                $data_rows   = [];
                $row_index   = 1;
                while ($row = $stmt->fetch()) {

                    if ($row_index == 1) {
                        $char_index = 1;


                        foreach ($export_fields_json as $field) {

                            if (isset($field['ignore_json'])) {
                                continue;
                            }
                            if (isset($field['labels'])) {

                                foreach ($field['labels'] as $_key => $label) {
                                    $data_header[] = [
                                        'field_code'        => (isset($field['codes'][$_key]) ? $field['codes'][$_key] : strtolower(preg_replace('/\s*/', '_', strip_tags($label)))),
                                        'field_description' => strip_tags($label)
                                    ];

                                }


                            } else {
                                $data_header[] =

                                    [
                                        'field_code'        => (isset($field['code']) ? $field['code'] : strtolower(preg_replace('/\s/', '_', strip_tags($field['label'])))),
                                        'field_description' => strip_tags($field['label'])
                                    ];
                            }


                        }

                        $row_index++;
                    }


                    $char_index = 1;
                    $_row       = [];
                    foreach ($row as $sql_field => $value) {


                        $char = number2alpha($char_index);
                        $type = (empty($export_fields[$char_index - 1]['type']) ? '' : $export_fields[$char_index - 1]['type']);


                        if ($type == 'html') {
                            $_value = $value;
                        } else {
                            if ($type == 'array') {
                                $_value = preg_split('/,/', $value);

                            } else {
                                $_value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5);

                            }
                        }


                        $_row[] = $_value;

                        $char_index++;
                    }
                    $data_rows[] = $_row;
                    $row_index++;
                }

                $data = array(
                    'schema' => $data_header,
                    'data'   => $data_rows
                );

                file_put_contents('EcomB2B/data_feeds/data_feed_department_'.$department->id.'_json.txt', json_encode($data));


            }


        }


    } else {
        $sql = "delete from `Stack Dimension`  where `Stack Key`=?";
        $db->prepare($sql)->execute([$row3['Stack Key']]);

    }

    $contador++;
    $lap_time1 = date('U');

    if ($print_est) {
        print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
            )."h  ($contador/$total) \r";
    }
}


if ($total > 0) {
    printf("%s: %s/%s %.2f min Product Sales\n", gmdate('Y-m-d H:i:s'), $contador, $total, ($lap_time1 - $lap_time0) / 60);
}

