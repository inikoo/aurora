<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 8 February 2016 at 19:13:16 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';



$supplier=$state['_object'];

if ($state['parent']=='agent') {
	$country_2alpha_code=strtoupper($state['_parent']->get('Agent Contact Address Country 2 Alpha Code'));
	$currency=$state['_parent']->get('Agent Default Currency Code');
	$country_origin=($state['_parent']->get('Agent Products Origin Country Code')==''?$account->get('Account Country Code'):$state['_parent']->get('Agent Products Origin Country Code'));
}else {
	$country_2alpha_code=$account->get('Account Country 2 Alpha Code');
	$currency=$account->get('Account Currency');
	$country_origin=$account->get('Account Country Code');
}

$object_fields=get_object_fields($supplier, $db, $user, $smarty , array('new'=>true, 'currency'=>$currency, 'country_origin'=>$country_origin, 'parent'=>$state['parent']));




$smarty->assign('default_country', $country_2alpha_code);
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($country_2alpha_code  )).'"');


$smarty->assign('default_telephone_data', base64_encode(json_encode(
			array(
				'default_country'=>strtolower($country_2alpha_code),
				'preferred_countries'=>array_map('strtolower', preferred_countries($country_2alpha_code))  ,
			)
		)
	));




$smarty->assign('state', $state);
$smarty->assign('object', $supplier);


$smarty->assign('object_name', $supplier->get_object_name());


$smarty->assign('object_fields', $object_fields);

//$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
//$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');

$smarty->assign('js_code', 'js/injections/supplier_new.'.(_DEVEL?'':'min.').'js');


$html=$smarty->fetch('new_object.tpl');

?>
