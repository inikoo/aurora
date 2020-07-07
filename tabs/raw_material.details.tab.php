<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13:28:34 MYT Tuesday, 7 July 2020, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$raw_material = $state['_object'];


$object_fields_raw_material = get_object_fields(
    $raw_material, $db, $user, $smarty, array(
                     'show_full_label' => true,
                     'parent'          => 'account',
                     'parent_object'   => 1
                 )
);




$smarty->assign('object_fields', $object_fields_raw_material);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');


