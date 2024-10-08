<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2015 01:06:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'customers.categories';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'categories';

$default = $user->get_tab_defaults($tab);


$table_views = array();

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
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],
    'subject'    => 'customer',
);


$table_buttons   = [];
$table_buttons[] = array(
    'icon'              => 'plus',
    'title'             => _('New category'),
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'    => 'Category_Code',
        'field_label' => _('New category code'),
        'field_edit'  => 'string',
        'object'      => 'Category',
        'parent'      => 'Category',
        'parent_key'  => $state['_parent']->properties('customer_root_category_key'),
        'placeholder' => _("Category code"),
        'other_fields'=>json_encode(['Category Branch Type'=>'Node'])
    )

);

$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');


