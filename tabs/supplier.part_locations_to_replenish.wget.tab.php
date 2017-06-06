<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2017 at 13:08:04 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'supplier.part_locations_to_replenish.wget';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'replenishments';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'location' => array('label' => _('Location')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include 'utils/get_table_html.php';


?>
