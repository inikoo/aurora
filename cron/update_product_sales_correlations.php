<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 April 2018 at 14:37:59 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf('SELECT `Product ID` FROM `Product Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = get_object('Product', $row['Product ID']);
        $product->update_sales_correlations();

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
