<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';

$state['_object']->read_stores();
$state['_object']->read_rights();

$object_fields = get_object_fields($state['_object'], $db, $user, $smarty);



$smarty->assign('state', $state);


$stores = array();
$sql    = sprintf(
    'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` '
);
foreach ($db->query($sql) as $row) {
    $stores[$row['Store Key']] = $row;
}
$smarty->assign('stores', $stores);

$smarty->assign('object', $state['_object']);
$smarty->assign('system_user', $state['_object']);

$smarty->assign('object_fields', $object_fields);
$html = $smarty->fetch('edit_object.tpl');



