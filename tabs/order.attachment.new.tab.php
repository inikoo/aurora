<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 17 Jul 2021 02:46:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
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
        'new' => true,
        'type' => 'order'
    ));

    $smarty->assign('object_name', 'Attachment');
    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);
    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);


    try {
        $html = $smarty->fetch('new_object.tpl');
    } catch (Exception $e) {
        $html = '';
    }
}

