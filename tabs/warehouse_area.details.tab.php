<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2018 at 19:31:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

if (!$user->can_view('locations') or !in_array($state['warehouse']->id, $user->warehouses)
) {
    $html = '';
} else {

    include_once 'utils/invalid_messages.php';

    $warehouse_area = $state['_object'];

    $object_fields = get_object_fields($warehouse_area, $db, $user, $smarty);


    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);

    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);


    $html = $smarty->fetch('edit_object.tpl');



}

?>
