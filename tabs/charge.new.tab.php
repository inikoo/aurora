<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27-05-2019 19:17:35 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';


$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
                         'parent'              => $state['parent'],
                         'parent_object'       => $state['_parent'],
                         'new'                 => true,
                         'store_key'=>$state['_parent']->get('Store Key')
                     )
);
$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('object_name', $state['_object']->get_object_name());
$smarty->assign('object_fields', $object_fields);

$smarty->assign('store_key', $state['_parent']->get('Store Key'));




$html = $smarty->fetch('new_object.tpl');

