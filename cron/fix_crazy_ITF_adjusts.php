<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

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
    'Author Name'  => 'Script (Magento import)'
);


$sql = sprintf('  select * from `Inventory Transaction Fact` where  `Inventory Transaction Type`="Adjust" and (`Inventory Transaction Amount`>999999999 or `Inventory Transaction Amount`<-999999999  ) ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = get_object('Part', $row['Part SKU']);





        $sql = sprintf(
            'update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%.3f where `Inventory Transaction Key`=%d  ', $part->get('Part Cost') * $row['Inventory Transaction Quantity'], $row['Inventory Transaction Key']
        );


        print $part->get('Part Cost')." ".$row['Inventory Transaction Quantity']."  $sql\n";
$db->exec($sql);

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


