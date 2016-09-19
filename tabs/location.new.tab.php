<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 September 2016 at 11:11:17 GMT+8 Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';
include_once 'class.Location.php';





$object_fields=get_object_fields($state['_object'], $db, $user, $smarty, array( 'new'=>true));



$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);


$smarty->assign('object_name', $state['_object']->get_object_name());


$smarty->assign('object_fields', $object_fields);



$html=$smarty->fetch('new_object.tpl');

?>
