<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:44:31 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

$store = $state['_object'];





$smarty->assign(
    'default_country', $store->get('Store Home Country Code 2 Alpha')
);
$smarty->assign(
    'preferred_countries', '"'.join(
                             '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
                         ).'"'
);


$object_fields = get_object_fields($store, $db, $user, $smarty, array());
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object', $store);

$smarty->assign('js_code', 'js/injections/store_settings.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('edit_object.tpl');

?>
