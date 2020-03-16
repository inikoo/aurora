<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2018 at 13:38:49 GMT+8, Kuala Lumpur,  Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_dashboard_customers($db, $account, $user, $smarty, $parent = '', $currency, $display_device_version = 'desktop') {


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


    $stores = array();
    $sql    = sprintf('SELECT `Store Key`,`Store Code` FROM `Store Dimension` WHERE `Store Status`="Normal" or  `Store Status`="ClosingDown" ');
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
        return $smarty->fetch('dashboard/customers.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/customers.dbard.tpl');
    }
}


?>
