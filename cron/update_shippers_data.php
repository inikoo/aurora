<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 March 2019 at 14:54:10 GMT+8, Sanur, bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf("SELECT `Shipper Key` FROM `Shipper Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $shipper = get_object('Shipper',$row['Shipper Key']);

        $shipper->update_shipper_usage();
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
