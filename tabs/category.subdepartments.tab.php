<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 16:34:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/




$tab     = 'category.product_categories.categories';
$ar_file = 'ar_products_tables.php';
$tipo    = 'sub_departments';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),
    'status'   => array('label' => _("Product's Status")),
    'stock'    => array('label' => _('Stock')),
    'sales'    => array('label' => _('Sales')),
    'sales_y'  => array('label' => _('Invoiced amount (Yrs)')),
    'sales_q'  => array('label' => _('Invoiced amount (Qs)')),

);
$title         = _('New sub-department');
$field_label   = _('Add sub-department').':';
$placeholder   = _("Sub-department code");
$table_filters = array(
    'label' => array('label' => _('Label')),
    'code'  => array('label' => _('Code')),

);



$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons[] = array(
    'icon'              => 'plus',
    'title'             => $title,
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'    => 'Category_Code',
        'field_label' => $field_label,
        'field_edit'  => 'string',
        'object'      => 'Category',
        'parent'      => $state['object'],
        'parent_key'  => $state['key'],
        'placeholder' => $placeholder
    )

);


$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



