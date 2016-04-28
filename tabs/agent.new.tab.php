<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2016 at 18:38:39 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';



$agent=$state['_object'];

$object_fields=get_object_fields($agent, $db, $user, $smarty ,array('new'=>true));

$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');


$default_country=($agent->get('Contact Address Country 2 Alpha Code')==''?$account->get('Account Country 2 Alpha Code'):$agent->get('Contact Address Country 2 Alpha Code'));
$smarty->assign('default_telephone_data', base64_encode(json_encode(
			array(
				'default_country'=>strtolower($default_country),
				'preferred_countries'=>array_map('strtolower', preferred_countries($default_country))  ,
			)
		)
	));




$smarty->assign('state', $state);
$smarty->assign('object', $agent);


$smarty->assign('object_name', $agent->get_object_name());


$smarty->assign('object_fields', $object_fields);

$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');



$html=$smarty->fetch('new_object.tpl');

?>
