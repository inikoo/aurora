<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  14 September 2017 at 16:33:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'charge.orders';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),
    'number'   => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');


?>
