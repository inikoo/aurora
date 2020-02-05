<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 16:39:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'category.part.sales.history';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'sales_history';

$default = $user->get_tab_defaults($tab);



$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


