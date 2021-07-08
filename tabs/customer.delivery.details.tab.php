<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 08 Jul 2021 23:57:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */


if (!$user->can_view('fulfilment')) {
    $html = '';
} else {

    include_once 'utils/invalid_messages.php';


    $object_fields = get_object_fields($state['_object'], $db, $user, $smarty);

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

