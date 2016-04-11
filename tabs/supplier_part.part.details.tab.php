<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 21:38:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$part=$state['_object']->part;

$object_fields=get_object_fields($part, $db,array('supplier_part_scope'=>true));





$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries(
($part->get('Part Origin Country Code')==''?$account->get('Account Country 2 Alpha Code'):$part->get('Part Origin Country Code'))
)).'"');

$html=$smarty->fetch('edit_object.tpl');

?>
