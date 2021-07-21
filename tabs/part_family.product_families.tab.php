<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:11 June 2016 at 17:08:05 BST, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

$tab     = 'part_family.product_families';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'product_families';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Label')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);



$edit_table_dialog = array(


    'spreadsheet_edit' => array(
        'label'=>'<span class="spreadsheet_edit_label"></span>',
        'tipo'       => 'edit_objects',
        'parent'     => 'part_category',
        'parent_key' => $state['key'],
        'object'     => 'product',
        'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
    ),

);
$smarty->assign('edit_table_dialog', $edit_table_dialog);

$objects = 'product';
$edit_fields = get_export_edit_template_fields($objects);

foreach($edit_fields as $key=>$value){
    $edit_fields[$key]['checked']=1;
}



$smarty->assign('edit_fields', $edit_fields);

$table_buttons=array();

$table_buttons[] = array(
    'icon'  => 'edit',
    'title' => _("Edit products"),
    'id'    => 'edit_dialog'
);

$smarty->assign('table_buttons', $table_buttons);



$smarty->assign(
    'js_code', 'js/injections/part_family.product_families.'.(_DEVEL ? '' : 'min.').'js'
);

include('utils/get_table_html.php');



