<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 September 2015 14:43:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'orders.website';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_in_website';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array('label' => _('Customer')),
    'number'   => array('label' => _('Number')),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],
);





include('utils/get_table_html.php');


?>
