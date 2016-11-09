<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 November 2016 at 21:27:58 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'stores.sales.history';
$ar_file = 'ar_products_tables.php';
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
