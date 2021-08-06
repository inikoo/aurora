<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2016 at 14:18:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
require_once 'class.Agent.php';


$sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $agent = new Agent($row['Agent Key']);
        $agent->update_supplier_parts();
        $agent->update_purchase_orders();


    }

}


$sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $supplier = new Supplier($row['Supplier Key']);
        $supplier->update_supplier_parts();
        $supplier->update_history_records_data();
        $supplier->update_attachments_data();

        $supplier->update_purchase_orders();


    }

}