<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 21:11:20 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/



include_once 'conf/object_fields.php';


if (!$user->can_view('orders') or !in_array(
    $state['store']->id, $user->stores
)
) {
    $html = '';
} else {

    include_once 'utils/invalid_messages.php';



    $object_fields = get_object_fields($state['_object'], $db, $user, $smarty);

    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);

    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);

    $html = $smarty->fetch('edit_object.tpl');

}



?>
