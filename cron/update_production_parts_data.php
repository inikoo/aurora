<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  30 January 2019 at 15:02:57 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

require_once __DIR__.'/cron_common.php';


$sql = "select `Production Part Supplier Part Key` from  `Production Part Dimension` ";


$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $production_part = get_object('Production Part', $row['Production Part Supplier Part Key']);

    if ($production_part->id) {
        $number_raw_materials = count($production_part->get_raw_materials());
        $production_part->fast_update(array('Production Part Raw Materials Number' => $number_raw_materials));

    }
}


$sql = "select `Raw Material Key` from  `Raw Material Dimension` ";


$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $raw_material = get_object('RawMaterial', $row['Raw Material Key']);

    if ($production_part->id) {
        $raw_material->production_parts_number();

    }
}
