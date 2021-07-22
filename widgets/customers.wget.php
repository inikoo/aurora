<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2018 at 13:38:49 GMT+8, Kuala Lumpur,  Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_dashboard_customers($db, $user, $smarty, $parent, $currency) {


    include_once 'utils/date_functions.php';

    $smarty->assign('user', $user);


    if ($parent != '') {
        $object =  get_object('Store',$parent);
    } else {
        $object = get_object('Account',1);
    }
    $object->load_acc_data();
    $title = $object->get('Code');


    $stores = array();
    $sql    = "SELECT `Store Key`,`Store Code` FROM `Store Dimension` WHERE `Store Status`='Normal' or  `Store Status`='ClosingDown'";
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $stores[] = array(
                'key'  => $row['Store Key'],
                'code' => $row['Store Code']
            );

        }
    }

    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);
    $smarty->assign('currency', $currency);
    $smarty->assign('stores', $stores);

    return $smarty->fetch('dashboard/customers.dbard.tpl');

}

