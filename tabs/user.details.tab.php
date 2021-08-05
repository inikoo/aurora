<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 19:52:58 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'conf/fields/user.system.fld.php';

include_once 'utils/invalid_messages.php';
/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */

$state['_object']->read_stores();
$state['_object']->read_rights();




$smarty->assign('state', $state);


$stores = array();
$sql    = 'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` ';
foreach ($db->query($sql) as $row) {
    $stores[$row['Store Key']] = $row;
}
$smarty->assign('stores', $stores);

$smarty->assign('object', $state['_object']);
$smarty->assign('system_user', $state['_object']);



$object_fields = get_user_fields($state['_object'], $db, array(

    'type'   => 'user',
    'parent' =>  $state['parent']
));
$smarty->assign('object_fields', $object_fields);

try {
    $html = $smarty->fetch('edit_object.tpl');
} catch (Exception $e) {
    $html = '';
}




