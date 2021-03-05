<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 January 2019 at 15:03:42 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$production_product = $state['_object'];

$object_fields_production_product = get_object_fields(
    $production_product, $db, $user, $smarty, array(
        'show_full_label' => true,
        'parent'          => 'production',
        'parent_object'   => $state['_parent']
    )
);

$smarty->assign('object_fields', $object_fields_production_product);
$smarty->assign('state', $state);
$smarty->assign('object', $production_product);

$html = $smarty->fetch('edit_object.tpl');


