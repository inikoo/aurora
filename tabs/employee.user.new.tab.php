<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 January 2016 at 23:26:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Staff.php';


$employee = $state['_object'];

$object_fields = get_object_fields(
    $employee, $db, $user, $smarty, array(
        'new'     => true,
        'type'    => 'user',
        'parent'  => 'Staff',
        '_parent' => $state['_parent']
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $employee);
$smarty->assign('object_name', 'User');

$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('new_object.tpl');

?>
