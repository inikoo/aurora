<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 April 2017 at 10:49:57 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


if (!$user->can_supervisor('stores') or !in_array($state['store']->id, $user->stores)) {
    $html = '<div style="padding:20px"><i class="fa error fa-octagon padding_right_5" ></i>  '._("Sorry you don't have permission to access this area").'</div>';
} else {

    include_once 'utils/invalid_messages.php';
    include_once 'conf/object_fields.php';


    $website = get_object('Website', 0);

    $object_fields = get_object_fields(
        $website, $db, $user, $smarty, array(
                    'new'       => true,
                    'store_key' => $state['store']->id
                )
    );

    $smarty->assign('state', $state);
    $smarty->assign('object', $website);

    $smarty->assign('object_name', $website->get_object_name());
    $smarty->assign('object_fields', $object_fields);

    $html = $smarty->fetch('new_object.tpl');
}

