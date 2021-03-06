<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:08:27 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$webpage = $state['_object'];


$object_fields = get_object_fields(
    $webpage, $db, $user, $smarty, array('show_full_label' => false)
);


$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');


?>
