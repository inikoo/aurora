<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 13:33:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';




$sql = sprintf('SELECT `Warehouse Key`  FROM `Warehouse Dimension`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $warehouse = get_object('Warehouse',$row['Warehouse Key']);
        $warehouse->update_delivery_notes();
     
    }
}




$sql = sprintf('SELECT `Warehouse Area Key`  FROM `Warehouse Area Dimension`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $warehouse_area = get_object('WarehouseArea',$row['Warehouse Area Key']);

        $warehouse_area->update_warehouse_area_locations();
        $warehouse_area->update_warehouse_area_number_parts();

    }
}




$sql = sprintf('SELECT `Location Key`  FROM `Location Dimension`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $location = get_object('Location',$row['Location Key']);

        $location->update_parts();
        $location->update_stock_value();

    }
}

