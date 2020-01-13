<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 August 2018 at 00:31:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'customer.product.orders';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(

    'number'   => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),

);

$parameters = array(
    'parent'     => 'customer_product',
    'parent_key' => $state['parent_key'].'_'.$state['key'],
);


include('utils/get_table_html.php');



