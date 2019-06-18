<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 September 2018 at 07:44:33 GMT-5
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';



$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (fix missing barcodes)'
);


$sql = sprintf('SELECT `Part SKU`,`Part Barcode Number` FROM `Part Dimension` where `Part Barcode Number`!=""  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $part = get_object('Part', $row['Part SKU']);
        foreach ($part->get_products('objects') as $product) {

            if (count($product->get_parts()) == 1) {
                $product->editor = $editor;
                $product->update(
                    array('Product Barcode Number' => $row['Part Barcode Number']), ' from_part'
                );
            }

        }


    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


