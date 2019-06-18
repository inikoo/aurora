<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 08-05-2019 22:34:04 CEST , Trnava, Slovakia
 Copyright (c) 2019, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';


$website = get_object('website',$state['_object']->get('Store Website Key'));


$object_fields = get_object_fields(
    $website, $db, $user, $smarty, array('localization' => true)
);


$smarty->assign('object', $website);
$smarty->assign('key',$website->id);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');

?>
