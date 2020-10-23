<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 January 2018 at 12:51:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Move stock new warehouse)'
);


$sql = "select PL.`Location Key`,PL.`Part SKU`  from `Part Location Dimension` PL  where `Location Key`=1   ";

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);

$parts = [];

while ($row = $stmt->fetch()) {

    print_r($row);

    $part_location         = new PartLocation($row['Part SKU'], $row['Location Key']);
    $part_location->editor = $editor;
    if ($part_location->get('Quantity On Hand') == 0) {

        $part_location->disassociate();


    } else {



        if ($part_location->get('Quantity On Hand') > 0) {
            $transaction_type = 'Lost';

        } else {
            $transaction_type = 'Other Out';
        }

        $_data = array(
            'Quantity'         => -1 * $part_location->get('Quantity On Hand'),
            'Transaction Type' => $transaction_type,
            'Note'             => 'auto fix'
        );

        $part_location->stock_transfer($_data);
        $part_location->disassociate();


    }

    $part_location->part->update_leakages();
}