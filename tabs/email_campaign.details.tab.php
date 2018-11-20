<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 October 2017 at 13:53:19 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$email_campaign = $state['_object'];


$object_fields = get_object_fields(
    $email_campaign, $db, $user, $smarty, array(
    'new' => false,
    'store_key' => $email_campaign->get('Store Key'),
    'type'=>''
)
);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object_name', 'Email_Campaign');



if($email_campaign->get('Email Campaign Type')=='AbandonedCart'){
    $smarty->assign('js_code', 'js/injections/email_campaign.abandoned_cart.new.'.(_DEVEL ? '' : 'min.').'js');
}

$html = $smarty->fetch('edit_object.tpl');


?>
