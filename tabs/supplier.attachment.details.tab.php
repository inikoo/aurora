<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2016 at 12:20:28 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$attachment=$state['_object'];

$object_fields=get_object_fields($attachment, $db, $user, $smarty,array('type'=>'supplier'));

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('edit_object.tpl');

?>
