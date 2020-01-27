<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2015 at 23:07:03 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';


$object_fields = get_object_fields($user, $db, $user, $smarty, array('type' => 'profile'));

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('object', $user);

$smarty->assign('js_code', 'js/injections/profile_details.'.(_DEVEL ? '' : 'min.').'js');
$html = $smarty->fetch('edit_object.tpl');


