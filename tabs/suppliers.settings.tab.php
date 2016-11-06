<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2016 at 22:22:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$account = $state['_object'];

$object_fields = get_object_fields(
    $account, $db, $user, $smarty, array(
        'type' => 'suppliers.settings',
        'show_full_label' => false
    )
);


$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');


?>
