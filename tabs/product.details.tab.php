<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 13:29:56 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$product=$state['_object'];
$product->get_store_data();

$object_fields=get_object_fields($product, $db, $user, $smarty, array('show_full_label'=>false));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('parts_list', $product->get_parts_data(true));

$html=$smarty->fetch('edit_object.tpl');

?>
