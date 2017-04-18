<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';



require_once 'class.Product.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


print date('l jS \of F Y h:i:s A')."\n";

//update_fields_from_parts($db);
//print "updated fiels from parts\n";
//update_web_state($db);

update_categories_data($db);

update_products_data($db);



function update_products_data($db) {

    $sql = sprintf(
        "SELECT `Product ID` FROM `Product Dimension`  "
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $product = new Product($row['Product ID']);
            $product->update_part_numbers();
            $product->update_order_numbers();
            $product->update_customers_favored_numbers();

            $product->update_history_records_data();
            $product->update_images_data();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function update_categories_data($db) {

    $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE `Category Subject`='Product'  and `Category Key`=17400");

    $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE `Category Subject`='Product'" );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $category = new Category($row['Category Key']);
            $category->update_product_category_new_products();
            $category->update_product_category_products_data();
            $category->update_images_data();
            $category->update_history_records_data();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function update_web_state($db) {

    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`!=9 ORDER BY `Product ID` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);

            $product->update_part_numbers();

            $old_webstate = $product->get('Product Web State');

            $product->update_availability($use_fork = false);
            $product->update_cost();

            $new_webstate = $product->get('Product Web State');

            if ($old_webstate != $new_webstate) {
                print $product->id." ".$product->get('Product Store Key')." ".$product->get('Code')." $old_webstate  $new_webstate  \n";
            }

            //print $product->id."\r";
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function update_fields_from_parts($db) {

    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);
            print $part->id."\r";
            $part->updated_linked_products();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


?>
