<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 09:59:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$webpage = get_object('Webpage',0);

$object_fields = get_object_fields($webpage, $db, $user, $smarty,array('new'=>true,'store_key'=>$state['store']->id));

$smarty->assign('state', $state);
$smarty->assign('object', $webpage);

$smarty->assign('object_name', $webpage->get_object_name());
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');

?>
