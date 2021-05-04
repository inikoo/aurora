<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 11:55:40 BST Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';




$sql = sprintf("SELECT `Delivery Note Key` FROM `Delivery Note Dimension` where `Delivery Note Date`>'2021-01-01'  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = get_object('Delivery Note', $row['Delivery Note Key']);

        if($dn->get('State Index')>=80){
            $dn->update_picking_packing_bands();
        }

        //$dn->update_totals();
       // $dn->update_uuid();

    }

}

