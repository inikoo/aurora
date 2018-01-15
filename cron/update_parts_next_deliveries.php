<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 January 2018 at 20:21:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);
        $part->update_next_deliveries_data();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
}


?>
