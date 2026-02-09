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
    "SELECT `Product ID`  FROM `Product Dimension` WHERE `Product Store Key`=2  and `Product Status`!='Discontinued' order by `Product Code File As` "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $product = get_object('Product', $row['Product ID']);

        $sql = sprintf(
            "SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key` IN (10,14,16,23,21) and `Product Code`=%s and `Product Status`!='Discontinued' ",
            prepare_mysql($product->get('Code'))
        );

        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {
                $export_product = get_object('Product', $row2['Product ID']);

                if ($export_product->get('Store Currency Code') == 'CZK') {
                    $factor = 25;
                }elseif ($export_product->get('Store Currency Code') == 'RON') {
                    $factor = 5.1;
                } elseif ($export_product->get('Store Currency Code') == 'HUF') {
                    $factor = 400;
                } elseif ($export_product->get('Store Currency Code') == 'PLN') {
                    $factor = 4.3;
                } elseif ($export_product->get('Store Currency Code') == 'SEK') {
                    $factor = 11;
                } else {
                    continue;
                    //$factor = 1.3;
                }

                $editor                 = array(
                    'Author Alias' => '',
                    'Author Type'  => '',
                    'Author Key'   => '',
                    'User Key'     => 0,
                    'Date'         => gmdate('Y-m-d H:i:s'),
                    'Subject'      => 'System',
                    'Subject Key'  => 0,
                    'Author Name'  => sprintf('Script (update prices to match EU x %f)', $factor)
                );
                $export_product->editor = $editor;


                $price = round($product->get('Product Price') * $factor);


                if($product->get('Product Units Per Case')!=$export_product->get('Product Units Per Case')){
                    printf('Error different units per case: %s -> %s   =====\n', $product->get('Product Units Per Case'), $export_product->get('Product Units Per Case'));
                }else{
                    if ($price > 0) {
                        if (floatval(delta($price, $export_product->get('Product Price'))) > 30) {
                            printf("%s (%s): %.2f->%.2f %s\n", $product->get('Code'), $export_product->get('Store Code'), $export_product->get('Product Price'), $price, delta($price, $export_product->get('Product Price')));
                        }

                        if (floatval(delta($export_product->get('Product Price'), $price)) > 30) {
                            printf("%s (%s): %.2f->%.2f %s\n", $product->get('Code'), $export_product->get('Store Code'), $export_product->get('Product Price'), $price, delta($price, $export_product->get('Product Price')));
                        }
                        // $export_product->update(array('Product Price'=>$price));

                    }
                }



            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
