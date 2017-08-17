<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 October 2015 at 17:25:16 CET, Train Napoles-Florence, Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$invoice = $state['_object'];

$object_fields = get_object_fields(
    $invoice, $db, $user, $smarty
);



$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');



?>