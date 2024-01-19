<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 28 Jul 2021 00:37:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


require_once __DIR__.'/cron_common.php';

/** @var PDO $db */


$sql = 'SELECT `Location Key` FROM sk.`Location Dimension`  where `Location Warehouse Area Key` in (18,72,71,15)  ';

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        /** @var $location \Location */

        $location = get_object('Location', $row['Location Key']);
        print $location->get('Location Code')."\n";
        $location->delete();

    }

}
