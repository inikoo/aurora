<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 13:29:56 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/fields/product.fld.php';

/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */


if ($user->can_edit('stores') and in_array($state['store']->id, $user->stores)) {
    $product = $state['_object'];
    $product->get_webpage();
    $product->get_store_data();

    $object_fields = get_product_fields(
        $product, $user, $db, array('show_full_label' => false)
    );

    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);
    $smarty->assign('object', $product);

    try {
        $html = $smarty->fetch('edit_object.tpl');
    } catch (Exception $e) {
    }
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }
}


