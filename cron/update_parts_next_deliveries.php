<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 January 2018 at 20:21:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

//select `Part Reference`,`Supplier Delivery State`,`Supplier Delivery Transaction State` from `Purchase Order Transaction Fact` POTF LEFT JOIN                  `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)  left join                   `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join  `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`) where   POTF.`Supplier Delivery Key` IS NOT NULL AND `Supplier Delivery Transaction State` in ('InProcess','Dispatched','Received','Checked') and `Supplier Delivery State` in ('Placed','Costing','Cancelled','InvoiceChecked')  ;
//update  `Purchase Order Transaction Fact` POTF LEFT JOIN                  `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`) set `Supplier Delivery Transaction State`='Cancelled' , `Purchase Order Transaction State`='Cancelled' where  POTF.`Supplier Delivery Key` IS NOT NULL AND `Supplier Delivery Transaction State` in ('InProcess','Dispatched','Received','Checked') and `Supplier Delivery State` in ('Placed','Costing','Cancelled','InvoiceChecked');


$sql = sprintf(
    'SELECT `Part SKU`,`Part Reference` FROM `Part Dimension`  where `Part Status` in ("Discontinuing","In Use")   ORDER BY `Part Reference`  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $part =  get_object('Part',$row['Part SKU']);
        $part->update_next_deliveries_data();
        print $row['Part Reference']."\r";

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;

}




