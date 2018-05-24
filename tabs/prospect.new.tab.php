<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 20:26:36 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

include_once 'class.Prospect.php';


$prospect =  get_object('Prospect',0);

$object_fields = get_object_fields(
    $prospect, $db, $user, $smarty, array(
                 'new'            => true,
             )
);




$smarty->assign('state', $state);
$smarty->assign('object', $prospect);


$smarty->assign('object_name', $prospect->get_object_name());


$smarty->assign('object_fields', $object_fields);

$store =  get_object('Store',$state['parent_key']);

$country_2alpha_code= $store->get('Store Home Country Code 2 Alpha');

$smarty->assign('default_country', $store->get('Store Home Country Code 2 Alpha'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($store->get('Store Home Country Code 2 Alpha'))).'"');



$smarty->assign(
    'default_telephone_data', base64_encode(
                                json_encode(
                                    array(
                                        'default_country'     => strtolower($country_2alpha_code),
                                        'preferred_countries' => array_map('strtolower', preferred_countries($country_2alpha_code)),
                                    )
                                )
                            )
);


//$smarty->assign('js_code', 'js/injections/prospect_details.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('new_object.tpl');

?>
