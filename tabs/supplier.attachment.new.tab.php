<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 August 2016 at 18:20:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Supplier.php';

$attachment = $state['_object'];

$object_fields = get_object_fields(
    $attachment, $db, $user, $smarty, array(
        'new' => true,
        'type' => 'supplier'
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');


?>
