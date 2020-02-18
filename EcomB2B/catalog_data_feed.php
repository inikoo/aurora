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
        'product'
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

$object = get_object($_REQUEST['scope'], $_REQUEST['scope_key']);


if (!$object->id) {
    header("HTTP/1.0 401 Bad Request");
    exit;
}


if ($object->get('Store Key') != $website->get('Website Store Key')) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}


switch ($_REQUEST['scope']) {
    case 'category':

        include_once 'conf/export_fields.php';
        $export_fields = get_export_fields('portfolio_items');

        $sql = "select ";
        foreach ($export_fields as $field) {
            $sql .= $field['name'].',';
        }
        $sql = preg_replace('/,$/', ' from ', $sql);

        $sql .=  "`Website Webpage Scope Map`  left join `Product Dimension` P on (P.`Product ID`=`Website Webpage Scope Scope Key`) where `Website Webpage Scope Scope`='Product'  and `Website Webpage Scope Type`='Category_Products_Item'  and `Website Webpage Scope Webpage Key`=? ";




        $placeholders = array(
            '[image_address]' => 'https://'.$website->get('Website URL').'/wi.php?id='
        );

        $sql = strtr($sql, $placeholders);


        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $object->get('Product Category Webpage Key')
            )
        );
        $title = 'Category items';
        $subject = 'Data feed';

        break;

    default:
        header("HTTP/1.0 400 Bad Request");
        exit;
}


$objPHPExcel = new Spreadsheet();
Cell::setValueBinder(new AdvancedValueBinder());

$creator = 'aurora.systems';

$description = '';
$keywords = '';
$category = '';

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
        $char = number2alpha($char_index);


        $type = (empty($export_fields[$char_index - 1]['type']) ? '' : $export_fields[$char_index - 1]['type']);


        if ($type == 'html') {
            $_value = $value;
        } else {
            $_value = strip_tags($value);

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


