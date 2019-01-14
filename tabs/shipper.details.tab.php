<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  10 January 2019 at 15:57:12 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


if (!$user->can_view('locations') or !in_array($state['warehouse']->id, $user->warehouses)) {
    $html = '';
} else {


    $default_country = $account->get('Account Country 2 Alpha Code');
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


    $shipper = $state['_object'];

    $object_fields = get_object_fields($shipper, $db, $user, $smarty);

    $smarty->assign('object', $state['_object']);
    $smarty->assign('key', $state['key']);

    $smarty->assign('object_fields', $object_fields);
    $smarty->assign('state', $state);


    $html = $smarty->fetch('edit_object.tpl');


}

?>
