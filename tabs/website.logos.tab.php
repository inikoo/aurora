<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 April 2018 at 09:39:07 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$website = $state['_object'];


$object_fields = get_object_fields($website, $db, $user, $smarty, array('logos' => true));


$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');
$html.= '<span class="hide " id="webpage_data" data-website_key="'.$website->id.'"></span>'.$smarty->fetch('website.logos.edit.js');

?>
