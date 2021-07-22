<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2015 at 18:04:35 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';
include_once 'conf/fields/attachment.fld.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */


if ($user->can_edit('Staff')) {
    include_once 'utils/invalid_messages.php';
    $object_fields = get_attachment_fields($state['_object'], $user,array(
        'type' => 'employee'
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
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }
}

