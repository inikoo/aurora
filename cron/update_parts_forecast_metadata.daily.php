<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2017 at 19:41:08 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="FO-P7" ORDER BY `Part SKU` DESC  '
);
$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);

        $part->calculate_forecast_data();


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
