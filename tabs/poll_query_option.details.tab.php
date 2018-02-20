<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2018 at 13:28:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3
*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$object_fields = get_object_fields($state['_object'], $db, $user, $smarty, array('store'=>$state['store'],'store_key'=>$state['_object']->get('Store Key')));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>