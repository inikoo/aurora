<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 4 Oct 2019 19:55:34 +0800 MYT, Kuala Lumpur
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

/**
 * @var $customer \Customer
 */
$customer = $state['_object'];

$object_fields = get_object_fields(
    $customer, $db, $user, $smarty, array('show_full_label' => false)
);


$store = get_object('Store', $customer->get('Customer Store Key'));

$smarty->assign(
    'default_country', $store->get('Store Home Country Code 2 Alpha')
);
$smarty->assign(
    'preferred_countries', '"'.join(
                             '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
                         ).'"'
);

$default_country = ($customer->get('Contact Address Country 2 Alpha Code') == ''
    ? $store->get('Store Home Country Code 2 Alpha')
    : $customer->get(
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


$html = $smarty->fetch('edit_object.tpl');


