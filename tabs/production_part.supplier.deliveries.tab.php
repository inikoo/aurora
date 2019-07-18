<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-07-2019 20:31:03 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019 Inikoo

 Version 3

*/

$tab     = 'production_part.supplier.deliveries';
$ar_file = 'ar_production_tables.php';
$tipo    = 'deliveries_with_part';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include 'utils/get_table_html.php';


