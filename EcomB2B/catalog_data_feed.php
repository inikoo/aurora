<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 February 2020  12:23::31  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020 Inikoo

 Version 3.0
*/

use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

include_once 'utils/general_functions.php';
include_once 'utils/object_functions.php';
if (empty($_REQUEST['output']) or empty($_REQUEST['scope']) or !in_array(
        strtolower($_REQUEST['scope']), [
                                          'category',
                                          'product',
                                          'department',
                                          'family',
                                          'website'
                                      ]
    ) or empty($_REQUEST['scope_key']) or !is_numeric($_REQUEST['scope_key'])) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

require __DIR__.'/keyring/dns.php';
$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
require_once __DIR__.'/../vendor/autoload.php';


if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key'] = get_website_key_from_domain($redis);
}

$website = get_object('Website', $_SESSION['website_key']);

$files = array();


switch ($_REQUEST['scope']) {
    case 'department':
    case 'family':

        $object = get_object('category', $_REQUEST['scope_key']);

        break;
    default:
        $object = get_object($_REQUEST['scope'], $_REQUEST['scope_key']);

}


if (!$object->id) {
    header("HTTP/1.0 401 Bad Request");
    exit;
}

if ($object->get('Store Key') != $website->get('Website Store Key')) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$output_type = strtolower($_REQUEST['output']);

if ($website->get('Website Type') == 'EcomDS') {
    $export_fields_type = 'portfolio_items';
} else {
    $export_fields_type = 'website_catalogue_items';

}


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
$export_fields = get_export_fields($export_fields_type);

foreach ($export_fields as $key => $field) {

    if ($use_php_excel and isset($field['type']) and $field['type'] == 'array') {
        unset($export_fields[$key]);
    }
    if (!$use_php_excel and isset($field['ignore_json'])) {
        unset($export_fields[$key]);
    };
}

$sql = "select ";
foreach ($export_fields as $field) {
    $sql .= $field['name'].',';
}
$sql = preg_replace('/,$/', ' from ', $sql);


switch ($_REQUEST['scope']) {
    case 'category':


        $sql .= "`Website Webpage Scope Map`  left join `Product Dimension` P on (P.`Product ID`=`Website Webpage Scope Scope Key`) ";
        if ($website->get('Website Type') == 'EcomDS') {
            $sql .= " left join `Customer Portfolio Fact` on (`Customer Portfolio Product ID`=`Website Webpage Scope Scope Key`)";
        }
        $sql .= "    where `Website Webpage Scope Scope`='Product'  and `Website Webpage Scope Type`='Category_Products_Item'  and `Website Webpage Scope Webpage Key`=? ";

        $sql_args = array(
            $object->get('Product Category Webpage Key')
        );

        break;
    case 'department':
    case 'family':


        $sql .= "`Product Dimension` P left join `Page Store Dimension` W on (W.`Page Key`=`Product Webpage Key`) ";
        if ($website->get('Website Type') == 'EcomDS') {
            $sql .= " left join `Customer Portfolio Fact` on (`Customer Portfolio Product ID`=P.`Product ID`)";
        }

        if ($_REQUEST['scope'] == 'department') {
            $sql .= "where `Product Department Category Key`=?  and `Webpage State`='Online' ";

        } else {
            $sql .= "where `Product Family Category Key`=?  and `Webpage State`='Online' ";

        }

        $sql_args = array(
            $object->id
        );


        break;
    case 'website':


        $sql .= "`Product Dimension` P left join `Page Store Dimension` W on (W.`Page Key`=`Product Webpage Key`) ";
        if ($website->get('Website Type') == 'EcomDS') {
            $sql .= " left join `Customer Portfolio Fact` on (`Customer Portfolio Product ID`=P.`Product ID`)";
        }

        $sql .= "where `Webpage Website Key`=?  and `Webpage State`='Online' ";


        $sql_args = array(
            $object->id
        );


        break;
    default:
        header("HTTP/1.0 400 Bad Request");
        exit;
}


$placeholders = array(
    '[image_address]' => 'https://'.$website->get('Website URL').'/wi/'
);

$sql = strtr($sql, $placeholders);

$stmt = $db->prepare($sql);
$stmt->execute($sql_args);


$title   = 'Category items';
$subject = 'Data feed';


if ($use_php_excel) {

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

            IOFactory::createWriter($objPHPExcel, 'Xlsx')->setSheetIndex(0)->save('php://output');
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

} else {

    $data_header = [];
    $data_rows   = [];
    $row_index   = 1;
    while ($row = $stmt->fetch()) {

        if ($row_index == 1) {
            $char_index = 1;


            foreach ($export_fields as $field) {

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

    switch ($output_type) {

        case('json'):
            header("Content-type: application/json; charset=utf-8");
            header('Content-Disposition: attachment;filename="'.$_REQUEST['scope'].'_'.gmdate('Ymd').'_json.txt"');

            header('Cache-Control: max-age=0');
            print json_encode($data);

    }
}
