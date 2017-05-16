<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2017 at 10:17:56 GMT-5, CdMx Mexico
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$webpage = $state['_object'];

$object_fields = get_object_fields($webpage, $db, $user, $smarty, array());
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>
