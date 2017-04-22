<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 April 2017 at 14:04:30 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'conf/export_edit_template_fields.php';



$objects = 'location';
$smarty->assign(
    'title', sprintf(_("Locations in %s"), $state['_parent']->get('Code'))
);
$smarty->assign('parent', $state['parent']);
$smarty->assign('parent_key', $state['parent_key']);
$smarty->assign('parent_code', $state['_parent']->get('Code'));
$smarty->assign('objects', $objects);


$edit_fields = $export_edit_template_fields[$objects];



$smarty->assign('edit_fields', $edit_fields);
$smarty->assign('return_tab', $state['tab']);

$html = $smarty->fetch('edit_table.tpl');

?>
