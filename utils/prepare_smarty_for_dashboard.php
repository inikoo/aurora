<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   19 December 2019  14:37::11  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

/**
 * @param $db \PDO
 * @param $account \Account
 * @param $user \User
 * @param $smarty \Smarty
 * @param $parent
 * @param $currency
 *
 * @return mixed
 */
function prepare_smarty_for_dashboard($db, $account, $user, $smarty, $parent, $currency){

    $smarty->assign('user', $user);
    if ($parent != '') {
        $object = get_object('Store', $parent);
        $object->load_acc_data();
        $title = $object->get('Code');

    } else {
        $object = $account;
        $object->load_acc_data();
        $title = $object->get('Code');
    }


    $stores = array();
    $sql    = "SELECT `Store Key`,`Store Code` FROM `Store Dimension` WHERE `Store Status`='Normal'  or `Store Status`='ClosingDown' ";
    $stmt   = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $stores[] = array(
            'key'  => $row['Store Key'],
            'code' => $row['Store Code']
        );
    }
    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);
    $smarty->assign('currency', $currency);
    $smarty->assign('stores', $stores);

    return $smarty;

}