<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2018 at 18:45:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3
*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$object_fields = get_object_fields($state['_object'], $db, $user, $smarty, array('store'=>$state['store'],'store_key'=>$state['_object']->get('Store Key')));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>