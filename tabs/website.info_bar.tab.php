<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2018 at 13:29:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$website = $state['_object'];


$object_fields = get_object_fields(
    $website, $db, $user, $smarty, array('info_bar' => true)
);


$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');

?>
