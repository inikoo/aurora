<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 June 2017 at 19:23:56 GMT+8, KLIA2, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'supplier.parts_to_replenish_picking_location.wget';
$ar_file = 'ar_suppliers_tables.php';
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
