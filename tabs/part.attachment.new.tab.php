<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2016 at 00:15:35 GMT+8, Plane Kuala Lumpur, Malaysia -> Denpasar, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Part.php';

$attachment = $state['_object'];

$object_fields = get_object_fields(
    $attachment, $db, $user, $smarty, array(
        'new' => true,
        'type' => 'part'
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');


?>
