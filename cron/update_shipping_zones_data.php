<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23-07-2019 17:33:26 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf("SELECT `Shipping Zone Key` FROM `Shipping Zone Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $shipper_zone = get_object('Shipping Zone',$row['Shipping Zone Key']);

        $shipper_zone->update_usage();

    }

}



$sql = sprintf("SELECT `Shipping Zone Schema Key` FROM `Shipping Zone Schema Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $shipper_zone_schema = get_object('Shipping Zone Schema',$row['Shipping Zone Schema Key']);

        $shipper_zone_schema->update_shipping_zones();
        $shipper_zone_schema->update_usage();

    }

}


