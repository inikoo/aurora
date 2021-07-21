<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 September 2016 at 12:05:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \Smarty $smarty */
/** @var array $state */

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {

    $warehouse = $state['warehouse'];

    $tab     = 'warehouse.areas';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'areas';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array('label' => _('Code')),

    );

    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );

    $table_buttons=array();



    $edit_table_dialog = array(
        'labels'=>array(
            'add_items'=>_("Add area(s)").":",
            'edit_items'=>_("Edit area(s)").":"
        ),
        'new_item'         => array(
            'icon'      => 'plus',
            'reference' => "warehouse/".  $state['parent_key']."/areas/new"
        ),
        'upload_items'     => array(
            'icon'         => 'plus',
            'label'        => _("Upload warehouse areas"),
            'template_url' => '/upload_arrangement.php?object=warehouse_area&parent=warehouse&parent_key='.$warehouse->id,

            'tipo'       => 'edit_objects',
            'parent'      => 'warehouse',
            'parent_key' => $warehouse->id,

            'object' => 'warehouse_area',
        ),
        'inline_edit'      => array(),
        'spreadsheet_edit' => array(
            'tipo'        => 'edit_objects',

            'parent'      => 'warehouse',
            'parent_key'  => $warehouse->id,
            'object'      => 'warehouse_area',
            'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $warehouse->get('Code')),
        ),

    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $objects = 'warehouse_area';
    $edit_fields = get_export_edit_template_fields($objects);


    $smarty->assign('edit_fields', $edit_fields);


    $table_buttons = array();

    $table_buttons[] = array(
        'icon'  => 'edit_add',
        'title' => _("Edit warehouse areas"),
        'id'    => 'edit_dialog'
    );


    $smarty->assign('table_buttons', $table_buttons);

    $smarty->assign('title', _('Warehouse areas'));
    $smarty->assign('view_position','<span onclick=\"change_view(\'warehouse/'.$warehouse->id.'\')\"><i class=\"fal  fa-warehouse-alt\"></i> <span class=\"id Warehouse_Code\">'.$warehouse->get('Code').'</span></span><i class=\"fa fa-angle-double-right separator\"></i>  '._('Warehouse areas').' </span>');


    include 'utils/get_table_html.php';
}


