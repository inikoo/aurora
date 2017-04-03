<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 March 2017 at 14:19:38 GMT+8 , Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';


$object_fields = get_object_fields($state['_object'], $db, $user, $smarty,array('type'=>'setup'));


$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);

$smarty->assign('form_type', 'setup');
$smarty->assign('step', 'setup_account');


$smarty->assign('object_name', $state['_object']->get_object_name());


$smarty->assign('object_fields', $object_fields);


$html = $smarty->fetch('edit_object.tpl');

?>
