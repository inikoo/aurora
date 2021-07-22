<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 March 2016 at 16:04:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';
include_once 'conf/fields/store.new.fld.php';
include_once 'class.Store.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */


if ($user->can_supervisor('stores')) {


    $store = new Store(0);

    $object_fields = get_new_store_fields($store, $user, $db, $smarty);

    $smarty->assign('state', $state);
    $smarty->assign('object', $store);

    $smarty->assign('object_name', $store->get_object_name());
    $smarty->assign('object_fields', $object_fields);



    try {
        $html = $smarty->fetch('new_object.tpl');
    } catch (Exception $e) {
        $html='';
    }
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
        $html='';
    }
}







