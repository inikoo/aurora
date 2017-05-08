<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 February 2016 at 18:39:33 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

include_once 'class.Customer.php';


$customer = new Customer(0);

$object_fields = get_object_fields(
    $customer, $db, $user, $smarty, array(
                 'new'            => true,
             )
);




$smarty->assign('state', $state);
$smarty->assign('object', $customer);


$smarty->assign('object_name', $customer->get_object_name());


$smarty->assign('object_fields', $object_fields);

$store = new Store($state['parent_key']);

$country_2alpha_code= $store->get('Store Home Country Code 2 Alpha');

$smarty->assign(
    'default_country', $store->get('Store Home Country Code 2 Alpha')
);
$smarty->assign(
    'preferred_countries', '"'.join(
        '", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))
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

?>
