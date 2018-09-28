<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2018 at 20:12:00 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$email_campaign = $state['_object'];


$object_fields = get_object_fields(
    $email_campaign, $db, $user, $smarty, array(
    'new' => false,
    'store_key' => $email_campaign->get('Store Key')
)
);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object_name', 'Order_Basket_Purge');

$html = $smarty->fetch('edit_object.tpl');


?>
