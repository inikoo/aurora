<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2016 at 19:52:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'conf/export_edit_template_fields.php';


$objects='supplier_part';

$smarty->assign('title', _("Supplier's parts edit"));


$smarty->assign('parent', $state['object']);
$smarty->assign('parent_key', $state['key']);
$smarty->assign('parent_code', $state['_object']->get('Code'));




$smarty->assign('objects', $objects);


$smarty->assign('edit_fields', $export_edit_template_fields[$objects]);

$html=$smarty->fetch('edit_table.tpl');

?>
