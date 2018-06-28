<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 October 2017 at 22:31:32 GMT+8, Plane Bali - Kuala Lumpur
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/object_functions.php';


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="EO-01" ORDER BY `Part SKU` DESC  '
);
$sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  desc ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $part = get_object('Part', $row['Part SKU']);


        $part->update_stock_run();
        print $part->get('Reference')."\r";

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
