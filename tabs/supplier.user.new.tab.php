<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 June 2016 at 15:10:27 BST, Plane (Jakarta-Bali)
 Copyright (c) 2016, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';
include_once 'conf/fields/user.system.fld.php';

/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */


$object=new User();

$object_fields = get_user_fields($object, $db, array(
    'new'    => true,
    'type'   => 'user',
    'parent' => 'supplier'
));


$smarty->assign('state', $state);
$smarty->assign('object', $object);
$smarty->assign('object_name', 'User');
$smarty->assign('object_fields', $object_fields);



try {
    $html = $smarty->fetch('new_object.tpl');
} catch (Exception $e) {
    $html = '';
}