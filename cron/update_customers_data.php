<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2017 at 09:34:36 CET, MIjas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

require_once 'class.Customer.php';
/** @var PDO $db */


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

$where =' and  `Customer Type by Activity`="Lost"  ';

$sql = sprintf("select count(*) as num from `Customer Dimension` left join `Store Dimension` on (`Store Key`=`Customer Store Key`) $where");
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;


$sql = "select `Customer Key` from `Customer Dimension`  left join `Store Dimension` on (`Store Key`=`Customer Store Key`)  $where order by `Customer Key` desc ";


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $customer = new Customer('id', $row['Customer Key']);


      //  $customer->update_orders();
        $customer->update_activity();

       // $customer->update_portfolio();
       // $customer->update_invoices();
       // $customer->update_last_dispatched_order_key();
        //$customer->update_invoices();
        //$customer->update_payments();

        //$customer->update_account_balance();
        //$customer->update_credit_account_running_balances();

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }

    }

}


$sql = "SELECT `Store Key` FROM `Store Dimension`";
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('Store', $row['Store Key']);
        $store->update_customers_data();
    }
}


