<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2016 at 13:49:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

$material = $state['_object'];

$object_fields = get_object_fields(
    $material, $db, $user, $smarty, array('show_full_label' => false)
);


$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');

?>
