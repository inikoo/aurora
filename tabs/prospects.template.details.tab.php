<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 03:42:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$email_template = $state['_object'];

$object_fields = get_object_fields(
    $email_template, $db, $user, $smarty, array('role' => 'Invite Mailshot')
);



$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


//$smarty->assign('js_code', 'js/injections/prospect_details.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('edit_object.tpl');

?>
