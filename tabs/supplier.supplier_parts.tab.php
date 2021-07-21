<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 April 2016 at 18:16:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var array $state */
/** @var \User $user */
/** @var \Smarty $smarty */
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
        'barcodes' => array('label' => _("Barcode/Weight/CMB")),
        'parts'    => array(
            'label' => _('Part sales'),
            'title' => _('Sales of associated part (include other suppliers)')
        ),
        'reorder'  => array('label' => _('Reorder')),

    );

}

/**
 * @var $supplier \Supplier
 */
$supplier=$state['_object'];

$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$edit_table_dialog = array(
    'new_item'         => array(
        'icon'      => 'plus',
        'title'     => _("New supplier's product"),
        'reference' => "supplier/".$state['key']."/part/new"
    ),
    'upload_items'     => array(
        'icon'         => 'plus',
        'label'        => _("Upload supplier's products"),
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
        'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $supplier->get('Code')),
    ),

);
$smarty->assign('edit_table_dialog', $edit_table_dialog);

$objects = 'supplier_part';

$edit_fields = get_export_edit_template_fields($objects);


if ($supplier->data['Supplier On Demand'] == 'No') {

    foreach ($edit_fields as $key => $value) {
        if ($value['name'] == 'Supplier Part On Demand') {
            unset($edit_fields[$key]);
            break;
        }
    }
}

$smarty->assign('edit_fields', $edit_fields);

if ($supplier->get('Supplier Type') != 'Archived') {
    $table_buttons = array();
    $table_buttons[] = array(
        'icon'  => 'edit_add',
        'title' => _("Edit supplier's products"),
        'id'    => 'edit_dialog'
    );
    $smarty->assign('table_buttons', $table_buttons);
}

$smarty->assign('table_top_template', 'supplier_parts.edit.tpl');
include 'utils/get_table_html.php';
