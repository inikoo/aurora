<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2017 at 12:19:26 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
include_once 'nano_services/migrate_order.ns.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$store_key = 7;

$account = new Account();

$print_est = true;
$sql       = sprintf("select count(*) as num FROM `Order Dimension` O left join `Store Dimension` on (`Store Key`=`Order Store Key`)  where `Store Key`=%d ", $store_key);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf('SELECT `Order Key` FROM `Order Dimension` O left join `Store Dimension` on (`Store Key`=`Order Store Key`)  where  `Order Key`=%d order by O.`Order Key` desc ', 2339791);

$sql = sprintf('SELECT `Order Key` FROM `Order Dimension` O left join `Store Dimension` on (`Store Key`=`Order Store Key`)  where  `Store Key`=%d order by O.`Order Key` desc ', $store_key);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $order = (new migrate_order($db))->migrate($row['Order Key']);

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }


    }


} else {
    print_r($error_info = $db->errorInfo());
    print $sql;
    exit;
}


?>
