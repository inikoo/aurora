<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-10-2019 17:01:55 MYT, Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Customer.php';

/** @var array $state */
/** @var \Smarty $smarty */
/** @var \PDO $db */
/** @var \User $user */

$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
                         'new'          => true,
                         'customer_key' => $state['parent_key']
                     )
);


$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);


$smarty->assign('object_name', $state['_object']->get_object_name());


$smarty->assign('object_fields', $object_fields);

$store = get_object('Store', $state['_parent']->get('Store Key'));

$country_2alpha_code = $store->get('Store Home Country Code 2 Alpha');

$smarty->assign(
    'default_country', $country_2alpha_code
);
$smarty->assign(
    'preferred_countries', '"'.join(
                             '", "', preferred_countries($country_2alpha_code)
                         ).'"'
);


$smarty->assign(
    'default_telephone_data', base64_encode(
                                json_encode(
                                    array(
                                        'default_country'     => strtolower($country_2alpha_code),
                                        'preferred_countries' => array_map(
                                            'strtolower', preferred_countries($country_2alpha_code)
                                        ),
                                    )
                                )
                            )
);



$html = $smarty->fetch('new_object.tpl');


