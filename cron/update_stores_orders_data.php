<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2016 at 11:01:50 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
/** @var  $db PDO */


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


$account = new Account();

$account->update_orders_bis();



$sql = "SELECT `Store Key` FROM `Store Dimension` where `Store Status` in ('Normal','ClosingDown') ";
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('store', $row['Store Key']);


        $store->update_invoices_bis();
        $store->update_customers_data_bis();

        $store->update_orders_bis();
        $store->update_payments_bis();


    }

}

