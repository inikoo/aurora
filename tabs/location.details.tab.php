<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 17:28:22 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


if (!$user->can_view('locations') or !in_array($state['warehouse']->id, $user->warehouses)) {
    $html = '';
} else {


    
    $warehouse=get_object('Warehouse',$_SESSION['current_warehouse']);
    if($warehouse->get('Warehouse Unknown Location Key')==$state['key']){
        $html ='<div style="padding:20px">'._("This is a special location, can't be edited").'</div>';
    }else{
        include_once 'utils/invalid_messages.php';

        $location = $state['_object'];

        $object_fields = get_object_fields($location, $db, $user, $smarty,[
            'parent'=>$state['parent']
        ]);

        $smarty->assign('object', $state['_object']);
        $smarty->assign('key', $state['key']);

        $smarty->assign('object_fields', $object_fields);
        $smarty->assign('state', $state);


        $html = $smarty->fetch('edit_object.tpl');
    }



}

