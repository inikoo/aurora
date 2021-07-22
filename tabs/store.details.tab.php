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
include_once 'conf/fields/store.fld.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

if ($user->can_edit('stores') and in_array($state['_object']->id, $user->stores)) {

    $store = $state['_object'];
    $smarty->assign(
        'default_country', $store->get('Store Home Country Code 2 Alpha')
    );
    $smarty->assign(
        'preferred_countries', '"'.join(
                                 '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
                             ).'"'
    );


    $object_fields = get_store_fields($store, $user, $db, $smarty);
    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);
    $smarty->assign('object', $store);

    $smarty->assign('js_code', 'js/injections/store_settings.'.(_DEVEL ? '' : 'min.').'js');


    try {
        $html = $smarty->fetch('edit_object.tpl');
    } catch (Exception $e) {
        $html='';
    }
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
        $html='';
    }
}



