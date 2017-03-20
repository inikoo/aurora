
<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 13:41:34 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';






$product = $state['_object'];

$webpage=$product->get_webpage();


if(!$webpage->id){

    $html='<div style="padding:40px">'._("This product don't have webpage").'</div>';

    return;

}

if($product->get('Product Web Configuration')=='Offline'){

    $html='<div style="padding:40px">'._("This product is forced to be offline").'</div>';

    return;

}



$object_fields = get_object_fields($product, $db, $user, $smarty, array('type' => 'webpage_settings'));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object', $product);

$html = $smarty->fetch('edit_object.tpl');

?>
