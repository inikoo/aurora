<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:4 April 2017 at 14:00:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Store.php';
require_once 'utils/natural_language.php';



$account->load_acc_data();
$account->redis=$redis;

$key = '_acc_'.$account->get('Account Code');

$redis->hSet(
    $key, 'name',$account->get('Name')
);





$account->update_suppliers_data();

$account->update_production_job_orders_stats();

$account->update_dispatching_time_data('1m');
$account->update_sitting_time_in_warehouse();

$account->update_parts_data();

$account->update_orders();


$account->update_active_parts_stock_data();


$account->update_stores_data();
$account->update_warehouses_data();

$account->update_customers_data();
$account->update_employees_data();

$account->update_sales_from_invoices('Total');

$account->update_sales_from_invoices('1 Year');
$account->update_sales_from_invoices('1 Quarter');
$account->update_sales_from_invoices('1 Month');
$account->update_sales_from_invoices('1 Week');

$account->update_sales_from_invoices('Year To Day');
$account->update_sales_from_invoices('Quarter To Day');
$account->update_sales_from_invoices('Month To Day');
$account->update_sales_from_invoices('Week To Day');

$account->update_sales_from_invoices('Last Month');
$account->update_sales_from_invoices('Last Week');

$account->update_sales_from_invoices('Yesterday');
$account->update_sales_from_invoices('Today');

$account->update_previous_years_data();
$account->update_previous_quarters_data();



$account->update_inventory_dispatched_data('ytd');
$account->update_inventory_dispatched_data('qt');
$account->update_inventory_dispatched_data('all');


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);

        $store->load_acc_data();



        $store->update_orders();


        $store->fast_update(
            array('Store Today Start Orders In Warehouse Number'=>$store->get('Store Orders In Warehouse Number')+$store->get('Store Orders Packed Number')+$store->get('Store Orders Dispatch Approved Number'))

        );

        $store->update_new_products();

    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

