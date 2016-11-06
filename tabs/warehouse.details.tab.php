<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 September 2015 13:22:27 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {

    include_once 'utils/invalid_messages.php';

    $warehouse = $state['_object'];

    $object_fields = get_object_fields($warehouse, $db, $user, $smarty);


    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);

    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);


    $html = $smarty->fetch('edit_object.tpl');
}

?>
