<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';


$object_fields=get_object_fields($state['_object'], $db, $user, $smarty);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);
$html=$smarty->fetch('edit_object.tpl');



?>
