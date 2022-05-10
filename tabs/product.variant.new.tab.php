<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 09 May 2022 09:55:06 British Summer Time, Sheffield UK
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */


/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */

include_once 'utils/invalid_messages.php';
include_once 'conf/fields/product.fld.php';



if ($user->can_edit('stores') and in_array($state['store']->id, $user->stores)) {
    $object_fields = get_product_variant_fields(
        $state['_object'],  $user, $db, array(
                             'new' => true,
                             'parent_product' => $state['_parent']
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

