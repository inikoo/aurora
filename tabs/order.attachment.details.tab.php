<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 17 Jul 2021 06:04:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


include_once 'utils/invalid_messages.php';
include_once 'conf/fields/attachment.fld.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */


if (!(in_array($state['store']->id, $user->stores) and $user->can_view('orders'))) {
    $html = '';
} else {
    include_once 'utils/invalid_messages.php';
    $object_fields = get_attachment_fields($state['_object'], $user,array(
        'type' => 'order'
    ));
    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);
    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);

    try {
        $html = $smarty->fetch('edit_object.tpl');
    } catch (Exception $e) {
        $html = '';
    }
}