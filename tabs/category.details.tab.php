<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 12:47:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

$category=$state['_object'];

$object_fields=get_object_fields($category, $db, $user, $smarty, array('Category Scope'=>$category->get('Category Scope')));




$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('edit_object.tpl');

?>
