<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 November 2018 at 14:00:28 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$shipping_zone = get_object('Shipping_Zone', 0);

$object_fields = get_object_fields(
    $shipping_zone, $db, $user, $smarty, array(
                 'new' => true,
                 'store_key' => $state['parent_key'],
             )
);


$smarty->assign('state', $state);
$smarty->assign('object', $shipping_zone);


$smarty->assign('object_name', $shipping_zone->get_object_name());


$smarty->assign('object_fields', $object_fields);


$html = $smarty->fetch('new_object.tpl');

?>
