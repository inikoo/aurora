<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  3 September 2016 at 13:09:11 GMT+8
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'conf/export_edit_template_fields.php';


$category = $state['_object'];


if ($category->get('Category Scope') == 'Product') {

    if ($category->get('Category Subject') == 'Product') {
        $objects = 'product';
        $smarty->assign(
            'title', sprintf(_("Products in %s"), $category->get('Code'))
        );
        $smarty->assign('parent', $state['object']);
        $smarty->assign('parent_key', $state['key']);
        $smarty->assign('parent_code', $state['_object']->get('Code'));
    } else {
    }


} elseif ($category->get('Category Scope') == 'Part') {
    $objects = 'part';
    $smarty->assign('title', sprintf(_("Parts in %s"), $category->get('Code')));
    $smarty->assign('parent', $state['object']);
    $smarty->assign('parent_key', $state['key']);
    $smarty->assign('parent_code', $state['_object']->get('Code'));


} elseif ($category->get('Category Scope') == 'Supplier') {
}


$smarty->assign('objects', $objects);
$smarty->assign('edit_fields', $export_edit_template_fields[$objects]);

$html = $smarty->fetch('edit_table.tpl');


?>
