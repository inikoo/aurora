<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 21:24:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'supplier.timeseries_record.families';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'timeseries_drill_down_families';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array('label' => _('Code')),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
