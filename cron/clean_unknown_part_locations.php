<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 January 2018 at 12:51:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (clean zero Unk PL)'
);


$sql = sprintf('select `Warehouse Key`,`Warehouse Unknown Location Key` from `Warehouse Dimension` ');
if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {

        $sql = sprintf('SELECT `Part SKU` FROM `Part Location Dimension`  WHERE `Location Key`=%d AND `Quantity On Hand`=0 AND `Quantity In Process`=0 ', $row2['Warehouse Unknown Location Key']);
        print $sql;
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $part_location         = new PartLocation($row['Part SKU'].'_'.$row2['Warehouse Unknown Location Key']);
                $part_location->editor = $editor;
                $part_location->disassociate(array('Note' => _('Automatic cleaning of zero stock unknown locations')));

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
