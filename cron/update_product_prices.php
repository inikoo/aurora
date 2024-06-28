<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2018 at 20:30:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$sql = sprintf(
    "SELECT `Product ID`  FROM `Product Dimension` WHERE `Product Store Key`=1  and `Product Status`!='Discontinued' order by `Product Code File As` "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $product = get_object('Product', $row['Product ID']);


        $percentage=6;

        $factor = 1+($percentage/100);


        $editor = array(
            'Author Name' => '',
            'Author Alias' => '',
            'Author Type' => '',
            'Author Key' => '',
            'User Key' => 0,
            'Date' => gmdate('Y-m-d H:i:s'),
            'Subject' => 'System',
            'Subject Key' => 0,
            'Author Name' => sprintf('Script (increase prices %d', $percentage).'%)'
        );


        $product->editor = $editor;


        $price = round($product->get('Product Price') * $factor,2);


        if ($price > 0) {


                printf("%s (%s): %.2f->%.2f %s\n", $product->get('Code'), $product->get('Store Code'), $product->get('Product Price'), $price, delta($price, $product->get('Product Price')));




             $product->update(array('Product Price'=>$price));

        }

    }
}
