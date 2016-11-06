<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 October 2016 at 14:21:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'supplier.part_locations_with_errors.wget';
$ar_file = 'ar_suppliers_tables.php';
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
