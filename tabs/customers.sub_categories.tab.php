<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2021 17:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/


$tab     = 'customers.sub_categories';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'sub_categories';


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
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons[] = array(
    'icon'              => 'plus',
    'title'             => _('New category'),
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'    => 'Category_Code',
        'field_label' => _('Add category').':',
        'field_edit'  => 'string',
        'object'      => 'Category',
        'parent'      => $state['object'],
        'parent_key'  => $state['key'],
        'placeholder' => _("Category's code")
    )

);

$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



