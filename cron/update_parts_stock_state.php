<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 April 2016 at 17:39:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';


//$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Key`=24 ');
$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU` DESC  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);
        $part->update_available_forecast();
        $part->update_stock_status();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $supplier = new Supplier($row['Supplier Key']);

        $supplier->update_supplier_parts();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

?>
