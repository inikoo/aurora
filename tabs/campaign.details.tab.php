<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 13:33:16 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$campaign = $state['_object'];

$object_fields = get_object_fields($campaign, $db, $user, $smarty, array('store_key'=>$campaign->get('Store Key')));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>
