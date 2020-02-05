<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 September 2016 at 14:04:45 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'supplier.sales.history';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'sales_history';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';



