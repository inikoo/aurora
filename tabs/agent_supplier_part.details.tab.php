<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26-08-2019 11:35:14 MYT Road south of Perth, Australia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$supplier_part = $state['_object'];


$object_fields_supplier_part = get_object_fields(
    $supplier_part, $db, $user, $smarty, array(
        'show_full_label' => true,
        'parent'          => 'supplier',
        'parent_object'   => $state['_parent']
    )
);



$smarty->assign('object_fields', $object_fields_supplier_part);
$smarty->assign('state', $state);

//$smarty->assign('js_code', 'js/injections/supplier_part_details.'.(_DEVEL ? '' : 'min.').'js');

$html = $smarty->fetch('edit_object.tpl');


