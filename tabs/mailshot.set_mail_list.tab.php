<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2018 at 13:03:53 GMT+8. Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
                         'parent'              => $state['object'],
                         'parent_object'       => $state['_object'],
                         'new'                 => true,
                         'store_key'=>$state['_object']->get('Store Key'),
                         'scope'=>'customers',
                         'type'=>'mailing_list'
                     )
            
);


$smarty->assign('state', $state);


$smarty->assign('object', $state['_object']);

$smarty->assign('object_name', 'Email_Campaign');
$smarty->assign('object_fields', $object_fields);

$smarty->assign('control_class', 'hide');


$smarty->assign('js_code', 'js/injections/customers_list.new.'.(_DEVEL ? '' : 'min.').'js');

$html = $smarty->fetch('new_object.tpl');

?>
