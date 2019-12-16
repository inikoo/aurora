<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 June 2016 at 13:49:17 CEST, Train (Nottingham-Sheffield) 
 Copyright (c) 2015, Inikoo

 Version 3

*/


if ($account->get('Account Warehouses') == 0) {

    $html = '<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'), '<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';

    return;
}


$tab     = 'part_families';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'part_families';

$default = $user->get_tab_defaults($tab);


$table_views   = array(
    'overview'    => array('label' => _('Overview')),
    'performance' => array('label' => _('Performance')),

    'stock'        => array('label' => _('Stock')),
    'sales'        => array('label' => _('Sales')),
    'dispatched_q' => array('label' => _('Dispatched (Qs)')),
    'dispatched_y' => array('label' => _('Dispatched (Yrs)')),
    'revenue_q'    => array('label' => _('Revenue (Qs)')),
    'revenue_y'    => array('label' => _('Revenue (Yrs)')),

);
$table_filters = array(
    'label' => array(
        'label' => _('Label'),
        'title' => _('Category label')
    ),
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'subject'    => 'part',
);

$table_buttons = array();


$sql = "select `Category Key` from `Category Dimension`";

$table_buttons[] = array(
    'icon'              => 'plus',
    'title'             => _('New category'),
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'    => 'Category_Code',
        'field_label' => _('Add category').':',
        'field_edit'  => 'string',
        'object'      => 'Category',
        'parent'      => 'Category',
        'parent_key'  => $account->get('Account Part Family Category Key'),
        'placeholder' => _("Category's code")
    )

);

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


