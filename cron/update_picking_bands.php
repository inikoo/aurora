<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 Abril 2021, 22:40 , Kuala Lumpur Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf("SELECT `Picking Band Key` FROM `Picking Band Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $band = get_object('PickingBand', $row['Picking Band Key']);


        $band->update_parts();
        $band->update_usage();

    }
}