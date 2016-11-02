<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 September 2016 at 12:44:13 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$image         = $state['_object'];
$object_fields = get_object_fields(
    $image, $db, $user, $smarty, array('type' => 'part')
);

$smarty->assign('state', $state);
$smarty->assign('object', $image);
$smarty->assign('object_name', $image->get_object_name());

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>
