<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 11:55:40 BST Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';






$sql = sprintf("SELECT `Delivery Note Key` FROM `Delivery Note Dimension`   ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = get_object('Delivery Note', $row['Delivery Note Key']);

        $dn->update_totals();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

