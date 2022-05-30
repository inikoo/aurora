<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 30 May 2022 10:41:05 Central European Summer Time,Tranava, Slocakia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


/** @var PDO $db */
/** @var User $user */

require_once 'common.php';
if ($user->get('User View') != 'Staff') {
    exit;
}

if (!isset($_REQUEST['id'])) {
    exit;
}

$objPHPExcel = new Spreadsheet();
Cell::setValueBinder(new AdvancedValueBinder());

$creator = 'aurora.systems';

$description = '';
$keywords    = '';

$category    = get_object('Category',$_REQUEST['id']);

$columns_no_resize = array();

$title   = 'Variants';
$subject = 'Variants';


$objPHPExcel->getProperties()->setCreator($creator)->setLastModifiedBy($creator)->setTitle($title)->setSubject($subject)->setDescription($description)->setKeywords($keywords)->setCategory(
    ''
);

$where = " where P.`Product Type`='Product' and`Subject`='Product' and  `Category Key`=?  and is_variant='No' ";
$table = ' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) left join `Product Data` PD on (PD.`Product ID`=P.`Product ID`)  left join `Product DC Data` PDCD on (PDCD.`Product ID`=P.`Product ID`)  left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';

$data   = [];
$data[] = [
    'id'        => 'Id: Product ID',
    'id_master' => 'Id: Master Product ID',
    'type'      => 'Master',
    'code'      => 'Code',
    'name'      => 'Name',
    'label'     => 'Label',
    'parts'     => 'Parts',
    'units'     => 'Units per  outer',
    'price'     => 'Outer Price',
    'show'      => 'Show',
    'position'  => 'Position'
];


$sql = "select * from $table $where ";

$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $_REQUEST['id']
    ]
);

while ($row = $stmt->fetch()) {
    $product = get_object('Product', $row['Product ID']);
    $parts   = '';
    foreach ($product->get_parts_data() as $part_data) {
        $parts = number($part_data['Ratio']).'x '.$part_data['Part Reference'].', ';
    }
    $parts = preg_replace('/, $/', '', $parts);


    $data[] = [
        'id'        => $row['Product ID'],
        'id_master' => $row['variant_parent_id'],
        'type'      => 'Master',
        'code'      => $row['Product Code'],
        'name'      => $row['Product Name'],
        'label'     => $row['Product Variant Short Name'],
        'parts'     => $parts,
        'units'     => number($row['Product Units Per Case']),
        'price'     => $row['Product Price'],
        'show'      => '-',
        'position'  => $row['Product Variant Position'] < 10 ? 1 : $row['Product Variant Position'] / 10


    ];


    $sql   = "select * from `Product Dimension` where `is_variant`='Yes' and variant_parent_id=?";
    $stmt2 = $db->prepare($sql);
    $stmt2->execute(
        [
            $row['Product ID']
        ]
    );
    while ($row2 = $stmt2->fetch()) {
        $product = get_object('Product', $row2['Product ID']);

        $parts = '';
        foreach ($product->get_parts_data() as $part_data) {
            $parts = number($part_data['Ratio']).'x '.$part_data['Part Reference'].', ';
        }
        $parts = preg_replace('/, $/', '', $parts);

        $data[] = [
            'id'        => $row2['Product ID'],
            'id_master' => $row2['variant_parent_id'],
            'type'      => $row['Product Code'].' variant',
            'code'      => $row2['Product Code'],
            'name'      => $row2['Product Name'],
            'label'     => $row2['Product Variant Short Name'],
            'parts'     => $parts,
            'units'     => number($row2['Product Units Per Case']),
            'price'     => $row2['Product Price'],
            'show'      => $row2['Product Show Variant'],
            'position'  => $row2['Product Variant Position'] / 10

        ];
    }


    $data[] = [
        'id'        => 'NEW',
        'id_master' => $row['variant_parent_id'],
        'type'      => $row['Product Code'].' variant',
        'code'      => '',
        'name'      => '',
        'label'     => '',
        'parts'     => '',
        'units'     => '',
        'price'     => '',
        'show'      => ''
    ];
    $data[] = [
        'id'        => '',
        'id_master' => '',
        'type'      => '',
        'code'      => '',
        'name'      => '',
        'label'     => '',
        'parts'     => '',
        'units'     => '',
        'price'     => '',
        'show'      => ''
    ];

}


$row_index = 1;
foreach($data as $columns){
    $char_index = 1;
    foreach($columns as $column){

        $char = number2alpha($char_index);

        $objPHPExcel->getActiveSheet()->setCellValue(
            $char.$row_index, $column
        );


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




header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.'variants_'.$category->get('Code').'_'.gmdate('Ymd').'.xls"');

header('Cache-Control: max-age=0');
IOFactory::createWriter($objPHPExcel, 'Xls')->save('php://output');

