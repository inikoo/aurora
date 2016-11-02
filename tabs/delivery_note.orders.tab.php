<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  21 October 2015 at 16:43:00 BST, Gatwick Airport UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'delivery_note.orders';
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
