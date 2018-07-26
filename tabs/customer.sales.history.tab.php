<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 13:22:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'customer.sales.history';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'sales_history';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


?>
