<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 june 2021 23:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/
/** @var array $state */
/** @var \User $user */
/** @var \Smarty $smarty */
include_once 'utils/get_export_edit_template_fields.php';


$tab = 'customer.parts';

$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'barcodes' => array('label' => _("Barcode/Weight/CMB")),
    'parts'    => array(
        'label' => _('Part sales'),
        'title' => _('Sales of associated part')
    ),

);


$customer = $state['_object'];

$ar_file = 'ar_fulfilment_tables.php';
$tipo    = 'fulfilment_parts';

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
        'title'     => _("New customer part"),
        'reference' => "fulfilment/".$state['parent_key']."/customers/".$state['key']."/parts/new"
    ),
    'upload_items'     => array(
        'icon'         => 'plus',
        'label'        => _("Upload customer's products"),
        'template_url' => '/upload_arrangement.php?object=customer_part&parent=customer&parent_key='.$state['key'],

        'tipo'       => 'edit_objects',
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

        'object' => 'customer_part',
    ),
    'inline_edit'      => array(),
    'spreadsheet_edit' => array(
        'tipo'        => 'edit_objects',
        'parent'      => $state['object'],
        'parent_key'  => $state['key'],
        'object'      => 'customer_part',
        'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $customer->get('Name')),
    ),

);
$smarty->assign('edit_table_dialog', $edit_table_dialog);


$edit_fields = get_export_edit_template_fields('parts');


$smarty->assign('edit_fields', $edit_fields);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'  => 'edit_add',
    'title' => _("Edit customer's products"),
    'id'    => 'edit_dialog'
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';
