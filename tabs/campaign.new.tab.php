<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 April 2017 at 15:58:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';



$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
                         'parent'              => 'store',
                         'parent_object'       => $state['_parent'],
                         'new'                 => true,
                         'supplier_part_scope' => true
                     )
);
$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('object_name', $state['_object']->get_object_name());
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');

?>
