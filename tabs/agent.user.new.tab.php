<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 June 2016 at 17:45:46 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$object_fields=get_object_fields($state['_parent'], $db, $user, $smarty,array('new'=>true,'type'=>'user'));

$smarty->assign('state', $state);
$smarty->assign('object', $state['_parent']);
$smarty->assign('object_name', 'User');

$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('new_object.tpl');


?>
