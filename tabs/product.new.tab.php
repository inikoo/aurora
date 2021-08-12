<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 February 2016 at 09:27:25 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */

include_once 'utils/invalid_messages.php';
include_once 'conf/fields/product.fld.php';

if ($user->can_edit('stores') and in_array($state['store']->id, $user->stores)) {
    $object_fields = get_product_fields(
        $state['_object'],  $user, $db, array(
                             'new' => true,
                             'store_key' => $state['store']->id
                         )
    );

    $smarty->assign('state', $state);
    $smarty->assign('object', $state['_object']);
    $smarty->assign('object_name', $state['_object']->get_object_name());
    $smarty->assign('object_fields', $object_fields);

    try {
        $html = $smarty->fetch('new_object.tpl');
    } catch (Exception $e) {
    }
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }
}

