<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 October 2016 at 14:04:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'production.production_parts';
$ar_file = 'ar_production_tables.php';
$tipo    = 'production_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'packing' => array(
        'label' => _('Packing'),
        'title' => _('Packing')
    )
);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include_once 'conf/export_edit_template_fields.php';


$edit_table_dialog = array(
    'new_item'         => array(
        'icon'      => 'plus',
        'title'     => _("New production product"),
        'reference' => "production/".$state['key']."/part/new"
    ),
    'upload_items'     => array(
        'icon'         => 'plus',
        'label'        => _("Upload products"),
        'template_url' => '/upload_arrangement.php?object=supplier_part&parent=supplier&parent_key='.$state['key'],

        'tipo'       => 'edit_objects',
        'parent'     => 'supplier',
        'parent_key' => $state['key'],

        'object' => 'supplier_part',
    ),
    'inline_edit'      => array(),
    'spreadsheet_edit' => array(
        'tipo'        => 'edit_objects',
        'parent'      => $state['object'],
        'parent_key'  => $state['key'],
        'object'      => 'production_part',
        'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
    ),

);


$smarty->assign('edit_table_dialog', $edit_table_dialog);

$objects = 'supplier_part';


$edit_fields = $export_edit_template_fields['production_part'];




$smarty->assign('edit_fields', $edit_fields);


$table_buttons = array();

$table_buttons[] = array(
    'icon'  => 'edit_add',
    'title' => _("Edit products"),
    'id'    => 'edit_dialog'
);


$smarty->assign('table_buttons', $table_buttons);


$smarty->assign('table_top_template', 'supplier_parts.edit.tpl');


include 'utils/get_table_html.php';


