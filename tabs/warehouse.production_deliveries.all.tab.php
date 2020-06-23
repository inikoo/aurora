<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Tuesday, 23 June 2020, 3:17 pm, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

$tab     = 'warehouse.production_deliveries.all';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'production_deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'section'    => 'all',

);

include('utils/get_table_html.php');
