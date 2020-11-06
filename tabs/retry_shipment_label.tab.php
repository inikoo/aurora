<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 October 2015 at 21:26:35 BST, Birminham->Malaga (Plane)
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


include_once 'utils/invalid_messages.php';


if(!isset($state['metadata']['dn_key'])){
    return '';
}


$delivery_note=get_object('Delivery_Note',$state['metadata']['dn_key']);

$object_fields = get_object_fields($delivery_note, $db, $user, $smarty,['retry_shipment_label'=>'Yes']);




//$smarty->assign('object', 'retry_shipment_label');
//$smarty->assign('order', $state['_object']);

//$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);

$state['parent']='Delivery_Note';
$state['parent_key']=$delivery_note->id;

$smarty->assign('state', $state);


//print_r($state['_object']);

$customer = get_object('Customer', $state['_object']->get('Order Customer Key'));


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

$smarty->assign('object_name', 'shipment_label');


$html = $smarty->fetch('new_object.tpl');


