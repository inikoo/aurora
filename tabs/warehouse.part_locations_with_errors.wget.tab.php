<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 February 2017 at 10:25:02 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'warehouse.part_locations_with_errors.wget';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'part_locations_with_errors';

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
