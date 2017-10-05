<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 October 2017 at 22:51:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'stock_leakages';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'stock_leakages';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include 'utils/get_table_html.php';


?>
