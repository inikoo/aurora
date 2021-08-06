<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2017 at 11:47:48 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/object_functions.php';


$print_est = true;

$sql = sprintf(
    'SELECT `Barcode Key` FROM `Barcode Dimension` '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $barcode = get_object('Barcode', $row['Barcode Key']);
        $barcode->update_status();
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>