<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2015 at 23:07:03 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';

$system_user = new User($state['key']);

$object_fields = get_object_fields(
    $system_user, $db, $user, $smarty, array('type' => 'profile')
);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html = $smarty->fetch('edit_object.tpl');

?>
