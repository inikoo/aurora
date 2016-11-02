<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 November 2015 at 23:15:45 GMT Sheffied UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once 'class.Staff.php';

$employee                     = new Staff(0);
$employee->data['Staff Type'] = 'Contractor';

$object_fields = get_object_fields(
    $employee, $db, $user, $smarty, array('new' => true)
);

$smarty->assign('state', $state);
$smarty->assign('object', $employee);
$smarty->assign('object_name', 'Contractor');

$smarty->assign('object_fields', $object_fields);
$smarty->assign(
    'js_code', 'js/injections/employee.'.(_DEVEL ? '' : 'min.').'js'
);

$html = $smarty->fetch('new_object.tpl');

?>
