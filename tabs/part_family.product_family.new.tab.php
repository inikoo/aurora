<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2016 at 10:31:03 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once('class.Store.php');


if (!isset($state['metadata']['store_key'])) {

    $html = '';

    return;

}


include_once 'conf/export_edit_template_fields.php';

$store = new Store($state['metadata']['store_key']);

$title = sprintf(
    _('New family %s for %s'), $state['_object']->get('Code'), $store->get('Code')
);


$objects = 'product';
$smarty->assign('title', $title);


$smarty->assign('export_parent', 'part_category');
$smarty->assign('export_parent_key', $state['_object']->id);
$smarty->assign('export_parent_code', $state['_object']->get('Code'));
$smarty->assign('store_key', $store->id);

$smarty->assign('objects', $objects);


$smarty->assign('parent', 'store');
$smarty->assign('parent_key', $store->id);
$smarty->assign('parent_code', $store->get('Code'));
$smarty->assign('objects', $objects);
$smarty->assign('edit_fields', $export_edit_template_fields[$objects]);
$smarty->assign('return_tab', $state['tab']);


$smarty->assign('category', $state['_object']);

$html = $smarty->fetch('part_category.family.new.tpl');


?>
