<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 May 2018 at 16:56:57 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'category_customers';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'asset_customers';

$default = $user->get_tab_defaults($tab);



$smarty->assign('asset_code',$state['_object']->get('Code'));

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

$parameters = array(
    'parent'     => 'product_category',
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


