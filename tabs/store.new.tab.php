<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 March 2016 at 16:04:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';
include_once 'class.Store.php';

$store = new Store(0);

$object_fields = get_object_fields($store, $db, $user, $smarty);

$smarty->assign('state', $state);
$smarty->assign('object', $store);

$smarty->assign('object_name', $store->get_object_name());
$smarty->assign('object_fields', $object_fields);
$smarty->assign(
    'js_code', 'js/injections/employee.'.(_DEVEL ? '' : 'min.').'js'
);

$html = $smarty->fetch('new_object.tpl');

?>
