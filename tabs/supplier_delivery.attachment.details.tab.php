<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 19:05:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$attachment = $state['_object'];

$object_fields = get_object_fields(
    $attachment, $db, $user, $smarty, array('type' => 'supplier_delivery')
);

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>
