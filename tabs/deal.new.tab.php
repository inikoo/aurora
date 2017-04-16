<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2017 at 20:56:02 GMT+8, Cyberjaya, Malaysia
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



$smarty->assign(
    'js_code', 'js/injections/deal.new.'.(_DEVEL ? '' : 'min.').'js'
);

$html = $smarty->fetch('new_object.tpl');

?>
