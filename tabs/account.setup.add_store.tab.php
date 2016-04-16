<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 14:54:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';


$object_fields=get_object_fields($state['_object'], $db);


$account=new Account($db);
$smarty->assign('account', $account);

$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('form_type', 'setup');
$smarty->assign('step', 'add_store');


$smarty->assign('object_name', $state['_object']->get_object_name());


$smarty->assign('object_fields', $object_fields);




$html=$smarty->fetch('new_object.tpl');

?>
