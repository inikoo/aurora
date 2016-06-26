<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2016 at 20:00:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';



$supplier=$state['_object'];
$supplier->get_user_data();

$object_fields=get_object_fields($supplier, $db, $user, $smarty ,array('show_full_label'=>false));

$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');


$default_country=($supplier->get('Contact Address Country 2 Alpha Code')==''?$account->get('Account Country 2 Alpha Code'):$supplier->get('Contact Address Country 2 Alpha Code'));
$smarty->assign('default_telephone_data', base64_encode(json_encode(
			array(
				'default_country'=>strtolower($default_country),
				'preferred_countries'=>array_map('strtolower', preferred_countries($default_country))  ,
			)
		)
	));

$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$smarty->assign('js_code', 'js/injections/supplier_details.'.(_DEVEL?'':'min.').'js');

$html=$smarty->fetch('edit_object.tpl');

?>
