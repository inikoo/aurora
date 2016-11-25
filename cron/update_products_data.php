<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected = mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


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


function update_categories_data($db) {

    $sql = sprintf(
        "SELECT `Category Key` FROM `Category Dimension` WHERE `Category Subject`='Product' "
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $category = new Category($row['Category Key']);
            $category->update_product_category_new_products();
            $category->update_product_category_products_data();
           
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
