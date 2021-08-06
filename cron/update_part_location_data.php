<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 15:00:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
include_once 'class.PartLocation.php';
/** @var PDO $db */


//$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Key`=24 ');
$sql = sprintf(
    'SELECT `Part SKU` , `Location Key` FROM `Part Location Dimension`  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $part_location = new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
        $part_location->update_stock_value();

    }

}
