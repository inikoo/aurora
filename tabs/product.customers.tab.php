<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 April 2017 at 12:08:23 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'product.customers';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customers';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'contact'  => array(
        'label' => _('Contact'),
        'title' => _('Contact details')
    ),
    'invoices' => array(
        'label' => _('Invoices/Balance'),
        'title' => _('Invoices & Account balance')
    ),
    'weblog'   => array(
        'label' => _('Weblog'),
        'title' => _('Weblog')
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
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
