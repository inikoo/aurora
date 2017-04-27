<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:4 April 2017 at 14:00:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Store.php';
require_once 'utils/natural_language.php';


$account->update_stores_data();
$account->update_suppliers_data();
$account->update_warehouses_data();
$account->update_parts_data();



$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);

        $store->load_acc_data();

        $store->update_orders_in_basket_data();
        $store->update_orders_in_process_data();
        $store->update_orders_in_warehouse_data();
        $store->update_orders_packed_data();
        $store->update_orders_ready_to_ship_data();

        $store->update(
            array('Store Today Start Orders In Warehouse Number'=>$store->get('Store Orders In Warehouse Number')+$store->get('Store Orders Packed Number')+$store->get('Store Orders In Dispatch Area Number'))

        );

        $store->update_new_products();

    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}




?>
