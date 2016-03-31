<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2015 at 14:20:16 GMT Sheffied UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';
include_once 'class.Staff.php';

$employee=new Staff(0);

$object_fields=get_object_fields('employee', $db);




$smarty->assign('state', $state);
$smarty->assign('object', $employee);


$smarty->assign('object_name', $employee->get_object_name());


$smarty->assign('object_fields', $object_fields);


$smarty->assign('js_code', 'js/injections/employee.'.(_DEVEL?'':'min.').'js');


$html=$smarty->fetch('new_object.tpl');

?>
