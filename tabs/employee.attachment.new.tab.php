<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2015 at 20:48:17 GMT Sheffied UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Staff.php';

$attachment = $state['_object'];

$object_fields = get_object_fields(
    $attachment, $db, $user, $smarty, array(
        'new' => true,
        'type' => 'employee'
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');


?>
