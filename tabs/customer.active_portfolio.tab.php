<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  Wed 16 Oct 2019 11:19:41 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'customer.active_portfolio';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customer_portfolio';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview')
    ),


);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Product name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
    'type'       => 'Active'

);


include('utils/get_table_html.php');


