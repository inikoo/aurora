<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 August 2017 at 13:29:52 GMT+5:30, Delhi Airport, India
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'orders.packed';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_packed';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array('label' => _('Customer')),
    'number'   => array('label' => _('Number')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);




include('utils/get_table_html.php');



