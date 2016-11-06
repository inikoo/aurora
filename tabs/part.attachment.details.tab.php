<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:30 August 2016 at 13:16:30 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$attachment = $state['_object'];

$object_fields = get_object_fields(
    $attachment, $db, $user, $smarty, array('type' => 'part')
);

$smarty->assign('state', $state);
$smarty->assign('object', $attachment);
$smarty->assign('object_name', $attachment->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>
