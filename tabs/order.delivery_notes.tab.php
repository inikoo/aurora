<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  20 October 2015 at 16:34:15 BST, Lonoon UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'order.delivery_notes';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_notes';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');


?>
