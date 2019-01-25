<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 October 2016 at 14:04:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'production.supplier_parts';
$ar_file = 'ar_production_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'parts'    => array(
        'label' => _('Inventory Part'),
        'title' => _('Part details')
    ),
    'reorder'  => array('label' => _('Reorder')),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


if ($state['_object']->get('Supplier Type') != 'Archived') {



    include_once 'conf/export_edit_template_fields.php';


    $edit_table_dialog = array(
        'new_item'         => array(
            'icon'      => 'plus',
            'title'     => _("New supplier's part"),
            'reference' => "supplier/".$state['key']."/part/new"
        ),
        'upload_items'     => array(
            'icon'         => 'plus',
            'label'        => _("Upload supplier's parts"),
            'template_url' => '/upload_arrangement.php?object=supplier_part&parent=supplier&parent_key='.$state['key'],

            'tipo'        => 'edit_objects',
            'parent'      => $state['object'],
            'parent_key'  => $state['key'],

            'object'      => 'supplier_part',
        ),
        'inline_edit'      => array(),
        'spreadsheet_edit' => array(
            'tipo'       => 'edit_objects',
            'parent'     => $state['object'],
            'parent_key' => $state['key'],
            'object'     => 'supplier_part',
            'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
        ),

    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $objects = 'supplier_part';


    $edit_fields = $export_edit_template_fields[$objects];


    if ($state['_object']->data['Supplier On Demand'] == 'No') {

        foreach ($edit_fields as $key => $value) {
            if ($value['name'] == 'Supplier Part On Demand') {
                unset($edit_fields[$key]);
                break;
            }
        }

    }


    $smarty->assign('edit_fields', $edit_fields);


    if ($state['_object']->get('Supplier Type') != 'Archived') {

        $table_buttons = array();

        $table_buttons[] = array(
            'icon'  => 'edit_add',
            'title' => _("Edit supplier's parts"),
            'id'    => 'edit_dialog'
        );



        $smarty->assign('table_buttons', $table_buttons);


    }

    $smarty->assign('table_top_template', 'supplier_parts.edit.tpl');





}

include 'utils/get_table_html.php';


?>
