<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 June 2018 at 00:15:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

require_once 'class.Part.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
require_once 'class.Warehouse.php';
require_once 'class.Product.php';

require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/new_fork.php';


$warehouse = get_object('Warehouse', 1);

//$warehouse->update_inventory_snapshot('2020-07-01');
//exit("--\n");

if($warehouse->get('Warehouse Valid From')==''){
    exit('error no Warehouse Valid From');
}

$from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
$to   = date("Y-m-d", strtotime('now'));


//$from='2020-07-01';
//$to='2020-07-01';


$sql = sprintf(
    "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC",
    prepare_mysql($from),
    prepare_mysql($to)
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {
                new_housekeeping_fork(
                    'au_elastic',
                    array(
                        'type'          => 'update_inventory_snapshot',
                        'warehouse_key' => $row2['Warehouse Key'],
                        'date'          => $row['Date']
                    ),
                    DNS_ACCOUNT_CODE,
                    $this->db
                );


                print $row['Date']."\r";
            }
        }
    }
}