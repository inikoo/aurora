<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 February 2020  12:23::31  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020 Inikoo

 Version 3.0
*/

require_once __DIR__.'/../vendor/autoload.php';
require __DIR__.'/keyring/dns.php';
require_once 'utils/sentry.php';


require_once 'utils/object_functions.php';

use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

include_once 'utils/general_functions.php';

if (empty($_REQUEST['uid']) or !is_numeric($_REQUEST['uid']) or empty($_REQUEST['token']) or strlen($_REQUEST['token']) != 32 or empty($_REQUEST['output']) or empty($_REQUEST['scope'])) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$sql  =
    "select `Customer Key` ,`Website URL` from `Customer Dimension` left join `Website User Dimension` on (`Customer Key`=`Website User Customer Key`) left join `Website Dimension` on (`Website User Website Key`=`Website Key`)    where `Website User Static API Hash`=? and `Website User Key`=?  ";
$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $_REQUEST['token'],
        $_REQUEST['uid']
    )
);
if ($row = $stmt->fetch()) {

    switch ($_REQUEST['scope']) {
        case 'portfolio_items':
            $output_type = strtolower($_REQUEST['output']);

            if (in_array(
                $output_type, [
                                'csv',
                                'xlsx',
                                'xls',
                                'pdf'
                            ]
            )) {
                $use_php_excel = true;

            } elseif (in_array($output_type, ['json'])) {
                $use_php_excel = false;

            } else {
                header("HTTP/1.0 400 Bad Request");
                exit;
            }

            include_once 'conf/export_fields.php';

            $objPHPExcel = new Spreadsheet();
            Cell::setValueBinder(new AdvancedValueBinder());

            $creator     = 'aurora.systems';
            $title       = 'Portfolio items';
            $subject     = 'Data feed';
            $description = '';
            $keywords    = '';
            $category    = '';

            $columns_no_resize = array();

            $objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
                $category
            );

            $row_index = 1;


            $export_fields = get_export_fields('portfolio_items');

            foreach ($export_fields as $key=>$field) {

                if ($use_php_excel and isset($field['type']) and $field['type']=='array') {
                    unset($export_fields[$key]);
                }
                if (!$use_php_excel and isset($field['ignore_json']) ) {
                    unset($export_fields[$key]);
                }

              ;
            }



            $sql = "select ";
            foreach ($export_fields as $field) {



                $sql .= $field['name'].',';
            }

            $sql = preg_replace('/,$/', ' from ', $sql);
            $sql .= " `Customer Portfolio Fact` CPF left join `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) left join `Product Data` PD on (PD.`Product ID`=P.`Product ID`) left join `Product DC Data` PDCD on (PDCD.`Product ID`=P.`Product ID`)  left join `Store Dimension` S on (`Product Store Key`=`Store Key`)    left join `Page Store Dimension` W on (`Product Webpage Key`=`Page Key`) ";
            $sql .= "where `Customer Portfolio Customer Key`=? and   `Customer Portfolio Customers State`='Active'";


            $placeholders = array(
                '[image_address]' => 'https://'.$row['Website URL'].'/wi/'
            );

            $sql = strtr($sql, $placeholders);




            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $row['Customer Key']
                )
            );

            if ($use_php_excel) {
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

                        $char = number2alpha($char_index);
                        $type = (empty($export_fields[$char_index - 1]['type']) ? '' : $export_fields[$char_index - 1]['type']);


                        if ($type == 'html') {
                            $_value = $value;
                        } else {
                            $value=str_replace("\xc2\xa0",' ',$value);
                            $_value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5);

                        }


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

                        // print_r($cell->getColumn());
                        if (in_array($cell->getColumn(), $columns_no_resize)) {
                            $sheet->getColumnDimension($cell->getColumn())->setWidth(250);
                        } else {
                            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);

                        }

                    }


                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {

                }


                $objPHPExcel->getActiveSheet()->freezePane('A2');
                $output_type = strtolower($_REQUEST['output']);


                switch ($output_type) {

                    case('csv'):
                        header("Content-type: text/csv");
                        header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'.csv"');
                        header('Cache-Control: max-age=0');
                        IOFactory::createWriter($objPHPExcel, 'Csv')->setDelimiter(',')->setEnclosure('"')->setLineEnding("\r\n")->setSheetIndex(0)->save('php://output');


                        break;
                    case('xlsx'):
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'.xlsx"');

                        header('Cache-Control: max-age=0');

                        IOFactory::createWriter($objPHPExcel, 'Xlsx')->save('php://output');
                        break;
                    case('xls'):
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'.xls"');

                        header('Cache-Control: max-age=0');
                        IOFactory::createWriter($objPHPExcel, 'Xls')->save('php://output');
                        break;
                    case('pdf'):
                        header("Content-type:application/pdf");
                        header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'.pdf"');

                        header('Cache-Control: max-age=0');
                        $objPHPExcel->getActiveSheet()->setShowGridLines(false);
                        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                        IOFactory::createWriter($objPHPExcel, 'Pdf')->save('php://output');
                        break;

                }

            }else{

                $data_header = [];
                $data_rows   = [];
                $row_index   = 1;
                while ($row = $stmt->fetch()) {

                    if ($row_index == 1) {
                        $char_index = 1;


                        foreach ($export_fields as $field) {


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
                                $value=str_replace("\xc2\xa0",' ',$value);
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

                switch ($output_type) {

                    case('json'):
                        header("Content-type: application/json; charset=utf-8");
                        header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'_json.txt"');

                        header('Cache-Control: max-age=0');
                        print json_encode($data);

                }

            }


            break;
        case 'portfolio_images':
            $counter = 1;
            $files = array();

            $sql  = "SELECT `Customer Portfolio Product ID` FROM  `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Customers State`='Active' ";
            $stmt2 = $db->prepare($sql);
            $stmt2->execute(
                array(
                    $row['Customer Key']
                )
            );
            while ($row2 = $stmt2->fetch()) {
                $product = get_object('Product', $row2['Customer Portfolio Product ID']);
                $counter = 1;
                foreach ($product->get_images_slideshow() as $data) {

                    $files[] = array(
                        'name'      => strtolower($product->get('Code')).sprintf('_%02d', $counter),
                        'image_key' => $data['id'],
                        'folder'    => ''
                    );
                    $counter++;
                }
            }


            $zip = new ZipArchive();

            $tmp_file = tempnam('server_files/tmp/', 'webpage_images_zip_').'.zip';

            $zip->open($tmp_file, ZipArchive::CREATE);


            foreach ($files as $file) {
                $image = get_object('image', $file['image_key']);
                $zip->addFile('../'.$image->get('Image Path'), $file['folder'].basename($file['name'].'.'.$image->get('Image File Format')));

            }


            $zip->close();


            header('Content-disposition: attachment; filename=portfolio_images.zip');
            header('Content-type: application/zip');
            readfile($tmp_file);
            unlink($tmp_file);

            break;
        default:
            header("HTTP/1.0 400 Bad Request");
            exit;
    }


} else {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

