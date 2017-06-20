<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 April 2016 at 18:16:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

if ($user->get('User Type') == 'Agent') {

    $tab = 'agent_parts';

    $table_views = array(
        'overview' => array(
            'label' => _('Overview')

        ),
        'barcodes' => array('label' => _("Id's & Barcodes")),

    );

} else {
    $tab = 'supplier.supplier_parts';


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),
        'barcodes' => array('label' => _("Id's & Barcodes")),
        'parts'    => array(
            'label' => _('Inventory Part'),
            'title' => _('Part details')
        ),
        'reorder'  => array('label' => _('Reorder')),

    );

}


$ar_file = 'ar_inventory_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


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


// Editing ======

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


    /*

        if ($state['_object']->get('Supplier Number Parts') > 0) {
            $table_buttons[] = array(
                'icon'  => 'edit',
                'title' => _("Edit supplier's parts"),
                'id'    => 'edit_table'
            );
        }

        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _("New supplier's part"),
            'reference' => "supplier/".$state['key']."/part/new"
        );

        $smarty->assign('table_buttons', $table_buttons);

        $smarty->assign(
            'upload_file', array(
                'tipo'       => 'edit_objects',
                'icon'       => 'fa-cloud-upload',
                'parent'     => $state['object'],
                'parent_key' => $state['key'],
                'object'     => 'supplier_part',
                'label'      => _("Upload supplier's parts")

            )
        );

    */

    $smarty->assign('table_buttons', $table_buttons);


}

$smarty->assign('table_top_template', 'supplier_parts.edit.tpl');


include 'utils/get_table_html.php';


?>
