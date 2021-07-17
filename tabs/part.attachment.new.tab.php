<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2016 at 00:15:35 GMT+8, Plane Kuala Lumpur, Malaysia -> Denpasar, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'conf/fields/attachment.fld.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

if ($user->can_edit('parts')) {
    include_once 'utils/invalid_messages.php';
    $object_fields = get_attachment_fields(
        $state['_object'], $user, array(
        'new'  => true,
        'type' => 'part'
    )
    );

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
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }

}
