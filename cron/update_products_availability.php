<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 19:45:26 GMT+8, Kuala Lumpur, Malaysia
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




    $sql = sprintf(
        "SELECT `Product ID` FROM `Product Dimension`  "
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $product = new Product($row['Product ID']);
                  $product->update_availability();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }




?>
