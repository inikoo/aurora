<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:12 May 2016 at 19:32:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


if ( !$user->can_view('suppliers')    ) {
	$html='';
}else {

	include_once 'utils/invalid_messages.php';


	$object_fields=get_object_fields($state['_object'], $db, $user, $smarty);

	$smarty->assign('object', $state['_object']);
	$smarty->assign('key', $state['key']);

	$smarty->assign('object_fields', $object_fields);
	$smarty->assign('state', $state);


	$html=$smarty->fetch('edit_object.tpl');
}



?>