<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11:13 am Thursday, 12 November 2020 (MYT), Kuala Lumpur , Malysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';



$sql = sprintf("SELECT `Delivery Note Key` FROM `Delivery Note Dimension` where `Delivery Note State` not in ('Dispatched','Cancelled') ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = get_object('Delivery Note', $row['Delivery Note Key']);
        $dn->update_shippers_services();
        print_r($dn->get_shippers_services());

    }

}

