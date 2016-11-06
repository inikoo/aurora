<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 October 2015 at 11:47:09 CEST, Rome Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'invoice.orders';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders';
$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');


?>
