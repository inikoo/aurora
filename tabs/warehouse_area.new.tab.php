<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2020  13:15::37  +0800   Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';

$object_fields = get_object_fields($state['_object'], $db, $user, $smarty,array('new'=>true));

$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('object_name', $state['_object']->get_object_name());
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');


