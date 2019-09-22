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

$smarty->assign('js_code', 'js/injections/invoice_details.'.(_DEVEL ? '' : 'min.').'js');

$customer = get_object('Customer', $state['_object']->get('Invoice Customer Key'));

$smarty->assign('default_country', $state['store']->get('Store Home Country Code 2 Alpha'));
$smarty->assign(
    'preferred_countries', '"'.join(
                             '", "', preferred_countries($state['store']->get('Store Home Country Code 2 Alpha'))
                         ).'"'
);

$default_country = ($customer->get('Contact Address Country 2 Alpha Code') == ''
    ? $state['store']->get('Store Home Country Code 2 Alpha')
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


$html = $smarty->fetch('edit_object.tpl');



