<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 15:22:21 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$charge = $state['_object'];

$object_fields = get_object_fields($charge, $db, $user, $smarty, array('store_key'=>$charge->get('Store Key')));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>
