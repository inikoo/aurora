<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 13:59:41 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$deal_component = $state['_object'];
$object_fields  = get_object_fields($deal_component, $db, $user, $smarty, array('store_key' => $deal_component->get('Store Key')));


$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');


