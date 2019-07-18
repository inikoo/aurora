<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16-07-2019 14:24:25 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019 Inikoo

 Version 3

*/

$tab     = 'production_supplier.deliveries';
$ar_file = 'ar_production_tables.php';
$tipo    = 'production_deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);

$parameters = array(
    'parent'     => 'production_supplier',
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


