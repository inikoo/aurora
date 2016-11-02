<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 June 2016 at 16:32:24 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Staff.php';

$contractor = $state['_object'];


$object_fields = get_object_fields(
    $contractor, $db, $user, $smarty, array(
        'new' => true,
        'type' => 'user'
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $contractor);
$smarty->assign('object_name', 'User');

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');

?>
