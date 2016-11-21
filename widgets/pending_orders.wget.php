<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2016 at 12:31:50 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_dashboard_pending_orders($db, $account, $user, $smarty, $parent = '', $currency, $display_device_version = 'desktop') {


    include_once 'utils/date_functions.php';

    $smarty->assign('user', $user);


    if ($parent != '') {
        include_once 'class.Store.php';

        $object = new Store($parent);
        $object->load_acc_data();

        //  print_r($_object);
        $title = $object->get('Code');

    } else {
        $object = new Account();
        $object->load_acc_data();
        $title = $object->get('Code');
    }


   // $object->update_orders_in_basket_data();
    $object->update_orders_in_process_data();
    $object->update_orders_in_warehouse_data();
    $object->update_orders_packed_data();
    $object->update_orders_ready_to_ship_data();


    $stores = array();
    $sql    = sprintf('SELECT `Store Key`,`Store Code` FROM `Store Dimension` WHERE `Store State`="Normal" ');
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $stores[] = array(
                'key'  => $row['Store Key'],
                'code' => $row['Store Code']
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);
    $smarty->assign('currency', $currency);
    $smarty->assign('stores', $stores);


    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/pending_orders.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/pending_orders.dbard.tpl');
    }
}


?>
