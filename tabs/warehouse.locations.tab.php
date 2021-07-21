<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {


    $warehouse = $state['warehouse'];


    $tab     = 'warehouse.locations';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'locations';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Location code')
        ),

    );

    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );

    $smarty->assign(
        'js_code', 'js/injections/warehouse_locations.'.(_DEVEL ? '' : 'min.').'js'
    );

    $edit_table_dialog = array(
        'labels'=>array(
            'add_items'=>_("Add location(s)").":",
            'edit_items'=>_("Edit location(s)").":"
        ),
        'new_item'         => array(
            'icon'      => 'plus',
            'reference' => "locations/".$warehouse->id."/new"
        ),
        'upload_items'     => array(
            'icon'         => 'plus',
            'label'        => _("Upload locations"),
            'template_url' => '/upload_arrangement.php?object=location&parent=warehouse&parent_key='.$warehouse->id,

            'tipo'       => 'edit_objects',
            'parent'      => 'warehouse',
            'parent_key' => $warehouse->id,

            'object' => 'location',
        ),
        'inline_edit'      => array(),
        'spreadsheet_edit' => array(
            'tipo'        => 'edit_objects',

            'parent'      => 'warehouse',
            'parent_key'  => $warehouse->id,
            'object'      => 'location',
            'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $warehouse->get('Code')),
        ),

    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $objects = 'location';


    $edit_fields = get_export_edit_template_fields($objects);


    $smarty->assign('edit_fields', $edit_fields);


    $table_buttons = array();

    $table_buttons[] = array(
        'icon'  => 'edit_add',
        'title' => _("Edit locations"),
        'id'    => 'edit_dialog'
    );


    $smarty->assign('table_buttons', $table_buttons);


    $flags = array();

    $sql = sprintf(
        'select `Warehouse Flag Key`,`Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` where `Warehouse Flag Warehouse Key`=%d ',
        $warehouse->id
    );

    $flags = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $flags[$row['Warehouse Flag Key']] = array(
                'color' => strtolower($row['Warehouse Flag Color']),
                'key'   => $row['Warehouse Flag Key'],
                'label' => $row['Warehouse Flag Label']
            );
        }
    }


    $smarty->assign('flags', $flags);
    $smarty->assign('aux_templates', array('edit_locations.tpl'));
    $smarty->assign('title', _('Locations'));
    $smarty->assign('view_position','<span onclick=\"change_view(\'warehouse/'.$warehouse->id.'\')\"><i class=\"fal  fa-warehouse-alt\"></i> <span class=\"id Warehouse_Code\">'.$warehouse->get('Code').'</span></span><i class=\"fa fa-angle-double-right separator\"></i>  '._('Locations').' </span>');


    include 'utils/get_table_html.php';
}

