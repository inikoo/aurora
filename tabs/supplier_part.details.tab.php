<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 22:02:23 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$supplier_part=$state['_object'];


$object_fields_supplier_part=get_object_fields($supplier_part, $db, $user, $smarty,
	array(
		'show_full_label'=>true,
		'supplier'=>$state['_parent']
	));



$part=$state['_object']->part;

$object_fields_part=get_object_fields($part, $db, $user, $smarty, array('supplier_part_scope'=>true));

$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries(
			($part->get('Part Origin Country Code')==''?$account->get('Account Country 2 Alpha Code'):$part->get('Part Origin Country Code'))
		)).'"');

$smarty->assign('object_fields', array_merge($object_fields_supplier_part, $object_fields_part));
$smarty->assign('state', $state);

$smarty->assign('js_code', 'js/injections/supplier_part_details.'.(_DEVEL?'':'min.').'js');

$html=$smarty->fetch('edit_object.tpl');

?>
