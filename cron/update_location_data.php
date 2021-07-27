<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 28 Jul 2021 00:37:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


require_once __DIR__.'/common.php';

/** @var PDO $db */


$sql = 'SELECT `Location Key` FROM `Location Dimension`  ';

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        /** @var $location \Location */
        $location = get_object('Location', $row['Location Key']);
        $location->update_fulfilment_status();
        $location->update_pipeline_status();

    }

}
