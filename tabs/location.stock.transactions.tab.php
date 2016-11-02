<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 17:05:48 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'location.stock.transactions';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'stock_transactions';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'note' => array(
        'label' => _('Note'),
        'title' => _('Note')
    ),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include 'utils/get_table_html.php';


?>
