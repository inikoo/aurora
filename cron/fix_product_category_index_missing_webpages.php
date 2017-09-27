<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 September 2017 at 22:12:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf('SELECT * FROM `Product Category Index`  WHERE ( `Product Category Index Product Webpage Key` IS NULL  OR `Product Category Index Product Webpage Key`=0 )  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
       // print_r($row);

        $product = get_object('Product', $row['Product Category Index Product ID']);
       // print_r($product);

        $webpage_key = $product->get('Product Webpage Key');

        // print_r($category->get('Product Category Webpage Key'));


        $sql = sprintf(
            'UPDATE `Product Category Index` SET `Product Category Index Product Webpage Key`=%s WHERE `Product Category Index Key`=%d  ',
            prepare_mysql($webpage_key), $row['Product Category Index Key']
        );
        print "$sql\n";


        $db->exec($sql);



    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}

$sql = sprintf('SELECT * FROM `Product Category Index`  WHERE ( `Product Category Index Website Key` IS NULL  OR `Product Category Index Website Key`=0 )  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
       // print_r($row);

        $category = get_object('Category', $row['Product Category Index Category Key']);


        $webpage_key = $category->get('Product Category Webpage Key');

        // print_r($category->get('Product Category Webpage Key'));


        $sql = sprintf(
            'UPDATE `Product Category Index` SET `Product Category Index Website Key`=%s WHERE `Product Category Index Key`=%d  ',
            prepare_mysql($webpage_key),
            $row['Product Category Index Key']
        );
        print "$sql\n";
        //exit;

        $db->exec($sql);



    }
} else {
    print_r($error_info = $this->db->errorInfo());
    print "$sql\n";
    exit;
}




?>
