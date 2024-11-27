<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 October 2016 at 12:26:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";
//$store = new Store('id',1);
//$store->update_sales_from_invoices('Week To Day');
//exit;


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension` WHERE `Store Status` = 'Normal'");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);
        $store->update_orders();


    }

}
