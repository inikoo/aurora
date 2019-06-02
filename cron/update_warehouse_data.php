<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 13:33:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';




$sql = sprintf('SELECT `Warehouse Key`  FROM `Warehouse Dimension`  ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $warehouse = get_object('Warehouse',$row['Warehouse Key']);

        $warehouse->update_delivery_notes();
     
    }
}


