<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 April 2016 at 14:05:48 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'part.stock.transactions';
$ar_file = 'ar_inventory_tables.php';
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
