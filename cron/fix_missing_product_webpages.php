<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2017 at 14:42:50 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
include_once 'class.Page.php';

include_once 'class.Public_Webpage.php';
include_once 'class.Public_Category.php';
/** @var PDO $db */


$sql = "SELECT `Product ID`,`Product Store Key`,`Product Code` FROM `Product Dimension`   WHERE  `Product Web State`!='Offline' and   `Product Public`='Yes' and  `Product Status`!='Discontinued' and    (`Product Webpage Key` IS NULL OR `Product Webpage Key`=0 )     ORDER BY `Product Code` DESC";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {



        $subject_webpage     = new Page('scope', 'Product', $row['Product ID']);
        $subject_webpage_key = $subject_webpage->id;

        $product = new Product($row['Product ID']);
        if ($subject_webpage_key > 0) {

            print 'Fix '.$product->id.' '.$product->get('Code')." :S \n";
            $product->update(array('Product Webpage Key' => $subject_webpage_key), 'no_history');

        } else {
            if ($product->get('Product Status') != 'Discontinued' and $product->get('Product Public') == 'Yes') {

                $store = get_object('store', $product->get('Store Key'));

                foreach ($store->get_websites('objects') as $website) {
                    printf("webpage for  %d  product %s %s , %s not found\n", $product->get('Store Key'), $product->get('Code'), $product->id, $product->get('Product Status'));

                    $website->create_product_webpage($product->id);
                }
               

            }


        }
    }


}