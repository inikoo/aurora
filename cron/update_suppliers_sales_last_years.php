<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 October 2016 at 23:33:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Agent.php';
require_once 'class.Supplier.php';
require_once 'class.Category.php';


$sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $agent = new Agent($row['Agent Key']);
        $agent->load_acc_data();
        $agent->update_previous_years_data();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $supplier = new Supplier($row['Supplier Key']);

        $supplier->load_acc_data();

        $supplier->update_previous_years_data();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$sql = sprintf(
    "SELECT `Category Key` FROM `Category Dimension` WHERE   `Category Scope`='Supplier'  "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Category Key']);

        $category->update_supplier_category_previous_years_data();


    }
}

?>
