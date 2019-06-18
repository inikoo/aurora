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
$store  = get_object('Store', 9);


$sql = sprintf('select  `Delivery Note Key`   from `Delivery Note Dimension` where  `Delivery Note Number Ordered Parts`=0   ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $dn = get_object('Delivery Note', $row['Delivery Note Key']);


        print $dn->id.' '.$dn->get('`Delivery Note ID').' '.$dn->get('Delivery Note Date').' '.$dn->get('Delivery Note Number Ordered Parts')."\n";

        $dn->delete(true);


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


