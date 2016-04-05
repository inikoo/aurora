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

$object_fields=get_object_fields($supplier_part, $db,array('show_full_label'=>true));





$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html=$smarty->fetch('edit_object.tpl');

?>
