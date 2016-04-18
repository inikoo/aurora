<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2016 at 18:02:13 GMT+8, Yiwu, China
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

include_once 'class.Staff.php';

$account=new Account();
$smarty->assign('account', $account);


$user=new User('Administrator');

$employee=new Staff(0);



$object_fields=get_object_fields($employee, $db,$user);



$smarty->assign('state', $state);
$smarty->assign('object', $employee);


$smarty->assign('object_name', $employee->get_object_name());


$smarty->assign('object_fields', $object_fields);

$smarty->assign('form_type', 'setup');
$smarty->assign('step', 'add_employee');

$smarty->assign('js_code', 'js/injections/employee.'.(_DEVEL?'':'min.').'js');


$html=$smarty->fetch('new_object.tpl');

?>
