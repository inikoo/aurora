<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 May 2016 at 14:37:00 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';


$options = array('new'            => true,
                 'Category Scope' => ''
);

if ($state['module'] == 'products') {
    $options['Category Scope'] = 'Product';
    $options['store_key']      = $state['store']->id;


} elseif ($state['module'] == 'inventory') {
    include_once 'class.SupplierPart.php';
    include_once 'class.Part.php';
    $options['Category Scope'] = 'Part';
} elseif ($state['module'] == 'suppliers') {
    $options['Category Scope'] = 'Supplier';
} elseif ($state['module'] == 'customers') {
    $options['Category Scope'] = 'Customer';
} elseif ($state['module'] == 'warehouses') {
    $options['Category Scope'] = 'Location';
} else {
    exit('main_category.new.tab.php UNKNOWN module '.$state['module']);
}


$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, $options
);


$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);


$smarty->assign('object_name', $state['_object']->get_object_name());


$smarty->assign('object_fields', $object_fields);


$html = $smarty->fetch('new_object.tpl');

?>
