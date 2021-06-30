<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2021 22:02 , Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'customer_category.customers';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customers';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Customer name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Customer email')
    ),
    'company_name' => array(
        'label' => _('Company name'),
        'title' => _('Company name')
    ),
    'contact_name' => array(
        'label' => _('Contact name'),
        'title' => _('Contact name')
    )

);

$parameters    = array(
    'parent'          => 'category',
    'parent_key'      => $state['key'],
    'grandparent_key' => $state['_object']->get('Category Parent Key'),
    'store_key'       => $state['_object']->get('Category Store Key'),

);
$table_buttons = [];

$table_buttons[] = array(
    'icon'              => 'link',
    'title'             => _('Associate product'),
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'                 => 'Customer_Key',
        'field_label'              => _('Associate customer').': ',
        'field_edit'               => 'dropdown',
        'object'                   => 'Associate_Customer_Category',
        'parent'                   => $state['object'],
        'parent_key'               => $state['key'],
        'placeholder'              => _("Customer ID/name"),
        'dropdown_select_metadata' => base64_encode(
            json_encode(
                array(
                    'scope'      => 'customers',
                    'parent'     => 'store',
                    'parent_key' => $state['_object']->get('Category Store Key'),
                    'options'    => array()
                )
            )
        )
    )

);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


