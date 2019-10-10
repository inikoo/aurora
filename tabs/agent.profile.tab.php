<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 10 Oct 2019 11:26:43 +0800 MYT, Kuala Lumpur, Malaysis
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';


$state['object']='user';
$state['_object']=$user;
$state['key']=$user->id;

$object_fields = get_object_fields($user, $db, $user, $smarty, array('type' => 'profile'));

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);

$smarty->assign('js_code', 'js/injections/profile_details.'.(_DEVEL ? '' : 'min.').'js');
$html = $smarty->fetch('edit_object.tpl');


