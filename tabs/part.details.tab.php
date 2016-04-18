<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:43:25 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

$part=$state['_object'];

$object_fields=get_object_fields($part, $db,$user,array('show_full_label'=>false));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries(
($part->get('Part Origin Country Code')==''?$account->get('Account Country 2 Alpha Code'):$part->get('Part Origin Country Code'))
)).'"');

$html=$smarty->fetch('edit_object.tpl');

?>
