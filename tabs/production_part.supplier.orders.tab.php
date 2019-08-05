<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-07-2019 18:33:17 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'production_part.supplier.orders';
$ar_file = 'ar_production_tables.php';
$tipo    = 'orders_with_part';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Job order number')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


