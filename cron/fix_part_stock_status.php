<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 January 2018 at 19:38:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';



$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (fix stock status)'
);


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU` DESC  '
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);
        $part->editor=$editor;
        $part->update_delivery_days();
        $part->update_available_forecast();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>


