<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 14:27:41 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$prospect = $state['_object'];

$object_fields = get_object_fields(
    $prospect, $db, $user, $smarty, array('show_full_label' => false)
);


$store = new Store($state['_object']->get('Prospect Store Key'));

$smarty->assign(
    'default_country', $store->get('Store Home Country Code 2 Alpha')
);
$smarty->assign(
    'preferred_countries', '"'.join(
        '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
    ).'"'
);

$default_country = ($prospect->get('Contact Address Country 2 Alpha Code') == ''
    ? $store->get('Store Home Country Code 2 Alpha')
    : $prospect->get(
        'Contact Address Country 2 Alpha Code'
    ));
$smarty->assign(
    'default_telephone_data', base64_encode(
        json_encode(
            array(
                'default_country'     => strtolower($default_country),
                'preferred_countries' => array_map(
                    'strtolower', preferred_countries($default_country)
                ),
            )
        )
    )
);


$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


//$smarty->assign('js_code', 'js/injections/prospect_details.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('edit_object.tpl');

?>
