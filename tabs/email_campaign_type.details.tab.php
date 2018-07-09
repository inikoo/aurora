<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 June 2018 at 16:40:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3
*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$email_campaign_type = $state['_object'];
$object_fields = get_object_fields($email_campaign_type, $db, $user, $smarty);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$smarty->assign('js_code', 'js/injections/email_campaign_type_details.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('edit_object.tpl');

?>