<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 11:29:38 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'part.stock.cost';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'stock_cost';

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
