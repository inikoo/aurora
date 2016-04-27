<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 April 2016 at 12:28:23 GMT+8, Ubud, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

$barcode=$state['_object'];

$object_fields=get_object_fields($barcode, $db, $user, $smarty, array('show_full_label'=>false));





$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html=$smarty->fetch('edit_object.tpl');

?>
