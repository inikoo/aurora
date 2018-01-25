<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 00:51:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'part_family.part_locations';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'part_family_part_locations';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
