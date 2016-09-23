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
$smarty->assign('title', sprintf(_("Supplier's parts in %s"), $state['_object']->get('Code')));
$smarty->assign('parent', $state['object']);
$smarty->assign('parent_key', $state['key']);
$smarty->assign('parent_code', $state['_object']->get('Code'));
$smarty->assign('objects', $objects);


$edit_fields=$export_edit_template_fields[$objects];


if ($state['_object']->data['Supplier On Demand']=='No') {

	foreach ($edit_fields as $key=>$value) {
        if($value['name']=='Supplier Part On Demand'){
            unset($edit_fields[$key]);
            break;
        }
	}

}



$smarty->assign('edit_fields', $edit_fields);
$smarty->assign('return_tab', $state['tab']);

$html=$smarty->fetch('edit_table.tpl');

?>
