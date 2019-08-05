<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2018 at 13:47:57 GMT+8. Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
                         'parent'              => $state['parent'],
                         'parent_object'       => $state['_parent'],
                         'new'                 => true,
                         'store_key'=>$state['_parent']->get('Store Key'),
                     )
);
$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('object_name', 'Mailshot');
$smarty->assign('object_fields', $object_fields);

$smarty->assign('store_key', $state['_parent']->get('Store Key'));


$smarty->assign('js_code', 'js/injections/mailshot.new.'.(_DEVEL ? '' : 'min.').'js');

$html = $smarty->fetch('new_object.tpl');


