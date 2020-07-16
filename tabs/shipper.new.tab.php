<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 July 2018 at 15:57:04 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'conf/api_shippers.php';


$account = get_object('Account', 1);

//$shippers=get_api_shippers($account->get('Account Country 2 Alpha Code'));



//$html=manual_new_shipper($state,$db, $user, $smarty,$account);



function manual_new_shipper($state,$db, $user, $smarty, $account){

    include_once 'utils/country_functions.php';

    include_once 'utils/invalid_messages.php';
    include_once 'conf/object_fields.php';

    $object_fields = get_object_fields(
        $state['_object'], $db, $user, $smarty, array(
                             'new' => true,
                         )
    );




    $smarty->assign('state', $state);
    $smarty->assign('object', $state['_object']);


    $smarty->assign('object_name', $state['_object']->get_object_name());
    $smarty->assign('object_fields', $object_fields);
    $country_2alpha_code = $account->get('Account Country 2 Alpha Code');

    $smarty->assign('default_country', $country_2alpha_code);
    $smarty->assign(
        'preferred_countries', '"'.join('", "', preferred_countries($country_2alpha_code)).'"'
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



    return $smarty->fetch('new_object.tpl');

}