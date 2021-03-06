<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11 August 2017 at 19:13:57 CEST, Tranava, Slovakia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'orders';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders';

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
