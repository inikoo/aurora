<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2017 at 15:26:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'warehouse.parts_to_replenish_picking_location.wget';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'parts_to_replenish_picking_location';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include 'utils/get_table_html.php';


?>
